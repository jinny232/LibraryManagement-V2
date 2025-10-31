@extends('layouts.app')

@section('content')
    {{-- A better practice would be to move the custom CSS below into a separate file and include it. --}}
    {{-- For demonstration, here's a cleaner version of the custom styles. --}}
    <style>
        /* Hides both vertical and horizontal scrollbars on the entire page while keeping scrolling functionality */
        html,
        body {
            height: 100%;
            -ms-overflow-style: none;
            /* For Internet Explorer and Edge */
            scrollbar-width: none;
            /* For Firefox */
            color: black;
        }

        html::-webkit-scrollbar,
        body::-webkit-scrollbar {
            display: none;
            /* For Chrome, Safari, and Opera */
        }

        .modern-container {
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 25px;
            padding: 2.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .modern-gradient-line {
            height: 3px;
            width: 100%;
            background: linear-gradient(90deg, #6a11cb, #2575fc);
            border-radius: 2px;
        }

        /* Forces table header and cell text to stay on one line */
        .modern-table-wrapper th,
        .modern-table-wrapper td {
            white-space: nowrap;
        }

        /* Hides the scrollbar specifically on the table wrapper */
        .modern-table-wrapper::-webkit-scrollbar {
            display: none;
            /* For Chrome, Safari, and Opera */
        }

        .modern-table-wrapper {
            -ms-overflow-style: none;
            /* For Internet Explorer and Edge */
            scrollbar-width: none;
            /* For Firefox */
        }


        .pagination-link {
            @apply flex items-center justify-center w-10 h-10 border rounded-full transition-colors duration-200;
        }

        .pagination-link.active {
            @apply bg-blue-600 text-white border-blue-600;
        }
    </style>

    <div class="container modern-container ">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <div class="modern-header mb-4 md:mb-0">
                <h2 class="text-3xl font-bold text-gray-800">📚 Book Management</h2>
                <div class="modern-gradient-line"></div>
            </div>
            <a href="{{ route('admin.books.create') }}"
                class="px-5 py-2 rounded-lg text-white font-semibold transition-transform duration-300 transform hover:-translate-y-1 shadow-lg"
                style="background: linear-gradient(90deg, #6a11cb, #2575fc);">
 <img src="{{asset('assets/img/create.png')}} " style="width:30px;height:30px">
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 rounded-lg text-green-700 bg-green-100 border border-green-200 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.books.index') }}" method="GET"
            class="flex flex-col md:flex-row gap-4 items-center mb-6 p-4 bg-gray-100 rounded-lg shadow-inner">
            <input type="text" name="search" placeholder="Search by title, author, or ISBN..."
                 maxlength="100"  value="{{ request('search') }}"
                class="w-full md:w-auto px-3 py-2 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 flex-grow">

            <select name="category"
                class="w-full md:w-auto px-3 py-2 border border-gray-300 rounded-lg text-black-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Categories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                         {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <select name="major"
                class="w-full md:w-auto px-3 py-2 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Majors</option>
                @foreach ($majors as $major)
                    <option value="{{ $major }}" {{ request('major') == $major ? 'selected' : '' }}>
                        {{ $major }}
                    </option>
                @endforeach
            </select>

            <select name="year"
                class="w-full md:w-auto px-3 py-2 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Years</option>
                @foreach ($years as $year)
                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>

            <div class="flex gap-2 w-full md:w-auto">
                <button type="submit"
                    class="py-2 bg-blue-600 text-white font-semibold rounded-lg transition-colors duration-200 hover:bg-blue-700">
                    <img src="{{asset('assets/img/filter.png')}}" style="width:30px;height:30px" alt="Filter">
                </button>
                <a href="{{ route('admin.books.index') }}"
                    class="py-2 text-center bg-red-200 text-gray-800 font-semibold rounded-lg transition-colors duration-200 hover:bg-gray-300">
                                        <img src="{{asset('assets/img/delete.png')}}" style="width:30px;height:30px" alt="Clear">

                </a>
            </div>
        </form>

        {{-- Charts Section --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-gray-50 p-6 rounded-lg shadow-inner">
                <h3 class="text-xl font-semibold mb-4 text-center text-gray-800">Books Borrowed per Major</h3>
                <canvas id="booksPerMajorChart"></canvas>
            </div>
            <div class="bg-gray-50 p-6 rounded-lg shadow-inner">
                <h3 class="text-xl font-semibold mb-4 text-center text-gray-800">Books Borrowed per Year</h3>
                <canvas id="booksPerYearChart"></canvas>
            </div>
        </div>
        @if ($books->count())
            <div class="overflow-x-auto modern-table-wrapper rounded-lg border border-gray-200 shadow-sm">
                <table class="w-full border-collapse font-sans text-sm text-gray-600">
                    <thead class="bg-gray-100 sticky top-0 z-10">
                        <tr>
                            {{-- Changed from ID to No. to represent the list order --}}
                                               <th class="py-4 px-6 text-left font-bold text-gray-600">🔢 No.</th>
                        <th class="py-4 px-6 text-left font-bold text-gray-600">📖 Title</th>
                        <th class="py-4 px-6 text-left font-bold text-gray-600">✍️ Author</th>
                        <th class="py-4 px-6 text-left font-bold text-gray-600">📂 Category</th>
                        <th class="py-4 px-6 text-left font-bold text-gray-600">🏷️ ISBN</th>
                        <th class="py-4 px-6 text-center font-bold text-gray-600">✅ Available</th>
                        <th class="py-4 px-6 text-center font-bold text-gray-600">📚 Total</th>
                        <th class="py-4 px-6 text-center font-bold text-gray-600">⚙️ Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($books as $book)
                            <tr class="transition-colors duration-200 hover:bg-gray-50">
                                {{-- This code correctly calculates the list number for each paginated page --}}
                                <td class="py-4 px-6 border-b border-gray-200">
                                    {{ ($books->currentPage() - 1) * $books->perPage() + $loop->iteration }}
                                </td>
                                <td class="py-4 px-6 border-b border-gray-200">{{ $book->title }}</td>
                                <td class="py-4 px-6 border-b border-gray-200">{{ $book->author }}</td>
                                <td class="py-4 px-6 border-b border-gray-200">{{ $book->category->name  }}</td>
                                <td class="py-4 px-6 border-b border-gray-200">{{ $book->isbn }}</td>
                                <td class="py-4 px-6 border-b border-gray-200 text-center">{{ $book->available_copies }}
                                </td>
                                <td class="py-4 px-6 border-b border-gray-200 text-center">{{ $book->total_copies }}</td>
                                <td class="py-4 px-6 border-b border-gray-200 text-center whitespace-nowrap">
                                    <a href="{{ route('admin.books.show', $book) }}"
                                        class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-300 transition-colors duration-200">
                                         👀
                                    </a>
                                    <a href="{{ route('admin.books.edit', $book) }}"
                                        class="px-3 py-1 bg-blue-100 text-blue-700 rounded-md text-sm font-medium hover:bg-blue-200 transition-colors duration-200">
                                        ✏️
                                    </a>
                                    <form action="{{ route('admin.books.destroy', $book) }}" method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Are you sure you want to delete this book?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-1 bg-red-100 text-red-700 rounded-md text-sm font-medium hover:bg-red-200 transition-colors duration-200">
                                           ❌
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-6 text-center text-blue-700 bg-blue-100 border border-blue-200 rounded-lg shadow-sm">
                No books found.
            </div>
        @endif

        {{ $books->links() }}
    </div>

    {{-- Chart.js and custom script section --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const booksPerMajorData = @json($booksPerMajor);
        const booksPerYearData = @json($booksPerYear);

        const booksPerMajorCtx = document.getElementById('booksPerMajorChart').getContext('2d');
        new Chart(booksPerMajorCtx, {
            type: 'bar',
            data: {
                labels: booksPerMajorData.map(item => item.major),
                datasets: [{
                    label: 'Books Borrowed',
                    data: booksPerMajorData.map(item => item.book_count),
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const booksPerYearCtx = document.getElementById('booksPerYearChart').getContext('2d');
        new Chart(booksPerYearCtx, {
            type: 'bar',
            data: {
                labels: booksPerYearData.map(item => item.year),
                datasets: [{
                    label: 'Books Borrowed',
                    data: booksPerYearData.map(item => item.book_count),
                    backgroundColor: 'rgba(16, 185, 129, 0.5)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
