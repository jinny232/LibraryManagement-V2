<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Library Dashboard</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js for interactivity -->
    <script src="//unpkg.com/alpinejs" defer></script>
    <!-- Chart.js CDN for charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        :root {
            --bg-color: #f5eded;
            --card-bg: #ffffff;
            --text-color: #f1f1f1;
            --primary-color: #4a90e2;
            --secondary-color: #b0bec5;
            --accent-color: #2ecc71;
            --border-color: #444444;
            --shadow-light: 0 4px 10px rgba(0, 0, 0, 0.3), 0 8px 20px rgba(0, 0, 0, 0.2);
            --shadow-hover: 0 8px 15px rgba(0, 0, 0, 0.4), 0 15px 30px rgba(0, 0, 0, 0.3);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
            overflow: hidden;
        }

        .dashboard-wrapper {
            display: flex;
            width: 100%;
        }

        .sidebar {
            background-color: #ebedf1;
           /* border-right: 1px solid var(--border-color);*/
            box-shadow: var(--shadow-light);
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: sticky;
            top: 0;
            left: 0;
            overflow-y: auto;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .sidebar::-webkit-scrollbar {
            display: none;
        }

        .sidebar-header {
            font-size: 1.5rem;
            font-weight: 700;
            background-image: linear-gradient(to right, #6a11cb, #2575fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 2rem;
            text-align: center;
            padding: 1.5rem 0;
        }

        .sidebar-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-nav a {
            display: block;
            position: relative;
            padding: 0.75rem 1rem;
            margin: 0 1rem 0.5rem;
            color: #555555;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sidebar-nav a.active {
            background-color: #f0f2f5;
            color: #2c3e50;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .sidebar-nav a:hover:not(.active) {
            background-color: #e0e2e6;
            color: #2c3e50;
            transform: translateX(5px);
        }

        .sidebar-nav a.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 6px;
            height: 100%;
            background: linear-gradient(to top, #6a11cb, #2575fc, #00d2ff);
            border-radius: 0 4px 4px 0;
        }

        .list-section {
            background-color: #ebedf1;
            border: 1px solid #e0e2e6;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--shadow-light);
        }

        .list-section-header {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e0e2e6;
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
            border-bottom: 1px solid #e0e2e6;
            transition: all 0.3s ease;
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .list-item:hover {
            background-color: #f0f2f5;
            transform: translateX(5px);
        }

        .list-item-title {
            font-weight: 500;
            color: #2c3e50;
        }

        .list-item-count {
            font-weight: 600;
            color: var(--accent-color);
        }
    </style>
</head>

<body>
    <div class="dashboard-wrapper">
        <aside class="z-20 hidden w-64 sidebar md:block flex-shrink-0">
            <div class="sidebar-header">
                UCSICONIC LIBRARY
            </div>

            <nav class="sidebar-nav">

                <ul>

                    <li class="{{ request()->routeIs('admin.dashboard.index') ? 'active' : '' }}">

                        <a href="{{ route('admin.dashboard.index') }}"
                            class="{{ request()->routeIs('admin.dashboard.index') ? 'active' : '' }}">

                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">

                                <path d="M9 17v-6a2 2 0 012-2h8m-6 8h6a2 2 0 002-2V7a2 2 0 00-2-2h-8a2 2 0 00-2 2v10z">

                                </path>

                            </svg>

                            <span>Dashboard</span>

                        </a>

                    </li>

                    <li class="{{ request()->routeIs('members.*') ? 'active' : '' }}">

                        <a href="{{ route('members.index') }}"
                            class="{{ request()->routeIs('members.*') ? 'active' : '' }}">

                            <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">

                                </path>

                            </svg>

                            <span>Member List</span>

                        </a>

                    </li>

                    <li class="{{ request()->routeIs('admin.books.index') ? 'active' : '' }}">

                        <a href="{{ route('admin.books.index') }}"
                            class="{{ request()->routeIs('admin.books.index') ? 'active' : '' }}">

                            <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path
                                    d="M4 19.5A2.5 2.5 0 006.5 22h11a2.5 2.5 0 002.5-2.5v-15A2.5 2.5 0 0017.5 2h-11A2.5 2.5 0 004 4.5v15zM6 4h12M6 8h12M6 12h12M6 16h6">

                                </path>

                            </svg>

                            <span>All Books</span>

                        </a>

                    </li>

                    <li class="{{ request()->routeIs('admin.books.create') ? 'active' : '' }}">

                        <a href="{{ route('admin.books.create') }}"
                            class="{{ request()->routeIs('admin.books.create') ? 'active' : '' }}">

                            <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path d="M12 4v16m8-8H4"></path>

                            </svg>

                            <span>Add Book</span>

                        </a>

                    </li>

                    <li class="{{ request()->routeIs('admin.borrowings.index') ? 'active' : '' }}">

                        <a href="{{ route('admin.borrowings.index') }}"
                            class="{{ request()->routeIs('admin.borrowings.index') ? 'active' : '' }}">

                            <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path
                                    d="M17 9V7a5 5 0 00-10 0v2H5a2 2 0 00-2 2v7a2 2 0 002 2h14a2 2 0 002-2v-7a2 2 0 00-2-2h-2zM7 9V7a3 3 0 016 0v2H7z">

                                </path>

                            </svg>

                            <span>Overall Borrow History</span>

                        </a>

                    </li>

                    <li class="{{ request()->routeIs('admin.borrowings.create') ? 'active' : '' }}">

                        <a href="{{ route('admin.borrowings.create') }}"
                            class="{{ request()->routeIs('admin.borrowings.create') ? 'active' : '' }}">

                            <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path d="M12 4v16m8-8H4"></path>

                            </svg>

                            <span>Borrow Book</span>

                        </a>

                    </li>

                    <li class="{{ request()->routeIs('admin.borrowings.pending-return') ? 'active' : '' }}">

                        <a href="{{ route('admin.borrowings.pending-return') }}"
                            class="{{ request()->routeIs('admin.borrowings.pending-return') ? 'active' : '' }}">

                            <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path
                                    d="M17 9V7a5 5 0 00-10 0v2H5a2 2 0 00-2 2v7a2 2 0 002 2h14a2 2 0 002-2v-7a2 2 0 00-2-2h-2zM7 9V7a3 3 0 016 0v2H7z">

                                </path>

                            </svg>

                            <span>Return Book</span>

                        </a>

                    </li>


                    <li class="{{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">

                        <a href="{{ route('admin.settings.index') }}"
                            class="{{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">

                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">

                                <path
                                    d="M12 4v1m6.364 1.636l-.707.707M18 12h1M4 12H3m1.636-6.364l.707.707M12 20v-1m-6.364-1.636l.707-.707">

                                </path>

                            </svg>

                            <span>Settings</span>

                        </a>

                    </li>

                </ul>
                <div class="mt-auto mb-4">
                    <a href="{{ route('admin.logout') }}" class="logout-link">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                            <path
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        <span>Logout</span>
                    </a>
                </div>

            </nav>

        </aside>

        <!-- Main Content Area -->
        <div class="flex flex-col overflow-y-auto flex-1 p-6 bg-gray-100">

            @yield('content')
        </div>
    </div>

    @stack('scripts')
</body>

</html>
