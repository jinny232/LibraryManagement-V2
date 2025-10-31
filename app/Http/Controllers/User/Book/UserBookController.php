<?php

namespace App\Http\Controllers\User\Book;

use App\Models\Book;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Borrowing;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserBookController extends Controller
{
    /**
     * Display a paginated list of all books with search functionality.
     */
  // In your BookController.php

public function index(Request $request)
{
    // Start with a query to get all books
    $query = Book::query();

    // Check for a general search term
    $searchTerm = $request->input('search');
    if ($searchTerm) {
        $query->where('title', 'like', "%{$searchTerm}%")
              ->orWhere('author', 'like', "%{$searchTerm}%");
    }

    // Check for a specific category filter
    $selectedCategoryId = $request->input('category');
        if ($selectedCategoryId) {
            $query->where('category_id', $selectedCategoryId);
        }

    // Fetch unique categories to populate the dropdown
    $categories = Category::orderBy('name')->pluck('name', 'id');

    // Fetch the books, ordered by title, and paginate the results
    $books = $query->orderBy('title')->paginate(12);

    // Pass all necessary variables to the view
    return view('user.books.index', compact('books', 'searchTerm', 'categories', 'selectedCategoryId'));
}

    /**
     * Display the details of a specific book.
     */
    public function show(Book $book)
    {
        // The Book model is automatically resolved by the route model binding
        return view('user.books.show', compact('book'));
    }

    /**
     * Automatically create a new borrowing record for a book.
     * This method bypasses the 'pending' request stage and immediately borrows the book.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function borrow(Request $request)
    {
        // 1. Validate the incoming request to ensure a book_id is present and exists
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        // 2. Get the authenticated member from your custom authentication method
        // You'll need to adapt this based on how your member authentication works
        $memberId = session('member_id');
        $member = Member::find($memberId);

        if (!$member) {
            // If the user isn't logged in, redirect them with an error
            return back()->with('error', 'You must be logged in to borrow a book.');
        }

        // 3. Start a database transaction for data integrity
        DB::beginTransaction();

        try {
            // Find the book and check for available copies
            $book = Book::findOrFail($request->book_id);

            if ($book->available_copies < 1) {
                // If no copies are available, roll back the transaction and return an error
                DB::rollBack();
                return back()->with('error', 'This book is not available for borrowing at this time.');
            }

            // 4. Create the new borrowing record
            $dueDate = Carbon::now()->addDays(14); // Set the due date for 14 days from now

            Borrowing::create([
                'member_id' => $member->member_id,
                'book_id' => $request->book_id,
                'borrow_date' => now(),
                'due_date' => $dueDate,
                'status' => 'pending', // Directly set the status to 'borrowed'
            ]);

            // 5. Decrement the available copies of the book
            $book->decrement('available_copies');

            // 6. Commit the transaction
            DB::commit();

            // 7. Redirect with a success message
            return redirect()->route('user.books.index')->with('success', 'Book successfully borrowed!');

        } catch (\Exception $e) {
            // If any part of the process fails, roll back the transaction
            DB::rollBack();
            return back()->with('error', 'Failed to borrow the book. Please try again.');
        }
    }
}
