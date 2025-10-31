@extends('layouts.appuser')

@section('title', 'Book Catalog')

@section('content')
    <div class="container py-5">
        @if (session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger text-center">{{ session('error') }}</div>
        @endif

        <h1 class="mb-4 text-center">Available Books</h1>

        <!-- Search Form -->
    <div class="row mb-4">
    <div class="col-md-8 mx-auto">
        <form action="{{ route('user.books.index') }}" method="GET" class="d-flex align-items-center">
            <div class="input-group flex-grow-1 me-2">
                <input type="text" name="search" class="form-control rounded-pill pe-5"
                    placeholder="Search by title, author, or category..." value="{{ request('search') }}">
                <span class="input-group-text bg-transparent border-0 rounded-pill ms-n5" style="z-index: 10;">
                    <button type="submit" class="btn btn-sm btn-link p-0 text-muted">
                        <i class="bi bi-search"></i>
                    </button>
                </span>
            </div>
            <div class="flex-shrink-0">
                <select name="category" id="category-select" class="form-select rounded-pill">
                    <option value="">All Categories</option>
                    {{-- Loop through the categories passed from the controller.
                         $categoryId is the key (the ID) and $categoryName is the value (the name). --}}
                    @foreach ($categories as $categoryId => $categoryName)
                        <option value="{{ $categoryId }}" {{ request('category') == $categoryId ? 'selected' : '' }}>
                            {{-- Display the category name to the user --}}
                            {{ $categoryName }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('category-select');

        if (categorySelect) {
            categorySelect.addEventListener('change', function() {
                this.closest('form').submit();
            });
        }

        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.display = 'none';
            }, 10000);
        });
    });
</script>
@endpush
        <style>
            .book-card-img-container {
                height: 250px;
                /* You can adjust this value as needed */
                overflow: hidden;
            }

            .book-card-img {
                height: 100%;
                width: 100%;
                object-fit: cover;
            }
        </style>
        @if ($books->count())
            <div class="row row-cols-1 row-cols-md-4 g-4">
                @foreach ($books as $book)
                    <div class="col">
                        <div class="card book-card h-100">
                            <div class="book-card-img-container">
                                <img src="{{ asset('storage/' . $book->image) }}" class="card-img-top book-card-img"
                                    alt="Book Cover for {{ $book->title }}">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $book->title }}</h5>
                                <p class="card-text text-muted">{{ $book->author }}</p>
                                <p class="card-text text-muted">{{ $book->category->name }}</p>
                                <div class="mt-auto">
                                    <a href="{{ route('user.books.show', $book) }}"
                                        class="btn btn-primary rounded-pill">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $books->links() }}
            </div>
        @else
            <div class="alert alert-info text-center mt-5" role="alert">
                <i class="bi bi-info-circle me-2"></i> No books found that match your search.
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Find all alert elements on the page
            const alerts = document.querySelectorAll('.alert');

            // Loop through each alert and set a timeout to hide it
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 10000); // 10000 milliseconds = 10 seconds
            });
        });
    </script>
@endpush
