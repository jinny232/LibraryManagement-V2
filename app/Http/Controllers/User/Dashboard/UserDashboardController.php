<?php

namespace App\Http\Controllers\User\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserDashboardController extends Controller
{
    public function index()
    {
        $memberName = session('member_name');

        // Fetch the IDs of the most borrowed books by counting entries in the borrowings table.
        $mostBorrowedBookIds = Borrowing::select('book_id', DB::raw('count(*) as total_borrows'))
            ->groupBy('book_id')
            ->orderBy('total_borrows', 'desc')
            ->take(10)
            ->pluck('book_id');

        // Fetch the actual book details for the most borrowed books.
        // It's often good practice to eager load the category here as well to avoid N+1 queries in the view.
        $mostBorrowedBooks = Book::whereIn('id', $mostBorrowedBookIds)->with('category')->get();

        // **Optimized Code:**
        // Instead of fetching all books, fetch a limited number (e.g., the latest 50)
        // and eager load the category relationship to prevent N+1 queries.
        $booksByCategory = Book::with('category')
            ->latest() // Order by latest books
            ->take(50) // Limit to a reasonable number, like 50
            ->get()
            ->groupBy('category.name'); // Group the collection by the category name

        // Pass all the necessary data to the homepage view.
        return view('user.homepage', compact('memberName', 'mostBorrowedBooks', 'booksByCategory'));
    }
}
