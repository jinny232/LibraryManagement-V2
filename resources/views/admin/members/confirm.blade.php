@extends('layouts.app')

@section('content')
<style>
    /* Import a clean, modern font */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    body {
        background-color: #f8f9fa; /* A light, modern off-white background */
        color: #212529; /* Dark text for readability */
        font-family: 'Inter', sans-serif; /* Clean, modern sans-serif font */
    }

    /* Modern Container with more rounded corners and a soft shadow */
    .modern-container {
        background: #ffffff; /* Clean white background */
        border: 1px solid #e9ecef;
        border-radius: 25px; /* Increased for a more rounded look */
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); /* Soft, subtle shadow */
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
        color: #343a40; /* Highlight labels with a dark color */
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

    /* Icon styling for a 3D effect */
    .icon-wrapper {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 24px;
      height: 24px;
      margin-right: 8px;
      position: relative;
      transform: translateY(-2px); /* Align with text baseline */
    }

    .icon-shadow {
      position: absolute;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.1);
      border-radius: 50%;
      transform: scale(0.9) translate(2px, 2px);
      z-index: 0;
      filter: blur(1px);
    }

    .icon-main {
      position: relative;
      z-index: 1;
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
                <p class="mb-3 text-lg">
                    <span class="icon-wrapper">
                        <svg class="icon-shadow" viewBox="0 0 24 24"></svg>
                        <svg class="icon-main" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#6a11cb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </span>
                    <strong>Name:</strong> {{ $member->name }}
                </p>
                <p class="mb-3 text-lg">
                    <span class="icon-wrapper">
                        <svg class="icon-shadow" viewBox="0 0 24 24"></svg>
                        <svg class="icon-main" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2575fc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                    </span>
                    <strong>Email:</strong> {{ $member->email }}
                </p>
                <p class="mb-3 text-lg">
                    <span class="icon-wrapper">
                        <svg class="icon-shadow" viewBox="0 0 24 24"></svg>
                        <svg class="icon-main" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#6a11cb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                        </svg>
                    </span>
                    <strong>Roll No:</strong> {{ $member->roll_no }}
                </p>
                <p class="mb-3 text-lg">
                    <span class="icon-wrapper">
                        <svg class="icon-shadow" viewBox="0 0 24 24"></svg>
                        <svg class="icon-main" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2575fc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                    </span>
                    <strong>Year:</strong>
                    @switch($member->year)
                        @case(1)
                            First Year
                            @break
                        @case(2)
                            Second Year
                            @break
                        @case(3)
                            Third Year
                            @break
                        @case(4)
                            Fourth Year
                            @break
                        @case(5)
                            Fifth Year
                            @break
                        @default
                            {{ $member->year }}
                    @endswitch
                </p>
                <p class="mb-3 text-lg">
                    <span class="icon-wrapper">
                        <svg class="icon-shadow" viewBox="0 0 24 24"></svg>
                        <svg class="icon-main" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#6a11cb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                    </span>
                    <strong>Major:</strong> {{ $member->major }}
                </p>
                <p class="mb-3 text-lg">
                    <span class="icon-wrapper">
                        <svg class="icon-shadow" viewBox="0 0 24 24"></svg>
                        <svg class="icon-main" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2575fc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.15.93.43 1.83.83 2.69a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.86.4 1.76.68 2.69.83a2 2 0 0 1 1.72 2v3z"></path>
                        </svg>
                    </span>
                    <strong>Phone Number:</strong> {{ $member->phone_number ?? 'N/A' }}
                </p>
                <p class="mb-3 text-lg">
                    <span class="icon-wrapper">
                        <svg class="icon-shadow" viewBox="0 0 24 24"></svg>
                        <svg class="icon-main" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#6a11cb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M8 2v4"></path>
                            <path d="M16 2v4"></path>
                            <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                            <path d="M3 10h18"></path>
                        </svg>
                    </span>
                    <strong>Registration Date:</strong> {{ $member->registration_date }}
                </p>
                <p class="mb-3 text-lg">
                    <span class="icon-wrapper">
                        <svg class="icon-shadow" viewBox="0 0 24 24"></svg>
                        <svg class="icon-main" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2575fc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                        </svg>
                    </span>
                    <strong>Expire Date:</strong> {{ $member->expired_at }}
                </p>
            </div>

            <div class="w-full md:w-5/12 flex items-center justify-center">
                <div class="modern-qrcode">
                    {!! $qrCode !!}
                </div>
            </div>
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('members.index') }}"
                class="inline-block modern-btn">
                Back to Members List
            </a>
        </div>
    </div>
</div>
@endsection
