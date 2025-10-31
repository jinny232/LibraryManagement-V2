@extends('layouts.app')

@section('content')
<style>
    /* Import a clean, modern font */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    body {
        background-color: #f8f9fa; /* A light, modern off-white background */
        color: #212529; /* Dark text for readability */
        font-family: 'Inter', sans-serif; /* Clean, modern sans-serif font */
        min-height: 100vh;
    }

    /* Modern Container with rounded corners and a soft shadow */
    .modern-container {
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 25px; /* Consistent rounded corners */
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        max-width: 1200px;
        margin: 2rem auto;
    }

    /* Header with a clean style and gradient line */
    .modern-header h2 {
        color: #343a40;
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .modern-gradient-line {
        height: 3px;
        width: 100%;
        background: linear-gradient(90deg, #6a11cb, #2575fc);
        border-radius: 2px;
    }

    /* Primary button with a vibrant gradient */
    .modern-btn {
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s ease;
        padding: 10px 20px;
        text-transform: capitalize;
        background: linear-gradient(90deg, #6a11cb, #2575fc);
        color: #ffffff;
        border: none;
        box-shadow: 0 4px 10px rgba(106, 17, 203, 0.2);
    }

    .modern-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(106, 17, 203, 0.3);
    }

    /* Secondary button with a clean look */
    .modern-btn-secondary {
        font-family: 'Inter', sans-serif;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.3s ease;
        padding: 5px 12px;
        text-transform: capitalize;
        background: #f1f3f5;
        color: #343a40;
        border: 1px solid #ced4da;
    }

    .modern-btn-secondary:hover {
        background: #e9ecef;
        transform: translateY(-1px);
    }

    /* Table styling */
    .modern-table-wrapper {
        overflow-x: auto;
        max-height: 450px;
        border-radius: 12px;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);

        /* Hide the scrollbar but keep functionality */
        -ms-overflow-style: none; /* For Internet Explorer and Edge */
        scrollbar-width: none; /* For Firefox */
    }

    /* For WebKit browsers (Chrome, Safari, etc.) */
    .modern-table-wrapper::-webkit-scrollbar {
        display: none;
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Inter', sans-serif;
    }

    .modern-table thead {
        background-color: #f1f3f5;
        border-bottom: 2px solid #e9ecef;
    }

    .modern-table th, .modern-table td {
        padding: 15px;
        border-bottom: 1px solid #e9ecef;
        text-align: left;
    }

    .modern-table th {
        color: #495057;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.9rem;
    }

    .modern-table tbody tr {
        transition: background-color 0.2s ease;
    }

    .modern-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* Modern alert message styling */
    .modern-alert-success {
        background-color: #e6f7e9;
        color: #28a745;
        border: 1px solid #c3e6cb;
        border-radius: 8px;
        padding: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .modern-alert-info {
        background-color: #e9f0ff;
        color: #004085;
        border: 1px solid #cce5ff;
        border-radius: 8px;
        padding: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
</style>

<div class="flex flex-col items-center p-4">
    <div class="w-full modern-container">
        <div class="flex justify-between items-center mb-6">
            <div class="modern-header">
                <h2 class="text-3xl">All Books</h2>
                <div class="modern-gradient-line"></div>
            </div>
            <a href="{{ route('admin.books.create') }}" class="modern-btn">
                Add New Book
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 modern-alert-success">{{ session('success') }}</div>
        @endif

        @if ($books->count())
            <div class="modern-table-wrapper">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>ISBN</th>
                            <th>Total Copies</th>
                            <th>Available Copies</th>
                            <th>Shelf No</th>
                            <th>Row No</th>
                            <th>Sub Col No</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($books as $index => $book)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $book->title }}</td>
                                <td>{{ $book->author }}</td>
                                <td>{{ $book->isbn }}</td>
                                <td>{{ $book->total_copies }}</td>
                                <td>{{ $book->available_copies }}</td>
                                <td>{{ $book->shelf_number }}</td>
                                <td>{{ $book->row_number }}</td>
                                <td>{{ $book->sub_col_number }}</td>
                                <td>
                                    <a href="{{ route('admin.books.show', $book->id) }}" class="modern-btn-secondary">Details</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="modern-alert-info text-center">No books found.</div>
        @endif
    </div>
</div>
@endsection
