<?php

namespace App\Http\Controllers\User\Book;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BookRequest;
use App\Models\Member; // Make sure this model exists

class BookRequestController extends Controller
{
    /**
     * Store a new book request.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        // Get the authenticated member from the session ID, which is your custom auth method.
        $memberId = session('member_id');
        $member = Member::find($memberId);

        // This check acts as a fallback to the middleware
        if (!$member) {
            return back()->with('error', 'You must be a registered member to book a book.');
        }

        // Create the new book request
        BookRequest::create([
            'book_id' => $request->book_id,
            'requester_name' => $member->name,
            'requester_email' => $member->email,
            'requester_phone' => $member->phone_number,
        ]);

        // Redirect back to the book details page with a success message
        return back()->with('success', 'Your request to book the book has been sent!');
    }
}
