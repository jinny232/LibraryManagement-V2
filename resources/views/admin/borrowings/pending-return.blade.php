@extends('layouts.app')

@section('content')

<div class="container mx-auto max-w-7xl mt-8 p-10 bg-white rounded-xl shadow-lg">
    <h2 class="text-4xl font-extrabold  from-purple-700 to-blue-500 text-gray-600 text-center pb-6 border-b border-gray-200 mb-6">Pending Book Returns ⏳</h2>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 border border-green-300 rounded-lg p-4 mb-4 font-medium flex items-center space-x-2">
            <span>✅</span> <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 text-red-700 border border-red-300 rounded-lg p-4 mb-4 font-medium flex items-center space-x-2">
            <span>❌</span> <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded-lg overflow-hidden shadow">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No.</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Member Name 🧑‍🎓</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Book Title 📚</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Borrow Date 📅</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Due Date 🗓️</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Action ⚙️</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($borrowings as $borrowing)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $borrowing->member->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $borrowing->book->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ \Carbon\Carbon::parse($borrowing->due_date)->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <div class="flex items-center justify-center space-x-2">
                                <button type="button" class="px-4 py-2 font-medium text-white rounded-lg shadow-md transition duration-200 ease-in-out transform hover:-translate-y-1 bg-gradient-to-r from-green-600 to-green-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50"
                                    onclick="showReturnConfirmationModal('{{ route('admin.borrowings.return', ['borrowing' => $borrowing->borrow_id]) }}');">
                                   ↩️ Return
                                </button>
                                <button type="button" class="px-4 py-2 font-medium text-white rounded-lg shadow-md transition duration-200 ease-in-out transform hover:-translate-y-1 bg-gradient-to-r from-purple-700 to-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50"
                                    onclick="showRenewConfirmationModal('{{ route('admin.borrowings.renew', ['borrowing' => $borrowing->borrow_id]) }}');">
                                  🔄Renew
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-gray-400 italic">No pending returns found. 🧘</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Confirmation Modal for Returning a Book --}}
<div id="return-confirmation-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center z-50">
    <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-sm text-center">
        <h3 class="text-2xl font-semibold text-gray-800 mb-4">Confirm Return ↩️</h3>
        <p class="text-gray-600 mb-6">Are you sure you want to mark this book as returned?</p>
        <div class="flex justify-center space-x-4">
            <button class="px-6 py-2 bg-gray-500 text-white rounded-lg transition duration-200 hover:bg-gray-600" onclick="hideReturnConfirmationModal()">Cancel</button>
            <form id="return-form" method="POST" action="">
                @csrf
                @method('PATCH')
                <button type="submit" class="px-6 py-2 bg-green-500 text-white rounded-lg transition duration-200 hover:bg-green-600">Confirm</button>
            </form>
        </div>
    </div>
</div>

{{-- Confirmation Modal for Renewing a Book --}}
<div id="renew-confirmation-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center z-50">
    <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-sm text-center">
        <h3 class="text-2xl font-semibold text-gray-800 mb-4">Confirm Renewal 🔄</h3>
        <p class="text-gray-600 mb-6">Are you sure you want to renew this book?</p>
        <div class="flex justify-center space-x-4">
            <button class="px-6 py-2 bg-gray-500 text-white rounded-lg transition duration-200 hover:bg-gray-600" onclick="hideRenewConfirmationModal()">Cancel</button>
            <form id="renew-form" method="POST" action="">
                @csrf
                <button type="submit" class="px-6 py-2 bg-green-500 text-white rounded-lg transition duration-200 hover:bg-green-600">Confirm</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Functions for the Return modal
    function showReturnConfirmationModal(actionUrl) {
        const modal = document.getElementById('return-confirmation-modal');
        const form = document.getElementById('return-form');
        form.action = actionUrl;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function hideReturnConfirmationModal() {
        const modal = document.getElementById('return-confirmation-modal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    // Functions for the Renew modal
    function showRenewConfirmationModal(actionUrl) {
        const modal = document.getElementById('renew-confirmation-modal');
        const form = document.getElementById('renew-form');
        form.action = actionUrl;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function hideRenewConfirmationModal() {
        const modal = document.getElementById('renew-confirmation-modal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
</script>
@endsection
