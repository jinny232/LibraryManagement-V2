<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            background-image: linear-gradient(to top, #cfd9df 0%, #e2eef2 100%);
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen">
    <div class="bg-white p-10 rounded-3xl shadow-2xl w-full max-w-md border border-gray-100 transform transition-all hover:scale-105 duration-300">
        <div class="text-center mb-8">
            <!-- User icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-blue-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <h2 class="text-3xl font-extrabold text-gray-900">Admin Login</h2>
            <p class="mt-2 text-sm text-gray-500">Sign in to your account to continue</p>
        </div>

        <!-- This section displays a Laravel session error message -->
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative mb-6" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf

            <div class="mb-5">
                <label for="email" class="block text-sm font-semibold text-gray-800 mb-2">Email</label>
                <input type="email" id="email" name="email" maxlength="20" required class="block w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out" placeholder="Enter your email address" value="{{ old('email') }}">
                <p id="email-error" class="mt-2 text-sm text-red-600 hidden">Email must be 20 characters or less.</p>
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-7">
                <label for="password" class="block text-sm font-semibold text-gray-800 mb-2">Password</label>
                <input type="password" id="password" name="password" required maxlength="20" class="block w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out" placeholder="Enter your password">
                <p id="password-error" class="mt-2 text-sm text-red-600 hidden">Password must be 20 characters or less.</p>
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full py-3 px-4 rounded-lg bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold text-lg hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg transition duration-200 ease-in-out">
                Login
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const emailError = document.getElementById('email-error');
            const passwordError = document.getElementById('password-error');

            function validateInput(input, errorElement) {
                if (input.value.length > 20) {
                    errorElement.classList.remove('hidden');
                } else {
                    errorElement.classList.add('hidden');
                }
            }

            emailInput.addEventListener('input', () => validateInput(emailInput, emailError));
            passwordInput.addEventListener('input', () => validateInput(passwordInput, passwordError));
        });
    </script>
</body>

</html>
