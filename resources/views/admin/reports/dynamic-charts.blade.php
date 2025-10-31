@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-3xl font-bold mb-6 text-center">Library Reports</h2>

    {{-- Buttons to detailed reports --}}
    <div class="flex justify-center gap-4 mb-8">
        <a href="{{ route('admin.reports.books') }}"
            class="px-5 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
            Books Report
        </a>
        <a href="{{ route('admin.reports.members') }}"
            class="px-5 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
            Members Report
        </a>
        <a href="{{ route('admin.reports.borrowings') }}"
            class="px-5 py-2 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition">
            Borrowings Report
        </a>
    </div>

    {{-- Charts Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Books --}}
        <div class="bg-gray-50 p-6 rounded-lg shadow-inner">
            <h3 class="text-xl font-semibold mb-4 text-center">Top 5 Most Borrowed Books</h3>
            <canvas id="booksChart"></canvas>
        </div>

        {{-- Members --}}
        <div class="bg-gray-50 p-6 rounded-lg shadow-inner">
            <h3 class="text-xl font-semibold mb-4 text-center">Top 5 Most Active Members</h3>
            <canvas id="membersChart"></canvas>
        </div>

        {{-- Borrowings --}}
        <div class="bg-gray-50 p-6 rounded-lg shadow-inner">
            <h3 class="text-xl font-semibold mb-4 text-center">Borrowings Per Month</h3>
            <canvas id="borrowingsChart"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Books chart
    const booksCtx = document.getElementById('booksChart').getContext('2d');
    new Chart(booksCtx, {
        type: 'bar',
        data: {
            labels: @json($booksData->pluck('label')),
            datasets: [{
                label: 'Borrow Count',
                data: @json($booksData->pluck('value')),
                backgroundColor: 'rgba(59,130,246,0.5)',
                borderColor: 'rgba(59,130,246,1)',
                borderWidth: 1
            }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });

    // Members chart
    const membersCtx = document.getElementById('membersChart').getContext('2d');
    new Chart(membersCtx, {
        type: 'pie',
        data: {
            labels: @json($membersData->pluck('label')),
            datasets: [{
                data: @json($membersData->pluck('value')),
                backgroundColor: ['#FF6384','#36A2EB','#FFCE56','#4BC0C0','#9966FF']
            }]
        },
        options: { responsive: true }
    });

    // Borrowings per month chart
    const borrowingsCtx = document.getElementById('borrowingsChart').getContext('2d');
    new Chart(borrowingsCtx, {
        type: 'line',
        data: {
            labels: @json($borrowingData->pluck('label')),
            datasets: [{
                label: 'Total Borrowings',
                data: @json($borrowingData->pluck('value')),
                borderColor: '#4BC0C0',
                fill: false,
                tension: 0.3
            }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });
</script>
@endsection
