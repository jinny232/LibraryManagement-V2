<?php

namespace App\Http\Controllers\User\Borrowed;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Borrowing;

class UserBorrowedController extends Controller
{
    /**
     * Display a listing of the user's borrowed books, with an optional filter.
     */
    public function index(Request $request)
    {
        // Get the authenticated member's ID from the session
        $memberId = session('member_id');

        // Start with the base query for the authenticated member's books
        $query = Borrowing::where('member_id', $memberId)->with('book');

        // Check for a status filter from the request
        $status = $request->query('status');

        if ($status && $status !== 'all') {
            // Special case for 'overdue' status
            if ($status === 'late') {
              $query->where('status', 'late');

            } else {
                // If a standard status is requested, add a where clause
                $query->where('status', $status);
            }
        }

        // Execute the query and get the borrowed books
        $borrowedBooks = $query->latest()->get();

        // Pass the borrowed books to the view
        return view('user.borrowed.index', compact('borrowedBooks'));
    }
}
