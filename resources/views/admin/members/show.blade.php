@extends('layouts.app')

@section('content')
<style>
/* Import a clean, modern font */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    body {
        background-color: #f8f9fa;
        /* A light, modern off-white background */
        color: #212529;
        /* Dark text for readability */
        font-family: 'Inter', sans-serif;
        /* Clean, modern sans-serif font */
    }

    /* Modern Container with more rounded corners and a soft shadow */
    .modern-container {
        background: #ffffff;
        /* Clean white background */
        border: 1px solid #e9ecef;
        border-radius: 25px;
        /* Increased for a more rounded look */
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        /* Soft, subtle shadow */
    }

    /* Header with a clean style and gradient line */
    .modern-header h1 {
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

    /* Text & Details */
    .modern-text {
        color: #495057;
        font-family: 'Inter', sans-serif;
    }

    .modern-text strong {
        color: #343a40;
        /* Highlight labels with a dark color */
        font-weight: 600;
    }

    /* QR Code styles */
    .modern-qrcode {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 10px;
        transition: all 0.3s ease;
    }

    .modern-qrcode:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    /* Image styles */
    .member-image {
        width: 170px;
        height: 170px;
        object-fit: cover;
        border-radius: 12px;
        /* Changed to a square with rounded corners */
        border: 4px solid #ffffff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Primary button with a vibrant gradient */
    .modern-btn {
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s ease;
        padding: 10px 20px;
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
</style>

<div class="flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-4xl p-6 modern-container">
        <div class="modern-header mb-4">
            <h1 class="text-3xl font-bold mb-1">Member Details</h1>
            <div class="modern-gradient-line"></div>
        </div>

        <div class="flex flex-col md:flex-row modern-text mt-6">
            <div class="w-full md:w-7/12 md:pr-6 mb-6 md:mb-0">
                <p class="mb-3 text-lg"><strong>Name:</strong> {{ $member->name }}</p>
                <p class="mb-3 text-lg"><strong>Email:</strong> {{ $member->email }}</p>
                <p class="mb-3 text-lg"><strong>Roll No:</strong> {{ $member->roll_no }}</p>
                <p class="mb-3 text-lg"><strong>Year:</strong> {{ $member->year_string }}</p>
                <p class="mb-3 text-lg"><strong>Major:</strong> {{ $member->major }}</p>
                {{-- This is the new gender line --}}
                <p class="mb-3 text-lg"><strong>Gender:</strong> {{ ucfirst($member->gender ?? 'N/A') }}</p>
                <p class="mb-3 text-lg"><strong>Phone Number:</strong> {{ $member->phone_number ?? 'N/A' }}</p>
                <p class="mb-3 text-lg"><strong>Registration Date:</strong>
                    {{ \Carbon\Carbon::parse($member->registration_date)->format('M d, Y') }}</p>
                <p class="mb-3 text-lg"><strong>Expire Date:</strong>
                    {{ \Carbon\Carbon::parse($member->expired_at)->format('M d, Y') }}</p>
            </div>

            <div class="w-full md:w-5/12 flex flex-col items-center justify-center space-y-6">
                @if ($member->image)
                    <img src="{{ asset('storage/' . $member->image) }}" alt="{{ $member->name }}" class="member-image">
                @endif
                <div class="modern-qrcode">
                    {!! $qrCode !!}
                </div>
            </div>
        </div>

        <div class="text-center mt-8 space-x-4">
            <a href="{{ route('members.index') }}" class="inline-block modern-btn">
                ⬅️ Back to Members List
            </a>
        </div>
    </div>
</div>
@endsection
