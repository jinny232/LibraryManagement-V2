@extends('layouts.appuser')

@section('title', 'My Borrowed Books')

@section('content')
    <!-- Tailwind CSS CDN for styling -->
    <script src="https://cdn.tailwindcss.com"></script>

    <div class="container mx-auto px-4 py-8 md:py-12 bg-white rounded-lg  ">
        <h1
            class="text-3xl font-extrabold text-center mb-6 md:mb-10 bg-clip-text text-transparent bg-gradient-to-r from-purple-500 to-blue-500 transform transition-transform duration-300 hover:scale-105">
            My Borrowed Books</h1>

        {{-- Filter controls --}}
        <div class="flex flex-col md:flex-row justify-center items-center mb-8 space-y-4 md:space-y-0 md:space-x-4">
            <div class="flex rounded-lg shadow-md overflow-hidden">
                <a href="{{ route('user.borrowed', ['status' => 'all']) }}"
                    class="px-5 py-3 text-sm font-medium transition-colors duration-300
               {{ request('status') == 'all' || !request('status') ? 'bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-white text-gray-700 hover:bg-gray-100 dark:bg-gray-700  dark:hover:bg-gray-600' }}">
                    All
                </a>
                <a href="{{ route('user.borrowed', ['status' => 'pending']) }}"
                    class="px-5 py-3 text-sm font-medium transition-colors duration-300
               {{ request('status') == 'pending' ? 'bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-white text-gray-700 hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600' }}">
                    Pending
                </a>
                <a href="{{ route('user.borrowed', ['status' => 'borrowed']) }}"
                    class="px-5 py-3 text-sm font-medium transition-colors duration-300
               {{ request('status') == 'borrowed' ? 'bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-white text-gray-700 hover:bg-gray-100 dark:bg-gray-700  dark:hover:bg-gray-600' }}">
                    Borrowed
                </a>
                <a href="{{ route('user.borrowed', ['status' => 'returned']) }}"
                    class="px-5 py-3 text-sm font-medium transition-colors duration-300
               {{ request('status') == 'returned' ? 'bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-white text-gray-700 hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600' }}">
                    Returned
                </a>
                <a href="{{ route('user.borrowed', ['status' => 'late']) }}"
                    class="px-5 py-3 ... {{ request('status') == 'late' ? 'bg-red-600 ...' : '...' }}">
                    Overdue
                </a>
            </div>
        </div>

        @if ($borrowedBooks->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($borrowedBooks as $borrowed)
                    <div
                        class="flex flex-col bg-white rounded-xl overflow-hidden shadow-lg transform transition-transform duration-300 hover:scale-105 hover:shadow-2xl dark:bg-gray-700">
                        {{-- This section handles the book cover image with a placeholder fallback --}}
                        <div class="h-64 overflow-hidden">
                            <img src="{{ asset('storage/' . $borrowed->book->image) }}" class="w-full h-full object-cover"
                                alt="Book Cover for {{ $borrowed->book->title }}"
                                onerror="this.onerror=null;this.src='https://placehold.co/400x600/f1f5f9/cccccc?text=Book+Cover';">
                        </div>

                        <div class="p-6 flex flex-col flex-grow">
                            <h5 class="text-xl font-bold text-gray-900  mb-2">{{ $borrowed->book->title }}</h5>
                            <p class="text-gray-500 dark:text-gray-500 text-sm mb-4">{{ $borrowed->book->author }}</p>

                            <div class="mt-auto pt-4 border-t border-gray-200 dark:border-gray-600 space-y-2 text-sm">
                                <p class="flex justify-between items-center text-gray-700 dark:text-gray-500">
                                    <span>Status:</span>
                                    {{-- Change the condition here --}}
                                    <span
                                        class="font-bold text-base {{ $borrowed->status === 'late' ? 'text-red-500' : 'text-green-600' }} dark:text-green-400">
                                        {{-- Display "Overdue" if the status is 'late' --}}
                                        @if ($borrowed->status === 'late')
                                            Overdue
                                        @else
                                            {{ ucfirst($borrowed->status) }}
                                        @endif
                                    </span>
                                </p>
                                <p class="flex justify-between items-center text-gray-700 dark:text-gray-500">
                                    <span>Borrowed on:</span>
                                    <span class="font-bold text-base">
                                        {{ \Carbon\Carbon::parse($borrowed->borrow_date)->format('M d, Y') }}
                                    </span>
                                </p>
                                <p class="flex justify-between items-center text-gray-700 dark:text-gray-500">
                                    <span>Due Date:</span>
                                    <span class="font-bold text-base">
                                        {{ \Carbon\Carbon::parse($borrowed->due_date)->format('M d, Y') }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded-lg mt-12 text-center"
                role="alert">
                <div class="flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="font-medium">You haven't borrowed any books yet.</p>
                </div>
            </div>
        @endif
    </div>
@endsection
