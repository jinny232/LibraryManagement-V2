@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    body {
        background-color: #f8f9fa;
        color: #212529;
        font-family: 'Inter', sans-serif;
        min-height: 100vh;
    }

    .modern-container {
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 25px;
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        max-width: 1200px;
        margin: 2rem auto;
        overflow-y: auto;
        max-height: calc(100vh - 4rem);
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .modern-container::-webkit-scrollbar {
        display: none;
    }

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
        box-shadow: 0 6px 15px rgba(106, 17, 203, 0.3);
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
        border-radius: 8px;
        padding: 12px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-transform: capitalize;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(108, 117, 125, 0.2);
    }

    .modern-alert {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 8px;
        border: 1px solid transparent;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border-color: #c3e6cb;
    }

    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #e9ecef;
        background-color: #fdfdfe;
    }

    .modern-table thead th {
        background-color: #6a11cb;
        color: #ffffff;
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        text-align: left;
        padding: 1rem;
        white-space: nowrap;
    }

    .modern-table tbody tr {
        background-color: #ffffff;
        transition: background-color 0.3s ease;
        border-bottom: 1px solid #e9ecef;
    }

    .modern-table tbody tr:hover {
        background-color: #f1f3f5;
    }

    .modern-table tbody td {
        padding: 1rem;
        font-family: 'Inter', sans-serif;
        color: #495057;
        white-space: nowrap;
    }

    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }

    /* Modal styles */
    .modal {
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.4);
        display: none;
        align-items: center;
        justify-content: center;
    }
    .modal-content {
        background-color: #fff;
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
        max-width: 500px;
        width: 90%;
        position: relative;
    }
    .close-btn {
        position: absolute;
        top: 1rem;
        right: 1rem;
        font-size: 1.5rem;
        font-weight: bold;
        cursor: pointer;
        color: #aaa;
    }
</style>

<div class="container mt-4 modern-container">

    @if(session('success'))
        <div class="modern-alert alert-success">
            ✅ {{ session('success') }}
        </div>
    @endif
    {{-- Category Management --}}
    <div class="modern-header mb-6">
        <h2 class="text-3xl">🗂️ Category Management</h2>
        <div class="modern-gradient-line"></div>
    </div>
    @if(session('category_success'))
        <div class="modern-alert alert-success">
            ✅ {{ session('category_success') }}
        </div>
    @endif
    @if($errors->has('category_name') || $errors->has('name'))
        <div class="bg-red-200 text-red-800 p-3 rounded mb-4">
            @foreach ($errors->get('category_name') as $error)
                <p>{{ $error }}</p>
            @endforeach
            @foreach ($errors->get('name') as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4 max-w-lg mb-8">
        @csrf
        <div>
            <label class="block font-medium mb-1" for="category_name">➕ Add New Category</label>
            <div class="flex items-center gap-4">
                <input type="text" id="category_name" name="name" placeholder="Enter category name" required
                       class="flex-1 border rounded px-3 py-2 @error('name') border-red-500 @enderror" />
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    ➕ Add
                </button>
            </div>
        </div>
    </form>

    {{-- Existing Categories Table --}}
    <div class="mt-8 mb-8">
        <h3 class="text-xl font-semibold mb-4">Existing Categories</h3>
        @if($categories->count())
            <div class="overflow-x-auto rounded-lg hide-scrollbar">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th class="py-3 px-4">ID</th>
                            <th class="py-3 px-4">Name</th>
                            <th class="py-3 px-4">Book Count</th>
                            <th class="py-3 px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr class="hover:bg-gray-100">
                                <td class="py-3 px-4">{{ $category->id }}</td>
                                <td class="py-3 px-4">{{ $category->name }}</td>
                                <td class="py-3 px-4">{{ $category->books_count  > 0 ? $category->books_count : '-'}}</td>
                                <td class="py-3 px-4">
                                    <button onclick="openCategoryModal({{ $category->id }}, '{{ $category->name }}')" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 mr-2">
                                        ✏️
                                    </button>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                            🗑️
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $categories->links() }}
            </div>
        @else
            <div class="modern-alert bg-blue-100 text-blue-800">No categories found.</div>
        @endif
    </div>

    <hr class="my-8">

    {{-- Shelf Management --}}
    <div class="modern-header mb-6">
        <h2 class="text-3xl">📚 Shelf Management</h2>
        <div class="modern-gradient-line"></div>
    </div>
    @if(session('shelf_success'))
        <div class="modern-alert alert-success">
            ✅ {{ session('shelf_success') }}
        </div>
    @endif
    @if($errors->has('shelf_number') || $errors->has('row_number') || $errors->has('sub_col_number'))
        <div class="bg-red-200 text-red-800 p-3 rounded mb-4">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    <form action="{{ route('admin.shelves.store') }}" method="POST" class="space-y-4 max-w-lg">
        @csrf
        <div>
            <label class="block font-medium mb-1" for="shelf_number">🏷️ Shelf Number</label>
            <input type="text" id="shelf_number" name="shelf_number" placeholder="e.g., A" required
                   class="w-full border rounded px-3 py-2 @error('shelf_number') border-red-500 @enderror" />
        </div>
        <div>
            <label class="block font-medium mb-1" for="row_number">➡️ Row Number</label>
            <input type="text" id="row_number" name="row_number" placeholder="e.g., 1" required
                   class="w-full border rounded px-3 py-2 @error('row_number') border-red-500 @enderror" />
        </div>
        <div>
            <label class="block font-medium mb-1" for="sub_col_number">🗃️ Sub-Column Number</label>
            <input type="text" id="sub_col_number" name="sub_col_number" placeholder="e.g., a" required
                   class="w-full border rounded px-3 py-2 @error('sub_col_number') border-red-500 @enderror" />
        </div>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            ➕ Add Shelf
        </button>
    </form>

    {{-- Existing Shelves Table --}}
    <div class="mt-8">
        <h3 class="text-xl font-semibold mb-4">Existing Shelves</h3>
        @if($shelves->count())
            <div class="overflow-x-auto rounded-lg hide-scrollbar">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th class="py-3 px-4">ID</th>
                            <th class="py-3 px-4">Shelf Number</th>
                            <th class="py-3 px-4">Row Number</th>
                            <th class="py-3 px-4">Sub-Column Number</th>
                            <th class="py-3 px-4">Book Count</th>
                            <th class="py-3 px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shelves as $shelf)
                            <tr class="hover:bg-gray-100">
                                <td class="py-3 px-4">{{ $shelf->id }}</td>
                                <td class="py-3 px-4">{{ $shelf->shelf_number }}</td>
                                <td class="py-3 px-4">{{ $shelf->row_number }}</td>
                                <td class="py-3 px-4">{{ $shelf->sub_col_number }}</td>
                                <td class="py-3 px-4">{{ $shelf->books_count > 0 ? $shelf->books_count : '-' }}</td>
                                <td class="py-3 px-4">
                                    <button onclick="openShelfModal({{ $shelf->id }}, '{{ $shelf->shelf_number }}', '{{ $shelf->row_number }}', '{{ $shelf->sub_col_number }}')" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 mr-2">
                                        ✏️
                                    </button>
                                    <form action="{{ route('admin.shelves.destroy', $shelf->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this shelf?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                            🗑️
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $shelves->links() }}
            </div>
        @else
            <div class="modern-alert bg-blue-100 text-blue-800">No shelves found.</div>
        @endif
    </div>
