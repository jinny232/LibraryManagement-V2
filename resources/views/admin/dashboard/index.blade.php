@extends('layouts.app')

@section('title', 'DASHBOARD')

@section('content')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        :root {
            --bg-color: #f0f4f8;
            --card-bg: #ffffff;
            --text-color: #2c3e50;
            --primary-color: #3498db;
            --secondary-color: #95a5a6;
            --accent-color: #e74c3c;
            --border-color: #e2e8f0;
            --shadow-light: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }



        .dashboard-container {
            /* This padding provides spacing around the content. */
            padding: 1.5rem;
            /* The width will automatically be full-screen because there is no 'max-width' specified. */
        }

        .dashboard-container::-webkit-scrollbar {
            width: 0;
        }

        .dashboard-header {
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }

        .dashboard-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .stats-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--shadow-light);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        /* Apply a more modern, consistent hover state */
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, #0628e8, #400fef, #950fef);
        }

        .stats-card-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }

        .stats-card-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-color);
        }

        .list-section {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--shadow-light);
        }

        .list-section-header {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .list-items {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .list-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.2s ease;
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .list-item:hover {
            background-color: #f8fafc;
        }

        .list-item-title {
            font-weight: 500;
            color: var(--text-color);
        }

        .list-item-count {
            font-weight: 600;
            color: var(--primary-color);
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table-responsive::-webkit-scrollbar {
            height: 8px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background-color: #f1f5f9;
        }
    </style>

    <div class="dashboard-container overflow-y-scroll h-screen">
        <header class="dashboard-header">
            <h2>📊 Reports Dashboard</h2>
        </header>

        <div class="stats-card-grid grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="stats-card">
                <p class="stats-card-title">📚 Total Books Borrowed</p>
                <p class="stats-card-number">{{ $dashboardStats['totalBorrowed'] }}</p>
            </div>

            <div class="stats-card">
                <p class="stats-card-title">📖 Currently Borrowed Books</p>
                <p class="stats-card-number">{{ $dashboardStats['currentlyBorrowed'] }}</p>
            </div>

            <div class="stats-card">
                <p class="stats-card-title">🚨 Overdue Books</p>
                <p class="stats-card-number">{{ $dashboardStats['overdueCount'] }}</p>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Dashboard Stats Chart -->
            <div class="list-section">
                <h3 class="list-section-header">📈 Dashboard Stats</h3>
                <div class="h-64">
                    <canvas id="dashboardStatsChart"></canvas>
                </div>
            </div>

            <!-- Top 5 Most Borrowed Books Chart -->
            <div class="list-section">
                <h3 class="list-section-header">🏆 Top 5 Most Borrowed Books</h3>
                <div class="h-64">
                    <canvas id="mostBorrowedBooksChart"></canvas>
                </div>
            </div>

            <!-- Top 5 Members by Borrowings Chart -->
            <div class="list-section">
                <h3 class="list-section-header">👥 Top 5 Members by Borrowings</h3>
                <div class="h-64">
                    <canvas id="topMembersChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Original lists below the charts -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="list-section">
                <h3 class="list-section-header">📚 Top 5 Most Borrowed Books</h3>
                <ul class="list-items">
                    @foreach ($mostBorrowedBooks as $book)
                        <li class="list-item">
                            <span class="list-item-title">{{ $book->book->title }}</span>
                            <span class="list-item-count">{{ $book->borrow_count }}  times</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="list-section">
                <h3 class="list-section-header">👤 Top 5 Members by Borrowings</h3>
                <ul class="list-items">
                    @foreach ($topMembers as $member)
                        <li class="list-item">
                            <span class="list-item-title">{{ $member->member->name }}</span>
                            <span class="list-item-count">{{ $member->borrow_count }} times</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- NEW SECTION: Recent Borrowings -->
        <div class="mt-6 list-section">
            <h3 class="list-section-header">⏱️ Recent Borrowings</h3>
            <div class="table-responsive">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                🧑‍🤝‍🧑 Member</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"> 📖 Book
                                Title</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                               📅 Borrowed Date</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">⏰ Due
                                Date</th>
                                <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($recentBorrowings as $borrowing)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $borrowing->member->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $borrowing->book->title }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $borrowing->borrow_date }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $borrowing->due_date }}
                                </td>
                                <td><a href="{{ route('admin.borrowings.show', $borrowing->borrow_id) }}" class="bg-blue-500 text-white font-semibold py-1 px-3 rounded text-sm hover:bg-blue-600">ℹ️Details</a>
</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data from the backend, injected as JSON
            const dashboardStats = @json($dashboardStats);
            const mostBorrowedBooks = @json($mostBorrowedBooks);
            const topMembers = @json($topMembers);

            // --- Chart Rendering Functions ---
            // Helper function to generate a bar chart
            function createBarChart(canvasId, labels, data, chartTitle, color) {
                const ctx = document.getElementById(canvasId).getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: chartTitle,
                            data: data,
                            backgroundColor: color,
                            borderColor: color,
                            borderWidth: 1,
                            borderRadius: 5,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                },
                                grid: {
                                    color: 'rgba(0,0,0,0.05)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.7)',
                                titleFont: {
                                    size: 14
                                },
                                bodyFont: {
                                    size: 12
                                },
                                padding: 10,
                                cornerRadius: 8,
                            }
                        }
                    }
                });
            }

            // Render Dashboard Stats Chart
            const dashboardStatsLabels = ['Total Borrowed', 'Currently Borrowed', 'Overdue'];
            const dashboardStatsData = [dashboardStats.totalBorrowed, dashboardStats.currentlyBorrowed,
                dashboardStats.overdueCount
            ];
            const dashboardStatsColors = ['#2575fc', '#6a11cb', '#e74c3c'];
            createBarChart('dashboardStatsChart', dashboardStatsLabels, dashboardStatsData, 'Borrowing Stats',
                dashboardStatsColors);

            // Render Most Borrowed Books Chart
            const mostBorrowedBookLabels = mostBorrowedBooks.map(item => item.book.title);
            const mostBorrowedBookData = mostBorrowedBooks.map(item => item.borrow_count);
            createBarChart('mostBorrowedBooksChart', mostBorrowedBookLabels, mostBorrowedBookData, 'Borrow Count',
                '#60009f');

            // Render Top Members Chart
            const topMembersLabels = topMembers.map(item => item.member.name);
            const topMembersData = topMembers.map(item => item.borrow_count);
            createBarChart('topMembersChart', topMembersLabels, topMembersData, 'Borrow Count', '#ff1493');
        });
    </script>

@endsection
