@extends('layouts.app')

@section('content')
    <style>
        /* Your existing styles... */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            background-color: #f0f4f8;
            color: #2c3e50;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        .modern-container {
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 25px;
            padding: 3rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            max-width: 900px;
            margin: 2rem auto;
            overflow-y: auto;
            max-height: calc(100vh - 4rem);
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .modern-container::-webkit-scrollbar {
            display: none;
        }

        .modern-header h2 {
            color: #343a40;
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.05);
        }

        .modern-gradient-line {
            height: 4px;
            width: 100%;
            background: linear-gradient(90deg, #6a11cb, #2575fc);
            border-radius: 2px;
        }

        .modern-input,
        .modern-select {
            border: 1px solid #ced4da;
            border-radius: 12px;
            padding: 14px;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .modern-input:focus,
        .modern-select:focus {
            border-color: #6a11cb;
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(106, 17, 203, 0.2), inset 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .modern-btn {
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.3s ease;
            padding: 14px 24px;
            text-transform: capitalize;
            background: linear-gradient(90deg, #6a11cb, #2575fc);
            color: #ffffff;
            border: none;
            box-shadow: 0 8px 20px rgba(106, 17, 203, 0.25);
        }

        .modern-btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px rgba(106, 17, 203, 0.4);
        }

        .modern-btn:active {
            transform: translateY(-1px);
        }

        .modern-alert-danger {
            background-color: #fde8e9;
            color: #b72121;
            border: 1px solid #f9cdd3;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .icon-wrapper {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            margin-right: 8px;
            position: relative;
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

        .flatpickr-calendar {
            background-color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
            font-family: 'Inter', sans-serif;
            margin-top: 8px;
        }

        .flatpickr-day.selected,
        .flatpickr-day.selected:hover {
            background: #6a11cb;
            border-color: #6a11cb;
            color: #fff;
        }

        .flatpickr-day.today {
            border-color: #6a11cb;
            font-weight: bold;
        }

        .flatpickr-day.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* New styles for the image preview container and image */
        #image-preview-container {
            margin-top: 16px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 150px; /* Adjust as needed */
            border: 2px dashed #e0e7ff;
            border-radius: 12px;
            background-color: #f9fafb;
            padding: 1rem;
            text-align: center;
            color: #6b7280;
        }

        #image-preview-container img {
            max-width: 100%;
            max-height: 200px; /* Adjust as needed */
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <div class="p-4 flex justify-center">
        <div class="w-full modern-container">
            <div class="flex justify-between items-center mb-6">
                <div class="modern-header">
                    <h2 class="text-3xl">Add New Member</h2>
                    <div class="modern-gradient-line"></div>
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-4 modern-alert-danger">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('members.store') }}" method="POST" enctype="multipart/form-data"
                class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf

                <div>
                    <label class="block mb-2 font-medium" for="name">
                        <span class="icon-wrapper">
                            <svg class="icon-shadow" viewBox="0 0 24 24"></svg>
                            <svg class="icon-main" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="#6a11cb" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </span>
                        Name
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                       maxlength="50"  class="w-full modern-input" required />
                </div>

                <div>
                    <label class="block mb-2 font-medium" for="email">
                        <span class="icon-wrapper">
                            <svg class="icon-shadow" viewBox="0 0 24 24"></svg>
                            <svg class="icon-main" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="#2575fc" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                        </span>
                        Email
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        maxlength="50" class="w-full modern-input" required />
                </div>

                <div>
                    <label class="block mb-2 font-medium" for="phone_number">
                        <span class="icon-wrapper">
                            <svg class="icon-shadow" viewBox="0 0 24 24"></svg>
                            <svg class="icon-main" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="#6a11cb" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.15.93.43 1.83.83 2.69a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.86.4 1.76.68 2.69.83a2 2 0 0 1 1.72 2v3z">
                                </path>
                            </svg>
                        </span>
                        Phone Number
                    </label>
                    <input type="text" name="phone_number" id="phone_number"
                         maxlength="11" value="{{ old('phone_number') }}" class="w-full modern-input" />
                </div>

                <div>
                    <label class="block mb-2 font-medium" for="roll_no">
                        <span class="icon-wrapper">
                            <svg class="icon-shadow" viewBox="0 0 24 24"></svg>
                            <svg class="icon-main" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="#2575fc" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                <rect x="8" y="2" width="8" height="4" rx="1"
                                    ry="1"></rect>
                            </svg>
                        </span>
                        Roll Number
                    </label>
                    <input type="text" name="roll_no" id="roll_no" value="{{ old('roll_no') }}"
                        class="w-full modern-input" required />
                </div>

                <div>
                    <label class="block mb-2 font-medium" for="year">
                        <span class="icon-wrapper">
                            <svg class="icon-shadow" viewBox="0 0 24 24"></svg>
                            <svg class="icon-main" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#6a11cb"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12"
                                    y2="3"></line>
                            </svg>
                        </span>
                        Year
                    </label>
                    <select name="year" id="year" class="w-full modern-select" required
                        onchange="updateMajorOptions()">
                        <option value="" disabled @selected(old('year') == '')>Select Year
                        </option>
                        <option value="1" @selected(old('year') == '1')>First Year</option>
                        <option value="2" @selected(old('year') == '2')>Second Year</option>
                        <option value="3" @selected(old('year') == '3')>Third Year</option>
                        <option value="4" @selected(old('year') == '4')>Fourth Year</option>
                        <option value="5" @selected(old('year') == '5')>Fifth Year</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-2 font-medium" for="gender">
                        <span class="icon-wrapper">
                            <svg class="icon-shadow" viewBox="0 0 24 24"></svg>
                            <svg class="icon-main" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="#6a11cb" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M12 16v-4"></path>
                                <path d="M16 12h-4"></path>
                            </svg>
                        </span>
                        Gender
                    </label>
                    <select name="gender" id="gender" class="w-full modern-select" required>
                        <option value="" disabled @selected(old('gender') == '')>Select Gender</option>
                        <option value="male" @selected(old('gender') == 'male')>Male</option>
                        <option value="female" @selected(old('gender') == 'female')>Female</option>
                        <option value="other" @selected(old('gender') == 'other')>Other</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-2 font-medium" for="major">
                        <span class="icon-wrapper">
                            <svg class="icon-shadow" viewBox="0 0 24 24"></svg>
                            <svg class="icon-main" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2575fc"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path
                                    d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            </svg>
                        </span>
                        Major
                    </label>
                    <select name="major" id="major" class="w-full modern-select" required>
                        <option value="" disabled @selected(old('major') == '')>Select Major
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block mb-2 font-medium" for="registration_date">
                        <span class="icon-wrapper">
                            <svg class="icon-shadow" viewBox="0 0 24 24"></svg>
                            <svg class="icon-main" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#6a11cb"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18"
                                    rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16"
                                    y2="6"></line>
                                <line x1="8" y1="2" x2="8"
                                    y2="6"></line>
                                <line x1="3" y1="10" x2="21"
                                    y2="10"></line>
                            </svg>
                        </span>
                        Registration Date
                    </label>
                    <input type="text" name="registration_date" id="registration_date"
                        class="w-full modern-input" required />
                </div>

                <div>
                    <label class="block mb-2 font-medium" for="expired_at">
                        <span class="icon-wrapper">
                            <svg class="icon-shadow" viewBox="0 0 24 24"></svg>
                            <svg class="icon-main" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2575fc"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z">
                                </path>
                            </svg>
                        </span>
                        Expire Date
                    </label>
                    <input type="date" name="expired_at" id="expired_at"
                        value="{{ old('expired_at') }}" class="w-full modern-input" required
                        readonly />
                </div>

                <div>
                    <label class="block mb-2 font-medium" for="image">
                        <span class="icon-wrapper">
                            <svg class="icon-shadow" viewBox="0 0 24 24"></svg>
                            <svg class="icon-main" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="#6a11cb" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21 15 16 10 5 21"></polyline>
                            </svg>
                        </span>
                        Member Image
                    </label>
                    <input type="file" name="image" id="image" class="w-full modern-input" accept="image/*" />

                    <div id="image-preview-container">
                        No image selected.
                    </div>
                </div>

                <div class="md:col-span-2">
                    <button type="submit" class="w-full modern-btn">
                        Add Member
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Get the date input elements
        const registrationDateInput = document.getElementById('registration_date');
        const expireDateInput = document.getElementById('expired_at');

        // Get the year and major select elements
        const yearSelect = document.getElementById('year');
        const majorSelect = document.getElementById('major');

        // Get the image input and preview container
        const imageInput = document.getElementById('image');
        const imagePreviewContainer = document.getElementById('image-preview-container');

        // Function to calculate and set the expire date
        function calculateExpireDate(selectedDate) {
            if (selectedDate) {
                const date = new Date(selectedDate);
                date.setFullYear(date.getFullYear() + 1);

                const year = date.getFullYear();
                const month = (date.getMonth() + 1).toString().padStart(2, '0');
                const day = date.getDate().toString().padStart(2, '0');

                expireDateInput.value = `${year}-${month}-${day}`;
            } else {
                expireDateInput.value = '';
            }
        }

        // Function to update the major based on the selected year
        function updateMajorOptions() {
            const selectedYear = yearSelect.value;
            let options = '';

            // Logic to determine available majors based on the selected year
            if (selectedYear === '1') {
                options = `
                    <option value="CST" @selected(old('major') == 'CST')>CST</option>
                `;
                majorSelect.innerHTML = options;
                majorSelect.value = 'CST';
            } else if (selectedYear >= '2' && selectedYear <= '5') {
                options = `
                    <option value="" disabled selected>Select Major</option>
                    <option value="CS" @selected(old('major') == 'CS')>CS</option>
                    <option value="CT" @selected(old('major') == 'CT')>CT</option>
                `;
                majorSelect.innerHTML = options;
                // If old major is CS or CT, keep it selected, otherwise, reset.
                if (['CS', 'CT'].includes(majorSelect.value)) {
                    // Do nothing, keep the value
                } else {
                    majorSelect.value = ''; // Reset to default
                }
            } else {
                options = '<option value="" disabled selected>Select Major</option>';
                majorSelect.innerHTML = options;
                majorSelect.value = ''; // Reset to default
            }
        }

        // Event listener for image preview
        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    imagePreviewContainer.innerHTML = ''; // Clear previous preview
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Image Preview';
                    imagePreviewContainer.appendChild(img);
                };

                reader.readAsDataURL(file);
            } else {
                imagePreviewContainer.innerHTML = 'No image selected.';
            }
        });

        // Set the initial expire date on page load.
        const today = new Date().toISOString().split('T')[0];
        calculateExpireDate(today);

        // Initialize flatpickr on the registration_date input field
        flatpickr(registrationDateInput, {
            defaultDate: today,
            enable: [today],
            onChange: function(selectedDates, dateStr, instance) {
                calculateExpireDate(dateStr);
            }
        });

        // Add event listener to the year select element to update the major
        yearSelect.addEventListener('change', updateMajorOptions);

        // Call the function on page load to set the initial options
        document.addEventListener('DOMContentLoaded', updateMajorOptions);
    </script>
@endsection
