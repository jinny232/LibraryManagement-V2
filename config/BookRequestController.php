<?php
namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookRequest;
use Illuminate\Http\Request;

class BookRequestController extends Controller
{
    public function store(Request $request, Book $book)
    {
        $request->validate([
            'requester_name' => 'required|string|max:255',
            'requester_email' => 'nullable|email|max:255',
            'requester_phone' => 'nullable|string|max:20',
        ]);

        BookRequest::create([
            'book_id' => $book->id,
            'requester_name' => $request->input('requester_name'),
            'requester_email' => $request->input('requester_email'),
            'requester_phone' => $request->input('requester_phone'),
        ]);

        return redirect()->back()->with('success', 'Your request has been submitted. We will contact you soon.');
    }
}
