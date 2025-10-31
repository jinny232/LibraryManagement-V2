<?php

namespace App\Http\Controllers\Admin\Book;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Shelf;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf; // Import the Dompdf facade

class BookController extends Controller
{
    /**
     * Display a listing of the books with search, sort, and filter capabilities.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Start with a base query to retrieve all books and eager load relationships
        $query = Book::with(['category', 'shelf']);

        // 1. Handle Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('author', 'like', '%' . $search . '%')
                    ->orWhere('isbn', 'like', '%' . $search . '%')
                    ->orWhereHas('category', function ($cq) use ($search) {
                        $cq->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('shelf', function ($sq) use ($search) {
                        $sq->where('shelf_number', 'like', '%' . $search . '%')
                            ->orWhere('row_number', 'like', '%' . $search . '%')
                            ->orWhere('sub_col_number', 'like', '%' . $search . '%');
                    });
            });
        }

        // 2. Handle Filtering
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        $membersQuery = Member::query();

        if ($request->filled('major')) {
            $membersQuery->where('major', $request->input('major'));
        }

        if ($request->filled('year')) {
            $membersQuery->where('year', $request->input('year'));
        }

        if ($request->filled('major') || $request->filled('year')) {
            // Get all book IDs borrowed by members of the specified major and/or year.
            $bookIds = $membersQuery->with('borrowings')
                ->get()
                ->pluck('borrowings')
                ->flatten()
                ->pluck('book_id')
                ->unique()
                ->toArray();
            $query->whereIn('id', $bookIds);
        }

        // 3. Handle Sorting
        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'asc');
        $query->orderBy($sortColumn, $sortDirection);

        // Get the books after applying all filters and sorting
        $books = $query->paginate(10);

        // Prepare data for Charts
        $categories = Category::all();
        $majors = Member::select('major')->distinct()->pluck('major');
        $years = Member::select('year')->distinct()->pluck('year');

        // Chart data for books per major
        $booksPerMajor = DB::table('borrowings')
            ->join('members', 'borrowings.member_id', '=', 'members.member_id')
            ->select('members.major', DB::raw('count(distinct borrowings.book_id) as book_count'))
            ->groupBy('members.major')
            ->get();

        // Chart data for books per year
        $booksPerYear = DB::table('borrowings')
            ->join('members', 'borrowings.member_id', '=', 'members.member_id')
            ->select('members.year', DB::raw('count(distinct borrowings.book_id) as book_count'))
            ->groupBy('members.year')
            ->get();

        return view('admin.books.index', compact(
            'books',
            'categories',
            'majors',
            'years',
            'booksPerMajor',
            'booksPerYear'
        ));
    }

    /**
     * Show the form for creating a new book.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::all();
        $shelves = Shelf::all();
        $lastBookId = Book::max('id') ?? 0;
        $newIsbn = str_pad($lastBookId + 1, 8, '0', STR_PAD_LEFT);
        return view('admin.books.create', compact('categories', 'shelves','newIsbn'));
    }

    /**
     * Store a newly created book in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
public function store(Request $request)
    {
        // 1. Validate the incoming request data.
        $validatedData = $request->validate([
            'title'              => 'required|string|max:255',
            'author'             => 'required|string|max:255',
            'isbn'               => 'required|string|unique:books,isbn|max:8',
            'category_id'        => 'required|integer|exists:categories,id',
            'shelf_id'           => 'required|integer|exists:shelves,id',
            'total_copies'       => 'required|integer|min:1',
            'image'              => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // 2. Handle the image upload and get the file path.
        if ($request->hasFile('image')) {
            $validatedData['image'] = $request->file('image')->store('images/books', 'public');
        }

        // 3. Set the initial available copies to the total copies.
        $validatedData['available_copies'] = $validatedData['total_copies'];

        // 4. Generate the barcode for the book's ISBN and save it.
        $validatedData['barcode'] = DNS1D::getBarcodeHTML($validatedData['isbn'], 'EAN13');

        // 5. Create the new book record and assign it to a variable.
        $book = Book::create($validatedData);

        // 6. Redirect to the confirmation page, passing the newly created book's ID.
        return redirect()->route('admin.books.confirm', $book->id)
             ->with('success', 'Book created successfully.');
    }
public function confirm($id)
    {
        // You can use the ID to find the book from the database.
        $book = Book::findOrFail($id);

        // Return a view with the book data to display a confirmation message.
        return view('admin.books.confirm', compact('book'));
    }
    /**
     * Display the specified book.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\View\View
     */
    public function show(Book $book)
    {
        // Eager load relationships for the show view
        $book->load(['category', 'shelf']);
        return view('admin.books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified book.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\View\View
     */
    public function edit(Book $book)
    {
        $categories = Category::all();
        $shelves = Shelf::all();
        return view('admin.books.edit', compact('book', 'categories', 'shelves'));
    }

    /**
     * Update the specified book in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Book $book)
    {
        // Validate all required fields for the update operation
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:8|unique:books,isbn,' . $book->id,
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')
            ],
            'shelf_id' => [
                'required',
                'integer',
                Rule::exists('shelves', 'id')
            ],
            'total_copies' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Calculate the number of books currently borrowed
        $borrowedCopies = $book->total_copies - $book->available_copies;
        $newTotalCopies = $validatedData['total_copies'];

        // Ensure the new total number of copies is not less than the number of borrowed copies
        if ($newTotalCopies < $borrowedCopies) {
            throw ValidationException::withMessages([
                'total_copies' => 'The total number of copies cannot be less than the number of books currently borrowed (' . $borrowedCopies . ').'
            ]);
        }

        // Handle the image upload
        if ($request->hasFile('image')) {
            // Delete old image if it exists from the public disk.
            if ($book->image) {
                Storage::disk('public')->delete($book->image);
            }
            // Store the new image to the public disk and get the path.
            $validatedData['image'] = $request->file('image')->store('images/books', 'public');
        }

        // Calculate the new number of available copies
        $validatedData['available_copies'] = $newTotalCopies - $borrowedCopies;

        $book->update($validatedData);

        return redirect()->route('admin.books.index')
            ->with('success', 'Book updated successfully.');
    }

    /**
     * Remove the specified book from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Book $book)
    {
        // Delete the image from the public disk before deleting the book
        if ($book->image) {
            Storage::disk('public')->delete($book->image);
        }

        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Book deleted successfully.');
    }

    /**
     * Display a listing of only the available books.
     *
     * @return \Illuminate\View\View
     */
    public function available()
    {
        $books = Book::where('available_copies', '>', 0)->get();
        return view('admin.books.available', compact('books'));
    }

    /**
     * Export the book's barcode as a downloadable PNG image.
     *
     * @param \App\Models\Book $book
     * @return \Illuminate\Http\Response
     */
    public function exportBarcode(Book $book)
    {
        // Generate the barcode data as a PNG image
        $barcode = DNS1D::getBarcodePNG($book->isbn, 'EAN13');

        // Create a downloadable response with the image data
        return Response::make($barcode, 200, [
            'Content-Type'        => 'image/png',
            'Content-Disposition' => 'attachment; filename="barcode_' . $book->isbn . '.png"',
        ]);
    }

    /**
     * Export the book's barcode and details as a downloadable PDF.
     *
     * @param \App\Models\Book $book
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Book $book)
    {
        // Generate a new barcode HTML to embed in the PDF
        $book->barcode = DNS1D::getBarcodeHTML($book->isbn, 'EAN13', 2, 60);

        // Load the view and pass the book data to it
        $pdf = Pdf::loadView('admin.books.barcode', compact('book'));

        // Stream the PDF to the browser as a download
        return $pdf->download('barcode_for_' . Str::slug($book->title) . '.pdf');
    }
}
