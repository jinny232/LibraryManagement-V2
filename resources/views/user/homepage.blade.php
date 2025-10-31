@extends('layouts.appuser')

@section('title', 'User Homepage')

{{-- Remove the duplicate script link here --}}

@section('content')
<style>
    .book-card-img-container {
        height: 250px; /* You can adjust this value as needed */
        overflow: hidden;
    }

    .book-card-img {
        height: 100%;
        width: 100%;
        object-fit: cover;
    }
</style>
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="display-3 fw-bold mb-3">Welcome, {{ $memberName ?? 'BookShop User' }}</h1>
            <a href="{{ route('user.books.index') }}" class="btn btn-primary btn-lg rounded-pill px-4">Explore Books</a>
        </div>
    </section>

    <section class="my-5">
        <h2 class="text-center mb-4">Most Borrowed Books</h2>

        <div id="mostBorrowedCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                {{-- Loop through the most borrowed books and create carousel items --}}
                @foreach ($mostBorrowedBooks->chunk(3) as $key => $chunk)
                    <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                        <div class="row row-cols-1 row-cols-md-3 g-4">
                            @foreach ($chunk as $book)
                                <div class="col">
                                    <div class="card book-card h-100">
                                        {{-- Use the book's image from the database --}}
                                   <div class="book-card-img-container">
                                                <img src="{{ asset('storage/' . $book->image) }}" class="card-img-top book-card-img" alt="Book Cover for {{ $book->title }}">
                                            </div>
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $book->title }}</h5>
                                            <p class="card-text text-muted">{{ $book->author }}</p>
                                            <p class="card-text text-muted">{{ $book->category->name }}</p>
                                            <a href="{{ route('user.books.show', $book) }}" class="btn btn-primary rounded-pill">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#mostBorrowedCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#mostBorrowedCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

 {{-- The most borrowed books section is fine, no changes needed here. --}}

    {{-- The Books by Category section needs to be corrected --}}
    @foreach ($booksByCategory as $categoryName => $books)
        <section class="my-5">
            {{-- Use the categoryName variable directly without decoding it --}}
            <h2 class="text-center mb-4">{{ ucfirst($categoryName) }} Books</h2>

            {{-- Use the categoryName to create a unique and valid ID for the carousel --}}
            <div id="categoryCarousel-{{ Str::slug($categoryName) }}" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    {{-- Loop through the books in the current category and create carousel items --}}
                    @foreach ($books->chunk(3) as $key => $chunk)
                        <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                            <div class="row row-cols-1 row-cols-md-3 g-4">
                                @foreach ($chunk as $book)
                                    <div class="col">
                                        <div class="card book-card h-100">
                                            {{-- Image Container to fix the size --}}
                                            <div class="book-card-img-container">
                                                <img src="{{ asset('storage/' . $book->image) }}" class="card-img-top book-card-img" alt="Book Cover for {{ $book->title }}">
                                            </div>
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $book->title }}</h5>
                                                <p class="card-text text-muted">{{ $book->author }}</p>
                                                <a href="{{ route('user.books.show', $book) }}" class="btn btn-primary rounded-pill">View Details</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#categoryCarousel-{{ Str::slug($categoryName) }}" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#categoryCarousel-{{ Str::slug($categoryName) }}" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </section>
    @endforeach
@endsection
