@extends('layouts.app')

@section('content')
<div class="bg-gray-100 min-h-screen p-8 font-sans h-[90vh] mb-1">
    <script src="https://cdn.tailwindcss.com"></script>
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-lg flex flex-col ">
        <div class="flex justify-between items-center mb-6 flex-shrink-0">
            <div class="flex items-center space-x-4">
                {{-- Dynamic image display with ID for JavaScript preview --}}
                <img id="book-cover-preview" src="{{ asset('storage/' . $book->image) }}" alt="Book Cover" class="w-24 h-32 object-cover rounded-lg shadow-md border border-gray-300 md:w-32 md:h-40" />
                <h1 class="text-3xl font-bold text-gray-800">😺 Edit Book: {{ $book->title }}</h1>
            </div>
            <a href="{{ route('admin.books.index') }}" class="text-gray-500 hover:text-gray-700">
                ⬅️ Back to Books
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 flex-shrink-0" role="alert">
                <strong class="font-bold">⚠️ Whoops!</strong>
                <span class="block sm:inline">There were some problems with your input.</span>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form updated to handle file uploads and include scrolling for the content area --}}
        <form action="{{ route('admin.books.update', $book) }}" method="POST" enctype="multipart/form-data" class="flex flex-col h-full">
            @csrf
            @method('PUT')

            {{-- New scrollable container for the form fields --}}
            <div class="flex-1 overflow-y-auto pr-2 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title Field -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">📖 Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $book->title) }}"
                               class="text-gray-700 mt-1 block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <!-- Author Field -->
                    <div>
                        <label for="author" class="block text-sm font-medium text-gray-700 mb-1">✍️ Author</label>
                        <input type="text" name="author" id="author" value="{{ old('author', $book->author) }}"
                               class="text-gray-700 mt-1 block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <!-- ISBN Field -->
                    <div>
                        <label for="isbn" class="block text-sm font-medium text-gray-700 mb-1">🏷️ ISBN</label>
                        <input type="text" name="isbn" id="isbn" value="{{ old('isbn', $book->isbn) }}"
                               class="text-gray-700 mt-1 block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <!-- Category Field (updated to a dropdown) -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">📂 Category</label>
                        <select name="category_id" id="category_id"
                                class="text-gray-700 mt-1 block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Hidden input to store the shelf_id determined by the three new dropdowns --}}
                    <input type="hidden" name="shelf_id" id="shelf_id" value="{{ old('shelf_id', $book->shelf_id) }}">

                    {{-- Three new select dropdowns for shelf location --}}
                    <div>
                        <label for="shelf_number" class="block text-sm font-medium text-gray-700 mb-1">📍 Shelf Number</label>
                        <select id="shelf_number" class="text-gray-700 mt-1 block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @php
                                $uniqueShelfNumbers = $shelves->pluck('shelf_number')->unique();
                            @endphp
                            @foreach ($uniqueShelfNumbers as $shelfNumber)
                                <option value="{{ $shelfNumber }}">{{ $shelfNumber }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="row_number" class="block text-sm font-medium text-gray-700 mb-1">➡️ Row Number</label>
                        <select id="row_number" class="text-gray-700 mt-1 block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @php
                                $uniqueRowNumbers = $shelves->pluck('row_number')->unique();
                            @endphp
                            @foreach ($uniqueRowNumbers as $rowNumber)
                                <option value="{{ $rowNumber }}">{{ $rowNumber }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="sub_col_number" class="block text-sm font-medium text-gray-700 mb-1">⬇️ Sub Col Number</label>
                        <select id="sub_col_number" class="text-gray-700 mt-1 block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @php
                                $uniqueSubColNumbers = $shelves->pluck('sub_col_number')->unique();
                            @endphp
                            @foreach ($uniqueSubColNumbers as $subColNumber)
                                <option value="{{ $subColNumber }}">{{ $subColNumber }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Total Copies Field -->
                    <div>
                        <label for="total_copies" class="block text-sm font-medium text-gray-700 mb-1">🔢 Total Copies</label>
                        <input type="number" name="total_copies" id="total_copies" value="{{ old('total_copies', $book->total_copies) }}"
                               class="text-gray-700 mt-1 block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <!-- Available Copies Field (made read-only as it's typically calculated) -->
                    <div>
                        <label for="available_copies" class="block text-sm font-medium text-gray-700 mb-1">✅ Available Copies</label>
                        <input type="number" name="available_copies" id="available_copies" value="{{ old('available_copies', $book->available_copies) }}"
                               class="text-gray-700 mt-1 block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" disabled>
                    </div>

                    <!-- Image Upload Field -->
                    <div class="col-span-1 md:col-span-2">
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-1">🖼️ Upload New Book Cover</label>
                        <input type="file" name="image" id="image" class="mt-1 block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700
                                hover:file:bg-indigo-100">
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3 flex-shrink-0">
                <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    ✔️ Update Book
                </button>
                <a href="{{ route('admin.books.index') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    ❌ Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const imageInput = document.getElementById('image');
        const bookCoverPreview = document.getElementById('book-cover-preview');
        const shelfIdInput = document.getElementById('shelf_id');
        const shelfNumberSelect = document.getElementById('shelf_number');
        const rowNumberSelect = document.getElementById('row_number');
        const subColNumberSelect = document.getElementById('sub_col_number');

        // This array will hold the shelf data passed from the backend
        const shelvesData = @json($shelves);

        // Helper function to find a shelf ID based on the three selected values
        function findShelfId() {
            const selectedShelfNumber = shelfNumberSelect.value;
            const selectedRowNumber = rowNumberSelect.value;
            const selectedSubColNumber = subColNumberSelect.value;

            const foundShelf = shelvesData.find(shelf =>
                shelf.shelf_number == selectedShelfNumber &&
                shelf.row_number == selectedRowNumber &&
                shelf.sub_col_number == selectedSubColNumber
            );

            return foundShelf ? foundShelf.id : null;
        }

        // Function to update the hidden shelf_id field
        function updateShelfId() {
            shelfIdInput.value = findShelfId();
        }

        // Set initial dropdown values based on the current book's shelf
        const currentShelf = shelvesData.find(shelf => shelf.id == shelfIdInput.value);
        if (currentShelf) {
            shelfNumberSelect.value = currentShelf.shelf_number;
            rowNumberSelect.value = currentShelf.row_number;
            subColNumberSelect.value = currentShelf.sub_col_number;
        }

        // Event listeners to update the hidden field whenever a dropdown changes
        shelfNumberSelect.addEventListener('change', updateShelfId);
        rowNumberSelect.addEventListener('change', updateShelfId);
        subColNumberSelect.addEventListener('change', updateShelfId);

        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    bookCoverPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endsection
