@extends('layouts.app')

@section('title', 'Borrowing Details')

@section('content')
<head>
    <!-- Use Tailwind CSS for a modern, responsive layout -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<!-- Main container for the entire page content. It's centered and adds subtle padding. -->
<div class="max-h-[90vh] flex flex-col items-center p-4 sm:p-6 ">
    <div class="w-full max-w-4xl bg-white rounded-2xl shadow-lg p-6 sm:p-10 border border-gray-100 mt-8 mb-8">

        <!-- Header Section -->
        <!-- A clean, simple header with a soft underline. -->
        <h2 class="text-3xl sm:text-4xl font-bold text-center text-gray-800 pb-4 mb-8 border-b-2 border-gray-200">
            Borrowing Details
        </h2>

        <!-- This container has a fixed height and allows its content to scroll independently. -->
        <div class=" overflow-y-auto pr-2">

            <!-- Main Content Grid -->
            <!-- This is a responsive two-column grid layout for the member and book details. -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">

                <!-- Member Details Card -->
                <!-- A clean card for member information with a subtle shadow and rounded corners. -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <div class="flex flex-col items-center text-center">
                        <!-- Member Image Placeholder -->
                        <img  src="{{ asset('storage/' . $borrowing->member->image) }}" alt="Member Image" class="rounded-full w-36 h-36 mb-4 object-cover border-4 border-white shadow-md">
                        <h3 class="text-xl font-semibold mb-2 text-gray-700">🧑‍🎓 Member Details</h3>
                    </div>
                    <div class="space-y-3 mt-4 text-sm sm:text-base text-gray-600">
                        <p><strong>📝 Name:</strong> {{ $borrowing->member->name }}</p>
                        <p><strong>📧 Email:</strong> {{ $borrowing->member->email }}</p>
                        <p><strong>🆔 Roll No:</strong> {{ $borrowing->member->roll_no }}</p>
                        <p><strong>🎓 Major:</strong> {{ $borrowing->member->major }}</p>
                        <p><strong>🗓️ Year:</strong> {{ $borrowing->member->year }}</p>
                        <p><strong>🚻 Gender:</strong> {{ $borrowing->member->gender }}</p>
                        <p><strong>📞 Phone:</strong> {{ $borrowing->member->phone_number }}</p>
                    </div>
                </div>

                <!-- Book Details Card -->
                <!-- A clean card for book information, matching the member details card style. -->
                @if ($borrowing->book)
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <div class="flex flex-col items-center text-center">
                        <!-- Book Image Placeholder -->
                        <img  src="{{ asset('storage/' . $borrowing->book->image) }}" alt="Book Cover" class="rounded-lg w-36 h-48 mb-4 object-cover border border-gray-300 shadow-md">
                        <h3 class="text-xl font-semibold mb-2 text-gray-700">📚 Book Details</h3>
                    </div>
                    <div class="space-y-3 mt-4 text-sm sm:text-base text-gray-600">
                        <p><strong>📖 Title:</strong> {{ $borrowing->book->title }}</p>
                        <p><strong>✍️ Author:</strong> {{ $borrowing->book->author }}</p>
                        <p><strong>🏷️ Category:</strong> {{ $borrowing->book->category->name }}</p>
                        <p><strong>🔖 ISBN:</strong> {{ $borrowing->book->isbn }}</p>
                        <p><strong>🔢 Book ID:</strong> {{ $borrowing->book->id }}</p>
                        <p><strong>🗄️ Shelf:</strong> {{ $borrowing->book->shelf->shelf_number }}</p>
                        <p><strong>↕️ Row:</strong> {{ $borrowing->book->shelf->row_number }}</p>
                        <p><strong>↔️ Sub-Column:</strong> {{ $borrowing->book->shelf->sub_col_number }}</p>
                    </div>
                </div>
                @else
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm flex items-center justify-center">
                    <p class="text-center text-red-500 font-medium">Book details not found. The book for this borrowing record may have been deleted.</p>
                </div>
                @endif
            </div>

            <!-- Borrowing Information Section -->
            <!-- This section displays the details of the borrowing transaction itself. -->
            <div class="mt-8 bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <h3 class="text-xl font-semibold text-center mb-6 text-gray-700">ℹ️ Borrowing Information</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-sm sm:text-base text-gray-600">
                    <p><strong>🗓️ Borrow Date:</strong> {{ $borrowing->borrow_date->format('F j, Y') }}</p>
                    <p><strong>⏳ Due Date:</strong> {{ $borrowing->due_date->format('F j, Y') }}</p>
                    <p><strong>↩️ Return Date:</strong> {{ $borrowing->return_date?->format('F j, Y') ?? 'Not Returned' }}</p>
                    <p><strong>📊 Status:</strong> <span class="font-bold {{ $borrowing->status === 'overdue' ? 'text-red-500' : ($borrowing->status === 'returned' ? 'text-green-500' : 'text-gray-500') }}">{{ ucfirst($borrowing->status) }}</span></p>
                </div>
            </div>
        </div>

        <!-- Back Button Section -->
        <div class="mt-8 text-center">
            <a href="{{ route('admin.borrowings.index') }}" class="inline-block bg-gray-800 text-white font-medium py-3 px-6 rounded-full shadow-lg hover:bg-gray-700 transition duration-300 transform hover:scale-105">
                ⬅️ Back to Borrowings List
            </a>
        </div>
    </div>
</div>


@endsection
