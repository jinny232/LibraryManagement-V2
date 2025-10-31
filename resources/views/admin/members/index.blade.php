@extends('layouts.app')

@section('content')
    {{-- A better practice would be to move the custom CSS below into a separate file and include it. --}}
    <style>
        /* Hides both vertical and horizontal scrollbars on the entire page while keeping scrolling functionality */
        html,
        body {
            height: 100%;
            -ms-overflow-style: none;
            /* For Internet Explorer and Edge */
            scrollbar-width: none;
            /* For Firefox */
            color: black;
        }

        html::-webkit-scrollbar,
        body::-webkit-scrollbar {
            display: none;
            /* For Chrome, Safari, and Opera */
        }

        .modern-container {
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 25px;
            padding: 2.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .modern-gradient-line {
            height: 3px;
            width: 100%;
            background: linear-gradient(90deg, #6a11cb, #2575fc);
            border-radius: 2px;
        }

        /* Forces table header and cell text to stay on one line */
        .modern-table-wrapper th,
        .modern-table-wrapper td {
            white-space: nowrap;
        }

        /* Hides the scrollbar specifically on the table wrapper */
        .modern-table-wrapper::-webkit-scrollbar {
            display: none;
            /* For Chrome, Safari, and Opera */
        }

        .modern-table-wrapper {
            -ms-overflow-style: none;
            /* For Internet Explorer and Edge */
            scrollbar-width: none;
            /* For Firefox */
        }


        .pagination-link {
            @apply flex items-center justify-center w-10 h-10 border rounded-full transition-colors duration-200;
        }

        .pagination-link.active {
            @apply bg-blue-600 text-white border-blue-600;
        }
    </style>

    <div class="container modern-container ">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <div class="modern-header mb-4 md:mb-0">
                <h2 class="text-3xl font-bold text-gray-800">Member Management</h2>
                <div class="modern-gradient-line"></div>
            </div>
            <a href="{{ route('members.create') }}"
                class="px-5 py-2 rounded-lg text-white font-semibold transition-transform duration-300 transform hover:-translate-y-1 shadow-lg"
                style="background: linear-gradient(90deg, #6a11cb, #2575fc);">
                <img src="assets/img/create.png " style="width:30px;height:30px">
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 rounded-lg text-green-700 bg-green-100 border border-green-200 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('members.index') }}" method="GET"
            class="flex flex-col md:flex-row gap-4 items-center mb-6 p-4 bg-gray-100 rounded-lg shadow-inner">
            <input type="text" name="search" placeholder="Search by name or roll number..."
                maxlength="100" value="{{ request('search') }}"
                class="w-full md:w-auto px-3 py-2 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 flex-grow">

            {{-- Major Filter --}}
            <select name="major"
                class="w-full md:w-auto px-3 py-2 border border-gray-300 rounded-lg text-black-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Majors</option>
                @foreach ($majors as $major)
                    <option value="{{ $major }}" {{ request('major') == $major ? 'selected' : '' }}>
                        {{ $major }}
                    </option>
                @endforeach
            </select>

            {{-- Year Filter (uses year int as value and year string as display) --}}
            <select name="year"
                class="w-full md:w-auto px-3 py-2 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Years</option>
                {{-- Assuming the controller passes an array like: [1 => '1st Year', 2 => '2nd Year', ...] --}}
                @foreach ($years as $yearInt => $yearString)
                    <option value="{{ $yearInt }}" {{ request('year') == $yearInt ? 'selected' : '' }}>
                        {{ $yearString }}
                    </option>
                @endforeach
            </select>

            {{-- Gender Filter (hardcoded options) --}}
            <select name="gender"
                class="w-full md:w-auto px-3 py-2 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Genders</option>
                <option value="Male" {{ request('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ request('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                <option value="Other" {{ request('gender') == 'Other' ? 'selected' : '' }}>Other</option>
            </select>

            <div class="flex gap-2 w-full md:w-auto">
                <button type="submit"
                    class="py-2 px-4 bg-blue-200 text-white font-semibold rounded-lg transition-colors duration-200 hover:bg-blue-700 flex-grow">
                    <img src="assets/img/filter.png" style="width:30px;height:30px" alt="Filter">
                </button>
                <a href="{{ route('members.index') }}"
                    class="py-2 px-4 text-center bg-gray-200 text-gray-800 font-semibold rounded-lg transition-colors duration-200 hover:bg-gray-300 flex-grow">
                    <img src="assets/img/delete.png" style="width:30px;height:30px" alt="Clear">
                </a>
            </div>
        </form>

        {{-- Charts Section with Selectors --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-gray-50 p-6 rounded-lg shadow-inner">
                <h3 class="text-xl font-semibold mb-4 text-center text-gray-800">Members per Major</h3>
                <select id="majorFilterChart"
                    class="w-full px-3 py-2 mb-4 border border-gray-300 rounded-lg text-black-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Majors</option>
                    @foreach ($majors as $major)
                        <option value="{{ $major }}">{{ $major }}</option>
                    @endforeach
                </select>
                <canvas id="membersPerMajorChart"></canvas>
            </div>
            <div class="bg-gray-50 p-6 rounded-lg shadow-inner">
                <h3 class="text-xl font-semibold mb-4 text-center text-gray-800">Members per Year</h3>
                <select id="yearFilterChart"
                    class="w-full px-3 py-2 mb-4 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Years</option>
                    @foreach ($years as $yearInt => $yearString)
                        <option value="{{ $yearInt }}">{{ $yearString }}</option>
                    @endforeach
                </select>
                <canvas id="membersPerYearChart"></canvas>
            </div>
            <div class="bg-gray-50 p-6 rounded-lg shadow-inner">
                <h3 class="text-xl font-semibold mb-4 text-center text-gray-800">Members per Gender</h3>
                <canvas id="membersPerGenderChart"></canvas>
            </div>
        </div>

        @if ($members->count())
            <div class="overflow-x-auto modern-table-wrapper rounded-lg border border-gray-200 shadow-sm">
                <table class="w-full border-collapse font-sans text-sm text-gray-600">
                    <thead class="bg-gray-100 sticky top-0 z-10">
                        <tr>
                            {{-- Changed from ID to No. to represent the list order --}}
                            <th class="py-4 px-6 text-left font-bold text-gray-600">No.</th>
                            <th class="py-4 px-6 text-left font-bold text-gray-600">Name</th>
                            <th class="py-4 px-6 text-left font-bold text-gray-600">Roll No</th>
                            <th class="py-4 px-6 text-left font-bold text-gray-600">Major</th>
                            <th class="py-4 px-6 text-left font-bold text-gray-600">Year</th>
                            <th class="py-4 px-6 text-left font-bold text-gray-600">Gender</th>
                            <th class="py-4 px-6 text-center font-bold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($members as $member)
                            <tr class="transition-colors duration-200 hover:bg-gray-50">
                                {{-- This code correctly calculates the list number for each paginated page --}}
                                <td class="py-4 px-6 border-b border-gray-200">
                                    {{ ($members->currentPage() - 1) * $members->perPage() + $loop->iteration }}
                                </td>
                                <td class="py-4 px-6 border-b border-gray-200">{{ $member->name }}</td>
                                <td class="py-4 px-6 border-b border-gray-200">{{ $member->roll_no }}</td>
                                <td class="py-4 px-6 border-b border-gray-200">{{ $member->major }}</td>
                                <td class="py-4 px-6 border-b border-gray-200">{{ $member->year_string }}</td>
                                <td class="py-4 px-6 border-b border-gray-200">{{ $member->gender }}</td>
                                <td class="py-4 px-6 border-b border-gray-200 text-center whitespace-nowrap">
                                    <a href="{{ route('admin.members.printCard', $member) }}" target="_blank"
                                        class="px-3 py-1 bg-green-100 text-green-700 rounded-md text-sm font-medium hover:bg-green-200 transition-colors duration-200">
                                        🖨️
                                    </a>
                                    <a href="{{ route('members.show', $member) }}"
                                        class="px-3 py-1 bg-blue-200 text-blue-700 rounded-md text-sm font-medium hover:bg-gray-300 transition-colors duration-200">
                                        👀
                                    </a>
                                    <a href="{{ route('members.edit', $member) }}"
                                        class="px-3 py-1 bg-blue-100 text-blue-700 rounded-md text-sm font-medium hover:bg-blue-200 transition-colors duration-200">
                                        ✏️
                                    </a>
                                    <form action="{{ route('members.destroy', $member) }}" method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Are you sure you want to delete this member?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-1 bg-red-100 text-red-700 rounded-md text-sm font-medium hover:bg-red-200 transition-colors duration-200">
                                            ❌
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-6 text-center text-blue-700 bg-blue-100 border border-blue-200 rounded-lg shadow-sm">
                No members found.
            </div>
        @endif

        {{ $members->links() }}
    </div>

    {{-- Chart.js and custom script section --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Use the data passed from the controller
        const membersData = @json($membersByMajor);
        const membersByYearData = @json($membersByYear);
        const membersByGenderData = @json($membersByGender);

        // Map year integers to strings
        const yearsMapping = {
            1: '1st Year',
            2: '2nd Year',
            3: '3rd Year',
            4: '4th Year',
            5: '5th Year',
        };

        // Major Chart
        const membersPerMajorCtx = document.getElementById('membersPerMajorChart').getContext('2d');
        const majorsChart = new Chart(membersPerMajorCtx, {
            type: 'bar',
            data: {
                labels: membersData.map(item => item.major),
                datasets: [{
                    label: 'Number of Members',
                    data: membersData.map(item => item.total),
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Year Chart
        const membersPerYearCtx = document.getElementById('membersPerYearChart').getContext('2d');
        const yearsChart = new Chart(membersPerYearCtx, {
            type: 'bar',
            data: {
                labels: membersByYearData.map(item => yearsMapping[item.year]),
                datasets: [{
                    label: 'Number of Members',
                    data: membersByYearData.map(item => item.total),
                    backgroundColor: 'rgba(16, 185, 129, 0.5)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Gender Chart
        const membersPerGenderCtx = document.getElementById('membersPerGenderChart').getContext('2d');
        const genderChart = new Chart(membersPerGenderCtx, {
            type: 'pie',
            data: {
                labels: membersByGenderData.map(item => item.gender),
                datasets: [{
                    label: 'Number of Members',
                    data: membersByGenderData.map(item => item.total),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)', // Red
                        'rgba(54, 162, 235, 0.5)', // Blue
                        'rgba(255, 206, 86, 0.5)', // Yellow
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.raw !== null) {
                                    label += context.raw;
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });


        // Add event listener for the major filter
        document.getElementById('majorFilterChart').addEventListener('change', (e) => {
            const selectedMajor = e.target.value;

            // Filter the members data
            const filteredData = selectedMajor ?
                membersData.filter(item => item.major === selectedMajor) :
                membersData;

            // Update the chart
            majorsChart.data.labels = filteredData.map(item => item.major);
            majorsChart.data.datasets[0].data = filteredData.map(item => item.total);
            majorsChart.update();
        });

        // Add event listener for the year filter
        document.getElementById('yearFilterChart').addEventListener('change', (e) => {
            const selectedYear = parseInt(e.target.value, 10);

            // Filter the members by year data
            const filteredData = selectedYear ?
                membersByYearData.filter(item => item.year === selectedYear) :
                membersByYearData;

            // Update the chart
            yearsChart.data.labels = filteredData.map(item => yearsMapping[item.year]);
            yearsChart.data.datasets[0].data = filteredData.map(item => item.total);
            yearsChart.update();
        });
    </script>
@endsection
