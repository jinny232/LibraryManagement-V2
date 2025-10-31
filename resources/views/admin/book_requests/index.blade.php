@extends('layouts.app')

@section('title', 'Book Requests')

<style>
    /* Import futuristic fonts from Google Fonts */
    @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap');

    body {
        background-color: #d5e2f6; /* Light cream background */
        color: #212529; /* Dark text for readability */
        font-family: 'Roboto Mono', monospace; /* Futuristic mono font */
        min-height: 100vh;
    }

    /* Sci-fi Container */
    .scifi-container {
        background: rgba(240, 248, 255, 0.6);
        border: 1px solid #c8d1e0;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(24, 139, 145, 0.15);
        backdrop-filter: blur(5px);
        max-width: 1200px; /* Max width for larger screens */
        margin: 1rem auto;
        padding: 20px;
    }
    .scifi-table-wrapper::-webkit-scrollbar {
        display: none; /* Chrome, Safari */
    }
    .scifi-table-wrapper {
        -ms-overflow-style: none; /* IE and Edge */
        scrollbar-width: none; /* Firefox */
    }

    /* Header with Glow Effect */
    .scifi-header h2 {
        color: #188b91; /* Your jungle green */
        font-family: 'Orbitron', sans-serif; /* Futuristic header font */
        font-weight: 700;
        text-shadow: 0 0 5px #188b91, 0 0 10px #188b91; /* Subtle text glow */
    }

    .scifi-glow-line {
        height: 2px;
        width: 100%;
        background: #188b91;
        margin-top: 5px;
        box-shadow: 0 0 3px #188b91, 0 0 6px #188b91;
        animation: pulse-glow 2s infinite alternate;
    }

    /* Animations */
    @keyframes pulse-glow {
        from { box-shadow: 0 0 3px #188b91, 0 0 6px #188b91; }
        to { box-shadow: 0 0 6px #188b91, 0 0 12px #188b91; }
    }

    /* Buttons */
    .scifi-btn, .scifi-btn-secondary {
        font-family: 'Orbitron', sans-serif;
        text-transform: uppercase;
        font-weight: 700;
        border-radius: 5px;
        transition: all 0.3s ease;
        padding: 10px 20px;
    }

    .scifi-btn {
        background-color: #188b91;
        color: #ffffff;
        border: none;
        box-shadow: 0 0 10px #188b91;
    }

    .scifi-btn:hover {
        background-color: #147277;
        transform: translateY(-2px);
        box-shadow: 0 0 15px #188b91;
    }

    .scifi-btn-secondary {
        background: none;
        color: #188b91;
        border: 1px solid #188b91;
        box-shadow: inset 0 0 5px #188b91, 0 0 5px #188b91;
        padding: 5px 10px;
        margin-right: 5px; /* Add some space between buttons */
    }

    .scifi-btn-secondary:hover {
        background-color: #188b91;
        color: #ffffff;
        box-shadow: inset 0 0 10px #188b91, 0 0 10px #188b91;
    }

    /* Table styling */
    .scifi-table-wrapper {
        overflow-x: auto;
    }

    .scifi-table {
        width: 100%;
        border-collapse: collapse;
        background: rgba(240, 248, 255, 0.8);
        border: 1px solid #c8d1e0;
        border-radius: 8px;
        color: #212529;
    }

    .scifi-table thead {
        background-color: #e3e8f0;
        border-bottom: 2px solid #188b91;
    }

    .scifi-table th, .scifi-table td {
        padding: 15px;
        border: 1px solid #c8d1e0;
        text-align: left;
    }

    .scifi-table th {
        color: #188b91;
        font-family: 'Orbitron', sans-serif;
        font-weight: 700;
    }

    .scifi-table tbody tr {
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .scifi-table tbody tr:hover {
        background-color: rgba(24, 139, 145, 0.08);
        color: #188b91;
    }

    .scifi-table tbody tr:nth-child(even) {
        background-color: rgba(240, 248, 255, 0.6);
    }

    .scifi-alert-success {
        background-color: rgba(24, 139, 145, 0.15);
        color: #188b91;
        border: 1px solid #188b91;
        border-radius: 5px;
        padding: 1rem;
        box-shadow: 0 0 10px rgba(24, 139, 145, 0.2);
    }

    .scifi-alert-info {
        background-color: rgba(24, 139, 145, 0.15);
        color: #188b91;
        border: 1px solid #188b91;
        border-radius: 5px;
        padding: 1rem;
        box-shadow: 0 0 10px rgba(24, 139, 145, 0.2);
    }
    .scifi-table-wrapper {
        max-height: 450px; /* adjust as needed */
        overflow-y: auto;
        overflow-x: auto; /* keep horizontal scroll if needed */
        border: 1px solid #c8d1e0;
        border-radius: 8px;
    }
</style>

@section('content')
<div class="container scifi-container">
    <div class="d-flex justify-content-between align-items-center mb-3" style="margin-top: 20px;">
        <div class="scifi-header">
            <h2>Book Requests</h2>
            <div class="scifi-glow-line"></div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert scifi-alert-success">{{ session('success') }}</div>
    @endif

    {{-- Add a check for error messages --}}
    @if (session('error'))
        <div class="alert scifi-alert-info">{{ session('error') }}</div>
    @endif

    @if ($bookRequests->count())
        <div class="scifi-table-wrapper">
            <table class="table scifi-table">
                <thead class="table-dark">
                    <tr>
                        <th>Requested By</th>
                        <th>Book Title</th>
                        <th>Requested On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookRequests as $request)
                        <tr>
                            {{-- Corrected from user_name to requester_name --}}
                            <td>{{ $request->requester_name }}</td>
                            <td>{{ $request->book->title }}</td>
                            <td>{{ $request->created_at->format('M d, Y') }}</td>
                            <td>
                                <form action="{{ route('admin.book-requests.approve', $request) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn scifi-btn-secondary">Approve</button>
                                </form>
                                <form action="{{ route('admin.book-requests.decline', $request) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn scifi-btn-secondary">Decline</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert scifi-alert-info">No pending book requests.</div>
    @endif
</div>
@endsection
