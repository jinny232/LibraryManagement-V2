<?php

namespace App\Http\Controllers\User\Book;

use App\Http\Controllers\Controller;
use App\Models\BookRequest;
use App\Models\Member;
use App\Models\Book;
use Illuminate\Http\Request;

class UserBookingsController extends Controller
{
    /**
     * Display a list of all books requested by the authenticated member.
     */
    public function index()
    {
        // Get the authenticated member's ID from the session
        $memberId = session('member_id');
        $member = Member::find($memberId);

        // Check if the member is authenticated
        if (!$member) {
            // This should be caught by the middleware, but serves as a fallback
            return back()->with('error', 'You must be a registered member to view your bookings.');
        }

        // Fetch all book requests for the member using their email
        $requestedBooks = BookRequest::with('book')
                                    ->where('requester_email', $member->email)
                                    ->latest()
                                    ->get();

        // Pass the data to the view
        return view('user.bookings.index', compact('requestedBooks'));
    }
}