</div>

<div id="categoryEditModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal('categoryEditModal')">&times;</span>
        <h3 class="text-2xl font-semibold mb-4">Edit Category</h3>
        <form id="categoryEditForm" action="" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="editCategoryId">
            <label class="block font-medium mb-1" for="editCategoryName">Category Name</label>
            <input type="text" id="editCategoryName" name="name" required class="w-full border rounded px-3 py-2 mb-4" />
            <button type="submit" class="modern-btn w-full">Save Changes</button>
        </form>
    </div>
</div>

<div id="shelfEditModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal('shelfEditModal')">&times;</span>
        <h3 class="text-2xl font-semibold mb-4">Edit Shelf</h3>
        <form id="shelfEditForm" action="" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="editShelfId">
            <label class="block font-medium mb-1" for="editShelfNumber">Shelf Number</label>
            <input type="text" id="editShelfNumber" name="shelf_number" required class="w-full border rounded px-3 py-2 mb-4" />
            <label class="block font-medium mb-1" for="editRowNumber">Row Number</label>
            <input type="text" id="editRowNumber" name="row_number" required class="w-full border rounded px-3 py-2 mb-4" />
            <label class="block font-medium mb-1" for="editSubColNumber">Sub-Column Number</label>
            <input type="text" id="editSubColNumber" name="sub_col_number" required class="w-full border rounded px-3 py-2 mb-4" />
            <button type="submit" class="modern-btn w-full">Save Changes</button>
        </form>
    </div>
</div>

<script>
    function openCategoryModal(id, name) {
        document.getElementById('categoryEditForm').action = `{{ url('admin/categories') }}/${id}`;
        document.getElementById('editCategoryId').value = id;
        document.getElementById('editCategoryName').value = name;
        document.getElementById('categoryEditModal').style.display = 'flex';
    }

    function openShelfModal(id, shelfNumber, rowNumber, subColNumber) {
        document.getElementById('shelfEditForm').action = `{{ url('admin/shelves') }}/${id}`;
        document.getElementById('editShelfId').value = id;
        document.getElementById('editShelfNumber').value = shelfNumber;
        document.getElementById('editRowNumber').value = rowNumber;
        document.getElementById('editSubColNumber').value = subColNumber;
        document.getElementById('shelfEditModal').style.display = 'flex';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }
</script>
@endsection
