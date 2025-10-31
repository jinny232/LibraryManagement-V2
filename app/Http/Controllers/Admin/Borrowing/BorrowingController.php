<?php

namespace App\Http\Controllers\Admin\Borrowing;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Member;
use App\Models\Reservation;
use App\Models\Category; // Add the Category model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BorrowingController extends Controller
{
    /**
     * Display a paginated list of all borrowings.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $borrowings = Borrowing::with(['member', 'book'])->paginate(20);
        return view('admin.borrowings.index', compact('borrowings'));
    }

    /**
     * Show the form for creating a new borrowing record.
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        // Retrieve available books
        $books = Book::where('available_copies', '>', 0)->get();

        // Retrieve distinct years for member selection
        $years = Member::distinct()->pluck('year')->sort();
        $members = Member::select('member_id', 'name', 'year', 'major', 'roll_no')->get();

        // Retrieve distinct book categories from the Category model
        $bookCategories = Category::distinct()->pluck('name')->sort();

        // Get selected member_id and book_id from request
        $selectedMember = $request->input('member_id');
        $selectedBook   = $request->input('book_id');

        return view('admin.borrowings.create', compact(
            'years',
            'members',
            'bookCategories',
            'books',
            'selectedMember',
            'selectedBook'
        ));
    }

    /**
     * Store a new borrowing record in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,member_id',
            'book_id' => 'required|exists:books,id',
            // 'due_date' is now automatically calculated and not validated from the request
        ]);

        $book = Book::findOrFail($request->book_id);

        if ($book->available_copies < 1) {
            return back()->withErrors('Book is not available.');
        }

        // Automatically calculate the due date as 5 days from now
        $dueDate = Carbon::now()->addDays(5);

        Borrowing::create([
            'member_id' => $request->member_id,
            'book_id' => $request->book_id,
            'borrow_date' => now(),
            'due_date' => $dueDate,
            'status' => 'borrowed',
        ]);

        $book->decrement('available_copies');

        return redirect()->route('admin.borrowings.create')->with('success', 'Book borrowed successfully!');
    }

    /**
     * Display a list of borrowings that are pending return.
     *
     * @return \Illuminate\View\View
     */
    public function pendingReturn()
    {
        $borrowings = Borrowing::whereNull('return_date')->with(['book', 'member'])->get();
        return view('admin.borrowings.pending-return', compact('borrowings'));
    }

    /**
     * Display the details of a specific borrowing record.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $borrowing = Borrowing::with(['member', 'book'])->findOrFail($id);
        return view('admin.borrowings.show', compact('borrowing'));
    }

    /**
     * Process the return of a book.
     *
     * @param  \App\Models\Borrowing  $borrowing
     * @return \Illuminate\Http\RedirectResponse
     */
    public function returnBook(Borrowing $borrowing)
    {
        if ($borrowing->return_date) {
            return redirect()->back()->with('error', 'This book is already returned.');
        }

        $borrowing->return_date = now();
        $borrowing->status = 'returned';
        $borrowing->save();

        $borrowing->book->increment('available_copies');

        return redirect()->route('admin.borrowings.pending-return')->with('success', 'Book returned successfully.');
    }

    /**
     * Display a list of returned borrowings.
     *
     * @return \Illuminate\View\View
     */
    public function returned()
    {
        $borrowings = Borrowing::where('status', 'returned')->get();
        return view('admin.borrowings.returned', compact('borrowings'));
    }

    /**
     * Display a list of overdue borrowings.
     *
     * @return \Illuminate\View\View
     */
public function overdue()
{
       $borrowings = Borrowing::whereNull('return_date')
                          ->where('due_date', '<', Carbon::now())
                          ->where('due_date', '<=', Carbon::now()->subDays(5))
                          ->with(['member', 'book'])
                          ->get();

    return view('admin.borrowings.overdue', compact('borrowings'));
}

    /**
     * Process the renewal of a borrowed book.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Borrowing  $borrowing
     * @return \Illuminate\Http\RedirectResponse
     */
    public function renewBook(Request $request, Borrowing $borrowing)
    {
        // Check if the book has already been returned
        if ($borrowing->return_date) {
            return back()->withErrors('This book has already been returned.');
        }

        // Check if the book has exceeded the renewal limit (e.g., 1 renewal)
        if ($borrowing->renewal_count >= 1) {
            return back()->withErrors('This book has already been renewed once.');
        }

        // Use a database transaction to ensure data consistency
        try {
            DB::beginTransaction();

            // Update the due date (e.g., extend by 2 weeks)
            $borrowing->due_date = Carbon::parse($borrowing->due_date)->addWeeks(2);
            // Increment the renewal count
            $borrowing->renewal_count += 1;
            // Update the status if it was overdue
            if ($borrowing->status === 'overdue') {
                $borrowing->status = 'borrowed';
            }
            $borrowing->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('An error occurred during renewal. Please try again.');
        }

        return back()->with('success', 'Book renewed successfully. New due date is ' . $borrowing->due_date->format('Y-m-d') . '.');
    }
}
