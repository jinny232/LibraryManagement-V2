@extends('layouts.app')

@section('content')
<style>
    /* Import futuristic fonts */
    @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap');

    body {
        background-color: #d5e2f6;
        font-family: 'Roboto Mono', monospace;
    }

    .scifi-container {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid #c8d1e0;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(24, 139, 145, 0.15);
        backdrop-filter: blur(5px);
        padding: 2rem;
        margin-top: 1rem;
        max-width: 1280px;
        margin-left: auto;
        margin-right: auto;
    }

    .scifi-header {
        color: #188b91;
        font-family: 'Orbitron', sans-serif;
        text-shadow: 0 0 5px #188b91, 0 0 10px #188b91;
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 1.5rem;
    }

    .scifi-alert-success {
        background-color: rgba(24, 139, 145, 0.15);
        color: #188b91;
        border: 1px solid #188b91;
        border-radius: 5px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .scifi-alert-danger {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border: 1px solid #dc3545;
        border-radius: 5px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .scifi-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #c8d1e0;
        box-shadow: 0 0 15px rgba(24, 139, 145, 0.1);
        background-color: #ffffff;
    }

    .scifi-table thead th {
        background-color: #188b91;
        color: #ffffff;
        font-family: 'Orbitron', sans-serif;
        font-weight: 700;
        text-align: left;
        padding: 1rem;
        border-right: 1px solid rgba(255, 255, 255, 0.2);
    }

    .scifi-table thead th:last-child {
        border-right: none;
    }

    .scifi-table tbody tr {
        background-color: #f8f9fa;
        transition: background-color 0.3s ease;
        border-bottom: 1px solid #c8d1e0;
    }

    .scifi-table tbody tr:last-child {
        border-bottom: none;
    }

    .scifi-table tbody tr:hover {
        background-color: #e9ecef;
    }

    .scifi-table tbody td {
        padding: 1rem;
        border-right: 1px solid #c8d1e0;
        font-family: 'Roboto Mono', monospace;
        color: #343a40;
    }

    .scifi-table tbody td:last-child {
        border-right: none;
    }

</style>

<div class="container scifi-container">
    <h2 class="scifi-header">Returned Borrowings</h2>
    <table class="scifi-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Book</th>
                <th>Member</th>
                <th>Returned Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($borrowings as $borrowing)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $borrowing->book->title }}</td>
                    <td>{{ $borrowing->member->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($borrowing->returned_at)->format('Y-m-d') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No returned borrowings.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
