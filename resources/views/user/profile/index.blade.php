@extends('layouts.appuser')

@section('title', 'My Profile')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4 text-center">My Profile</h1>
        <div class="card shadow-sm mx-auto" style="max-width: 900px;">
            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-7 border-end">
                        <h5 class="card-title text-center mb-4">Member Information</h5>
                        <form method="POST" action="{{ route('user.profile.update') }}">
                            @csrf
                            @method('PATCH')
                            <div class="list-group list-group-flush">
                                <div class="list-group-item">
                                    <p class="mb-0"><strong>Name:</strong> {{ $member->name }}</p>
                                </div>

                                <p class="list-group-item"><strong>Roll No:</strong> {{ $member->roll_no }}</p>

                                <div class="list-group-item">
                                    <label for="email" class="form-label"><strong>Email:</strong></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                        maxlength="50"  value="{{ old('email', $member->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="list-group-item">
                                    <label for="phone_number" class="form-label"><strong>Phone Number:</strong></label>
                                    <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number"
                                        maxlength="11" value="{{ old('phone_number', $member->phone_number) }}">
                                    @error('phone_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <p class="list-group-item"><strong>Major:</strong> {{ $member->major }}</p>

                                <p class="list-group-item"><strong>Year:</strong> {{ $member->year }}</p>

                                <p class="list-group-item"><strong>Member Since:</strong>
                                    {{ $member->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-5 d-flex flex-column align-items-center justify-content-center">
                        <h6 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Your Member QR Code</h6>
                        <div
                            class="scifi-qrcode w-48 h-48 bg-white dark:bg-gray-900 rounded-lg p-2 flex items-center justify-center">
                            {!! $qrCode !!}
                        </div>

                        <hr class="my-5 w-75">

                        <div class="d-flex flex-column align-items-center">
                            <h6 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Profile Image</h6>
                            <div class="mb-4">
                                <img id="profile-image-preview" src="{{ $member->image ? asset('storage/' . $member->image) : asset('images/default-profile.png') }}"
                                    class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                            </div>

                            <form method="POST" action="{{ route('user.profile.updateImage') }}"
                                enctype="multipart/form-data" class="text-center w-100">
                                @csrf
                                @method('PATCH')
                                <div class="mb-3">
                                    <label for="image" class="form-label">Change Image</label>
                                    <input class="form-control @error('image') is-invalid @enderror" type="file" id="image" name="image"
                                        accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-secondary mt-2">Upload New Image</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('profile-image-preview');

            if (imageInput && imagePreview) {
                imageInput.addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
        function validatePhoneNo() {
        const phoneInput = document.getElementById('phone_number');
        const phoneError = document.getElementById('phone-number-error');
        const phoneValue = phoneInput.value;

        // Remove any non-digit characters
        const cleanedValue = phoneValue.replace(/\D/g, '');
        phoneInput.value = cleanedValue;

        if (cleanedValue.length > 11) {
            phoneError.textContent = 'Phone number cannot exceed 11 digits.';
            phoneError.style.display = 'block';
            // Optionally, trim the value to 11 digits
            phoneInput.value = cleanedValue.slice(0, 11);
        } else if (cleanedValue.length < 11 && cleanedValue.length > 0) {
            phoneError.textContent = 'Phone number must be exactly 11 digits.';
            phoneError.style.display = 'block';
        } else {
            phoneError.style.display = 'none';
        }
    }
    </script>
@endsection
