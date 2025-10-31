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
        max-width: 900px;
        margin: 2rem auto;
        overflow-y: auto; /* Allow vertical scrolling if content overflows */
        max-height: calc(100vh - 4rem); /* Set a maximum height to keep it contained */

        /* Hide the scrollbar but keep functionality */
        -ms-overflow-style: none;  /* for Internet Explorer and Edge */
        scrollbar-width: none;     /* for Firefox */
    }

    /* For WebKit browsers (Chrome, Safari, etc.) */
    .modern-container::-webkit-scrollbar {
        display: none;
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

    /* Form input and select styling */
    .modern-input, .modern-select {
        border: 1px solid #ced4da;
        border-radius: 8px;
        padding: 12px;
        font-size: 1rem;
        font-family: 'Inter', sans-serif;
        background-color: #f1f3f5;
        transition: all 0.3s ease;
    }

    .modern-input:focus, .modern-select:focus {
        border-color: #6a11cb;
        background-color: #ffffff;
        box-shadow: 0 0 0 3px rgba(106, 17, 203, 0.2);
    }

    /* Primary button with a vibrant gradient */
    .modern-btn {
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s ease;
        padding: 12px 20px;
        text-transform: capitalize;
        background: linear-gradient(90deg, #6a11cb, #2575fc);
        color: #ffffff;
        border: none;
        box-shadow: 0 4px 10px rgba(106, 17, 203, 0.2);
    }

    .modern-btn:hover {
        transform: translateY(-2px);
        box-shadow: 6px 15px rgba(106, 17, 203, 0.3);
    }

    /* Alert messages for errors */
    .modern-alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        border-radius: 8px;
        padding: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    /* Smaller image preview size */
    .small-preview-image {
        width: 100px;
        height: 90px;
        object-fit: cover;
    }
</style>

<div class="p-4">
    <div class="w-full modern-container">
        <div class="flex justify-between items-center mb-6">
            <div class="modern-header">
                <h2 class="text-3xl">📖 Add New Book</h2>
                <div class="modern-gradient-line"></div>
            </div>
        </div>

        @if ($errors->any())
        <div class="mb-4 modern-alert-danger">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf

            <div>
                <label class="block mb-2 font-medium" for="title">📖 Book Title</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}"
                  maxlength="50"   class="w-full modern-input" required />
            </div>

            <div >
            <div id="image-preview-container" class="h-9 rounded-lg  hidden flex items-center justify-center">
    <img id="preview-image" src="https://placehold.co/120x160/E9ECEF/495057?text=No+Image" alt="Book Cover Preview" class="small-preview-image rounded-lg shadow-md border border-gray-300" />
</div>
            </div>

            <div>
                <label class="block mb-2 font-medium" for="author">✍️ Author</label>
                <input type="text" name="author" id="author" value="{{ old('author') }}"
                  maxlength="50"   class="w-full modern-input" required />
            </div>

            <div>
                <label class="block mb-2 font-medium" for="image">Book Cover Image</label>
                <input type="file" name="image" id="image"
                    class="w-full modern-input" accept="image/*" />
            </div>

           <div>
    <label class="block mb-2 font-medium" for="category_id">📂 Category</label>
    <select name="category_id" id="category_id" class="w-full modern-select" required>
        <option value="">-- Select Category --</option>
        @foreach($categories as $category)
        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
            {{ $category->name }}
        </option>
        @endforeach
    </select>
</div>

            <!-- New Shelf Selection -->
            <div>
                <label class="block mb-2 font-medium" for="shelf_number_select">📍Shelf Number</label>
                <select id="shelf_number_select" class="w-full modern-select" required>
                    <option value="">-- Select Shelf Number --</option>
                    @foreach($shelves->unique('shelf_number') as $shelf)
                    <option value="{{ $shelf->shelf_number }}">
                        {{ $shelf->shelf_number }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-2 font-medium" for="row_number_select">📍Row Number</label>
                <select id="row_number_select" class="w-full modern-select" required disabled>
                    <option value="">-- Select Row Number --</option>
                </select>
            </div>

            <div>
                <label class="block mb-2 font-medium" for="sub_col_number_select">📍Sub-column Number</label>
                <select id="sub_col_number_select" class="w-full modern-select" required disabled>
                    <option value="">-- Select Sub-column Number --</option>
                </select>
            </div>

            <input type="hidden" name="shelf_id" id="shelf_id" value="">

            <div>
                <label class="block mb-2 font-medium" for="isbn">🏷️ ISBN</label>
                <input type="text" name="isbn" id="isbn" value="{{ $newIsbn }}"
                  maxlength="9"   class="w-full modern-input" readonly />
            </div>

            <div>
                <label class="block mb-2 font-medium" for="total_copies">🔢 Total Copies</label>
                <input type="number" name="total_copies" id="total_copies" value="{{ old('total_copies') }}" min="1"
                    class="w-full modern-input" required />
            </div>

            <div class="md:col-span-2">
                <button type="submit" class="w-full modern-btn">
                    ➕ Add Book
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#category_id').select2();
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('image');
        const imagePreview = document.getElementById('preview-image');
        const imagePreviewContainer = document.getElementById('image-preview-container');

        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    // Show the preview container when an image is selected
                    imagePreviewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                // Hide the container and reset the image when no file is selected
                imagePreview.src = "https://placehold.co/120x160/E9ECEF/495057?text=No+Image";
                imagePreviewContainer.classList.add('hidden');
            }
        });

        // Shelf Selection Logic
        const allShelves = @json($shelves);
        const shelfNumberSelect = document.getElementById('shelf_number_select');
        const rowNumberSelect = document.getElementById('row_number_select');
        const subColNumberSelect = document.getElementById('sub_col_number_select');
        const shelfIdInput = document.getElementById('shelf_id');

        // Initial state
        rowNumberSelect.disabled = true;
        subColNumberSelect.disabled = true;
        shelfIdInput.value = '';

        shelfNumberSelect.addEventListener('change', function() {
            const selectedShelfNumber = this.value;
            // Reset and enable the next dropdown
            rowNumberSelect.innerHTML = '<option value="">-- Select Row Number --</option>';
            subColNumberSelect.innerHTML = '<option value="">-- Select Sub-column Number --</option>';
            subColNumberSelect.disabled = true;
            shelfIdInput.value = '';

            if (selectedShelfNumber) {
                const filteredByShelf = allShelves.filter(shelf => shelf.shelf_number === selectedShelfNumber);
                const uniqueRows = [...new Set(filteredByShelf.map(shelf => shelf.row_number))];

                uniqueRows.forEach(row => {
                    const option = document.createElement('option');
                    option.value = row;
                    option.textContent = row;
                    rowNumberSelect.appendChild(option);
                });
                rowNumberSelect.disabled = false;
            } else {
                rowNumberSelect.disabled = true;
                subColNumberSelect.disabled = true;
            }
        });

        rowNumberSelect.addEventListener('change', function() {
            const selectedShelfNumber = shelfNumberSelect.value;
            const selectedRowNumber = this.value;
            // Reset and enable the next dropdown
            subColNumberSelect.innerHTML = '<option value="">-- Select Sub-column Number --</option>';
            shelfIdInput.value = '';

            if (selectedRowNumber) {
                const filteredByRow = allShelves.filter(shelf =>
                    shelf.shelf_number === selectedShelfNumber &&
                    shelf.row_number === selectedRowNumber
                );
                const uniqueSubCols = [...new Set(filteredByRow.map(shelf => shelf.sub_col_number))];

                uniqueSubCols.forEach(subCol => {
                    const option = document.createElement('option');
                    option.value = subCol;
                    option.textContent = subCol;
                    subColNumberSelect.appendChild(option);
                });
                subColNumberSelect.disabled = false;
            } else {
                subColNumberSelect.disabled = true;
            }
        });

        subColNumberSelect.addEventListener('change', function() {
            const selectedShelfNumber = shelfNumberSelect.value;
            const selectedRowNumber = rowNumberSelect.value;
            const selectedSubColNumber = this.value;

            // Find the correct shelf_id and set the hidden input
            if (selectedSubColNumber) {
                const finalShelf = allShelves.find(shelf =>
                    shelf.shelf_number === selectedShelfNumber &&
                    shelf.row_number === selectedRowNumber &&
                    shelf.sub_col_number === selectedSubColNumber
                );
                if (finalShelf) {
                    shelfIdInput.value = finalShelf.id;
                }
            } else {
                shelfIdInput.value = '';
            }
        });
    });
</script>
@endsection
