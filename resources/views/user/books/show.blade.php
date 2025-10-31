@extends('layouts.appuser')

@section('title', $book->title)

@section('content')
<div class="container py-5">
    {{-- Display success or error messages --}}
    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif

    <div class="row">
        <!-- Book Cover Column -->
        <div class="col-md-4 text-center">
            <img src="{{ asset('storage/' . $book->image) }}" class="img-fluid rounded-lg shadow-sm" alt="Book Cover for {{ $book->title }}">
        </div>
        <!-- Book Details Column -->
        <div class="col-md-8">
            <h1 class="mb-3">{{ $book->title }}</h1>
            <p class="lead text-muted">by {{ $book->author }}</p>
            <hr>
            <dl class="row">
                <dt class="col-sm-4">Category</dt>
                <dd class="col-sm-8">{{ $book->category->name }}</dd>

                 <dt class="col-sm-4">Shelf Location</dt>
        <dd class="col-sm-8">{{ $book->shelf->shelf_number }},{{ $book->shelf->row_number }},{{ $book->shelf->sub_col_number }}</dd>

                <dt class="col-sm-4">ISBN</dt>
                <dd class="col-sm-8">{{ $book->isbn }}</dd>

                <dt class="col-sm-4">Availability</dt>
                <dd class="col-sm-8">
                    @if($book->available_copies > 0)
                        <span class="badge bg-success">Available ({{ $book->available_copies }} copies)</span>
                    @else
                        <span class="badge bg-danger">Not Available</span>
                    @endif
                </dd>
            </dl>
            <div class="mt-4">
                {{-- This form now directly borrows the book --}}
                <form action="{{ route('user.book.borrow') }}" method="POST">
                    @csrf
                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4" {{ $book->available_copies == 0 ? 'disabled' : '' }}>
                        Borrow this Book
                    </button>
                </form>
            </div>
            <p class="mt-4">
                <!-- Add a placeholder for a book description -->
                {{ $book->description ?? 'No description available for this book.' }}
            </p>
        </div>
    </div>
    <div class="mt-5 text-center">
        <a href="{{ route('user.books.index') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="bi bi-arrow-left me-2"></i> Back to All Books
        </a>
    </div>
</div>
@endsection
