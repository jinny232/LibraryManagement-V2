@extends('layouts.appuser')

@section('title', 'My Bookings')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-center">My Bookings</h1>

    @if($requestedBooks->count())
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach($requestedBooks as $request)
                <div class="col">
                    <div class="card book-card h-100 shadow-sm">
                        <img src="{{ $request->book->cover ?? 'https://placehold.co/400x600/f1f5f9/cccccc?text=Book+Cover' }}" class="card-img-top book-card-img" alt="Book Cover for {{ $request->book->title }}">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $request->book->title }}</h5>
                            <p class="card-text text-muted">by {{ $request->book->author }}</p>

                            <hr class="my-2">

                            <div class="mt-auto">
                                <p class="small mb-1">Status: <span class="badge bg-warning">{{ $request->status ?? 'Pending' }}</span></p>
                                <p class="small mb-0">Requested on: <span class="fw-bold">{{ $request->created_at->format('M d, Y') }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info text-center mt-5" role="alert">
            <i class="bi bi-info-circle me-2"></i> You have no pending book requests.
        </div>
    @endif
</div>
@endsection
