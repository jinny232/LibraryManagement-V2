@extends('layouts.app')

@section('title', 'Edit Member')

@section('content')
<div class="bg-gray-100 min-h-screen p-8 font-sans h-[90vh]">
    <div class="max-w-6xl mx-auto bg-white p-8 rounded-xl shadow-lg flex flex-col">
        <div class="flex justify-between items-center mb-6 flex-shrink-0">
            <h1 class="text-3xl font-bold text-gray-800">Edit Member: {{ $member->name }}</h1>
            <a href="{{ route('members.index') }}" class="text-gray-500 hover:text-gray-700">
                &larr; Back to Members
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 flex-shrink-0" role="alert">
                <strong class="font-bold">Whoops!</strong>
                <span class="block sm:inline">There were some problems with your input.</span>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('members.update', $member->member_id) }}" method="POST" enctype="multipart/form-data" class="flex-1 overflow-y-auto pr-2">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Left Column: Image Box --}}
                <div class="md:col-span-1 p-6 bg-gray-50 rounded-lg border border-gray-200 shadow-sm flex flex-col items-center">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Profile Image</h2>

                    {{-- Current Image Section (hidden when a new image is selected) --}}
                    <div id="current-image-container" class="mb-6 w-48 h-48 border-4 border-white shadow-lg rounded-full overflow-hidden">
                        <img src="{{ $member->image ? asset('storage/' . $member->image) : asset('images/default-profile.jpg') }}"
                             alt="Current Profile Image"
                             class="w-full h-full object-cover" />
                    </div>

                    {{-- New Image Preview Section (hidden by default) --}}
                    <div id="new-image-preview" class="hidden mb-6 w-48 h-48 border-4 border-white shadow-lg rounded-full overflow-hidden">
                        <img id="member-profile-preview"
                             src=""
                             alt="New Profile Image"
                             class="w-full h-full object-cover" />
                    </div>

                    <div class="w-full">
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-1 text-center">Change Profile Image</label>
                        <input type="file" name="image" id="image" accept="image/*"
                               class="mt-1 block w-full text-sm text-gray-500
                               file:mr-4 file:py-2 file:px-4
                               file:rounded-full file:border-0
                               file:text-sm file:font-semibold
                               file:bg-indigo-50 file:text-indigo-700
                               hover:file:bg-indigo-100">
                    </div>
                </div>

                {{-- Right Column: Form Fields --}}
                <div class="md:col-span-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $member->name) }}"
                              maxlength="50"  class="text-gray-700 mt-1 block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="roll_no" class="block text-sm font-medium text-gray-700 mb-1">Roll No</label>
                        <input type="text" name="roll_no" id="roll_no" value="{{ old('roll_no', $member->roll_no) }}"
                              maxlength="50"  class="text-gray-700 mt-1 block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                        <select name="year" id="year"
                                class="text-gray-700 mt-1 block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ old('year', $member->year) == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label for="major" class="block text-sm font-medium text-gray-700 mb-1">Major</label>
                        <input type="text" name="major" id="major" value="{{ old('major', $member->major) }}"
                               class="text-gray-700 mt-1 block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $member->phone_number) }}"
                             maxlength="11"   class="text-gray-700 mt-1 block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="registration_date" class="block text-sm font-medium text-gray-700 mb-1">Registration Date</label>
                        <input type="date" name="registration_date" id="registration_date"
                               value="{{ old('registration_date', \Carbon\Carbon::parse($member->registration_date)->format('Y-m-d')) }}"
                               class="text-gray-700 mt-1 block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                      <div>
        <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
        <select name="gender" id="gender"
                class="text-gray-700 mt-1 block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            <option value="Male" {{ old('gender', $member->gender) == 'Male' ? 'selected' : '' }}>Male</option>
            <option value="Female" {{ old('gender', $member->gender) == 'Female' ? 'selected' : '' }}>Female</option>
            <option value="Other" {{ old('gender', $member->gender) == 'Other' ? 'selected' : '' }}>Other</option>
        </select>
    </div>
                    <div>
                        <label for="expired_at" class="block text-sm font-medium text-gray-700 mb-1">Expired Date</label>
                        <input type="date" name="expired_at" id="expired_at"
                               value="{{ old('expired_at', \Carbon\Carbon::parse($member->expired_at)->format('Y-m-d')) }}"
                               class="text-gray-700 mt-1 block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3 flex-shrink-0">
                <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    Update Member
                </button>
                <a href="{{ route('members.index') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const imageInput = document.getElementById('image');
        const currentImageContainer = document.getElementById('current-image-container');
        const newImagePreviewContainer = document.getElementById('new-image-preview');
        const newImageTag = document.getElementById('member-profile-preview');

        if (imageInput) {
            imageInput.addEventListener('change', function (event) {
                const file = event.target.files[0];

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        // set new preview image
                        newImageTag.src = e.target.result;
                    };
                    reader.readAsDataURL(file);

                    // toggle visibility
                    currentImageContainer.classList.add('hidden');
                    newImagePreviewContainer.classList.remove('hidden');
                } else {
                    // reset if no file chosen
                    currentImageContainer.classList.remove('hidden');
                    newImagePreviewContainer.classList.add('hidden');
                    newImageTag.src = "";
                }
            });
        }
    });
</script>
@endsection
