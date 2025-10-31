@extends('layouts.app')

@section('title', 'Overall Borrow List')

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

    /* New CSS to hide the scrollbar specifically for the table container */
    .hide-scrollbar {
        -ms-overflow-style: none; /* IE and Edge */
        scrollbar-width: none; /* Firefox */
    }

    .hide-scrollbar::-webkit-scrollbar {
        display: none; /* Chrome, Safari, and Opera */
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
</style>

<div class="container mt-4 modern-container">
    <div class="flex justify-between items-center mb-6">
        <div class="modern-header">
            <h2 class="text-3xl">Overall Borrow-related List</h2>
            <div class="modern-gradient-line"></div>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.borrowings.pending-return') }}" class="btn-secondary">↩️Return a Book</a>
            <a href="{{ route('admin.borrowings.create') }}" class="modern-btn">➡️Borrow a Book</a>
        </div>
    </div>

    @if(session('success'))
        <div class="modern-alert alert-success">{{ session('success') }}</div>
    @endif

    @if($borrowings->count())
        {{-- Added the new class here --}}
        <div class="overflow-x-auto hide-scrollbar">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>#</th>
                    <th>👤 Member</th>
                    <th>📜 Roll No</th>
                    <th>📖 Book</th>
                    <th>🏷️ ISBN</th>
                    <th>🗓️ Borrow Date</th>
                    <th>📆 Due Date</th>
                    <th>🔄 Return Date</th>
                    <th>✅ Status</th>
                    <th>🛠️ Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($borrowings as $index => $borrowing)
                        <tr>
                            <td>{{ ($borrowings->currentPage() - 1) * $borrowings->perPage() + $index + 1 }}</td>
                            <td>{{ $borrowing->member->name }}</td>
                            <td>{{ $borrowing->member->roll_no }}</td>
                            <td>{{ $borrowing->book->title }}</td>
                            <td>{{ $borrowing->book->isbn }}</td>
                            <td>{{ $borrowing->borrow_date?->format('Y-m-d') ?? '-' }}</td>
                            <td>{{ $borrowing->due_date->format('Y-m-d') }}</td>
                            <td>{{ $borrowing->return_date?->format('Y-m-d') ?? '-' }}</td>
                            <td>{{ ucfirst($borrowing->status) }}</td>
                            <td>
                                {{-- Conditionally show buttons based on status --}}
                                @if ($borrowing->status === 'pending')
                                <a href="{{ route('admin.borrowings.create', ['member_id' => $borrowing->member->member_id, 'book_id' => $borrowing->book->id]) }}" class="bg-green-500 text-white font-semibold py-1 px-3 rounded text-sm hover:bg-green-600">
                                    ➡️Borrow
                                </a>
                                @elseif ($borrowing->status === 'borrowed' && !$borrowing->return_date)
                                    <form action="{{ route('admin.borrowings.renew', $borrowing->borrow_id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="bg-purple-500 text-white font-semibold py-1 px-3 rounded text-sm hover:bg-purple-600">
                                            🔁Renew
                                        </button>
                                    </form>
                                    @elseif ($borrowing->status === 'late' && !$borrowing->return_date)
                    <form action="{{ route('admin.borrowings.return', $borrowing->borrow_id) }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" href="{{ route('admin.borrowings.create') }}"class="bg-red-500 text-white font-semibold py-1 px-3 rounded text-sm hover:bg-red-600">
                            ↩️Return
                        </button>
                    </form>
                                @endif
                                <a href="{{ route('admin.borrowings.show', $borrowing->borrow_id) }}" class="bg-blue-500 text-white font-semibold py-1 px-3 rounded text-sm hover:bg-blue-600">ℹ️Details</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- Pagination links --}}
        <div class="mt-8 flex justify-center">
            {{ $borrowings->links() }}
        </div>
    @else
        <div class="modern-alert alert-info">No borrowings found.</div>
    @endif
</div>
@endsection
