@extends('layouts.app')

@section('title', 'Overdue Borrowings')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    body {
        font-family: 'Inter', sans-serif;
        background: #e0eafc; /* Light blue gradient start */
        background: linear-gradient(to right, #e0eafc, #cfdef3); /* Light blue gradient */
    }

    .modern-container {
        background-color: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        padding: 2.5rem;
        transition: transform 0.3s ease-in-out;
    }

    .modern-container:hover {
        transform: translateY(-5px);
    }

    .modern-header h1 {
        color: #1f2937;
    }

    .modern-gradient-line {
        height: 4px;
        width: 80px;
        background: linear-gradient(to right, #6a11cb 0%, #2575fc 100%);
        border-radius: 2px;
        margin-top: 0.5rem;
    }

    .modern-alert-success {
        background-color: #d1fae5;
        color: #065f46;
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid #10b981;
    }

    .modern-alert-info {
        background-color: #eff6ff;
        color: #1e40af;
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid #60a5fa;
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .modern-table thead th {
        background-image: linear-gradient(to right, #6a11cb 0%, #2575fc 100%);
        color: #ffffff;
        text-align: left;
        padding: 1.25rem 1rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .modern-table tbody tr {
        background-color: #f9fafb;
        transition: all 0.2s ease;
    }

    .modern-table tbody tr:nth-child(even) {
        background-color: #f3f4f6;
    }

    .modern-table tbody tr:hover {
        background-color: #e5e7eb;
    }

    .modern-table tbody td {
        padding: 1rem;
        color: #374151;
        border-bottom: 1px solid #e5e7eb;
    }

    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .modal-container {
        background-color: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        max-width: 400px;
        width: 100%;
        text-align: center;
        transform: translateY(-50px);
        transition: all 0.3s ease-out;
    }

    .modal-container.is-open {
        transform: translateY(0);
    }
</style>

<div class="container mt-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 modern-container">
    <div class="modern-header mb-4">
        <h1 class="text-3xl font-bold mb-1">Overdue Borrowings 📚</h1>
        <div class="modern-gradient-line"></div>
    </div>

    @if(session('success'))
        <div class="modern-alert-success mb-4">{{ session('success') }}</div>
    @endif

    <div class="overflow-x-auto">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Book</th>
                    <th>Member</th>
                    <th>Due Date</th>
                    <th>Days Overdue</th>
                    <th>Actions</th> <!-- Added new column for the button -->
                </tr>
            </thead>
            <tbody>
                @forelse($borrowings as $borrowing)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $borrowing->book->title }}</td>
                        <td>{{ $borrowing->member->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($borrowing->due_date)->format('l, F j, Y') }}</td>
                        <td>{{ round(abs(now()->diffInDays($borrowing->due_date))) }} days overdue</td>
                        <td>
                            <button type="button" class="px-4 py-2 font-medium text-white rounded-lg shadow-md transition duration-200 ease-in-out transform hover:-translate-y-1 bg-gradient-to-r from-green-600 to-green-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50"
                                onclick="showReturnConfirmationModal('{{ route('admin.borrowings.return', ['borrowing' => $borrowing->borrow_id]) }}');">
                                ↩️ Return
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center modern-alert-info">No overdue borrowings.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Custom Return Confirmation Modal -->
<div id="returnConfirmationModal" class="modal-overlay">
    <div class="modal-container">
        <h5 class="text-xl font-bold text-gray-800 mb-4">Confirm Return</h5>
        <p class="text-gray-600 mb-6">Are you sure you want to mark this book as returned?</p>
        <form id="returnForm" method="POST" action="">
            @csrf
            @method('PATCH')
            <div class="flex justify-center space-x-4">
                <button type="button" class="px-6 py-2 rounded-lg bg-gray-300 text-gray-800 font-semibold hover:bg-gray-400 transition" onclick="hideReturnConfirmationModal()">Cancel</button>
                <button type="button" id="returnSubmitButton" class="px-6 py-2 rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700 transition" onclick="handleReturnFormSubmission()">Return</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Function to show the modal and set the form action
    function showReturnConfirmationModal(url) {
        const modal = document.getElementById('returnConfirmationModal');
        const form = document.getElementById('returnForm');
        form.action = url; // Set the form's action URL
        modal.style.display = 'flex';
        // Add a class to trigger the animation
        setTimeout(() => {
            modal.querySelector('.modal-container').classList.add('is-open');
        }, 10);
    }

    // Function to hide the modal
    function hideReturnConfirmationModal() {
        const modal = document.getElementById('returnConfirmationModal');
        // Remove the class to trigger the reverse animation
        modal.querySelector('.modal-container').classList.remove('is-open');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300); // Wait for the transition to finish before hiding
    }

    // Function to handle form submission and provide visual feedback
    function handleReturnFormSubmission() {
        const submitButton = document.getElementById('returnSubmitButton');
        const form = document.getElementById('returnForm');

        // Provide visual feedback
        submitButton.disabled = true;
        submitButton.textContent = 'Returning...';
        submitButton.classList.add('opacity-50', 'cursor-not-allowed');

        // Submit the form
        form.submit();
    }
</script>
@endsection
