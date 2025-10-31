<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'User Page')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        /* General Styles */
        body {
            font-family: 'Inter', sans-serif;
            padding-top: 100px;
            background-color: #ffffff;
            color: #333;
        }

        /* Header */
        .header {
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            height: 80px;
            padding: 0 20px;
            transition: all 0.3s ease-in-out;
        }

        /* Logo */
        .logo-text {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin: 0;
        }

        .text-primary {
            color: #007bff !important;
        }

        /* Navigation Menu */
        .navmenu {
            display: flex;
            align-items: center;
        }

        .navmenu ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
        }

        .navmenu li {
            margin: 0 15px;
        }

        .navmenu a {
            display: block;
            padding: 10px 0;
            color: #555;
            font-weight: 600;
            position: relative;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        /* Navigation Underline Effect */
        .navmenu a::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -5px;
            width: 0;
            height: 2px;
            background: #007bff;
            transition: width 0.3s ease;
        }

        .navmenu a:hover::after,
        .navmenu a.active::after {
            width: 100%;
        }

        .navmenu a:hover,
        .navmenu a.active {
            color: #007bff;
        }

        /* Dropdown Toggle Hover Effect */
        .navmenu .dropdown-toggle:hover {
            color: #007bff;
        }

        .navmenu .dropdown-toggle:hover::after {
            width: 100%;
        }

        /* Main Content */
        #main-content {
            padding-top: 20px;
            min-height: calc(100vh - 80px);
        }

        /* Mobile Navigation */
        .mobile-nav-toggle {
            color: #333;
            font-size: 28px;
            cursor: pointer;
            line-height: 0;
        }

        .navmenu.mobile-nav-active {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: rgba(255, 255, 255, 0.9);
            z-index: 9990;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .navmenu.mobile-nav-active ul {
            flex-direction: column;
        }

        .navmenu.mobile-nav-active li {
            margin: 15px 0;
        }

        .navmenu.mobile-nav-active a {
            font-size: 24px;
        }

        /* Updated Dropdown Styles */
        .dropdown-menu {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 10px 0;
            min-width: 220px;
            /* Hide the dropdown by default using visibility and opacity */
            visibility: hidden;
            opacity: 0;
            pointer-events: none;
            /* Disables interaction with the hidden element */
            transition: visibility 0s 0.2s, opacity 0.2s ease-in-out;
        }

        /* This rule makes the dropdown visible and applies the animation */
        .dropdown-menu.show {
            visibility: visible;
            opacity: 1;
            pointer-events: auto;
            transition: visibility 0s, opacity 0.2s ease-in-out;
        }

        .dropdown-item {
            padding: 10px 20px;
            font-weight: 500;
            color: #333;
            transition: all 0.2s ease;
        }
/* Update the active and hover state */
.dropdown-item:hover {
    background-color: #f1f5f9; /* Retain the hover color */
    color: #333; /* Use a darker color for readability on hover */
}

/* Set the active background to white */
.dropdown-item.active,
.dropdown-item:active {
    background-color: #fff !important;
    color: #333 !important; /* A darker color for better contrast on white */
}

        .dropdown-divider {
            margin: 8px 0;
        }

        /* Mobile Nav Dropdown Fixes */
        .navmenu.mobile-nav-active .dropdown-menu {
            position: static;
            background: transparent;
            box-shadow: none;
            text-align: center;
            padding: 0;
            margin-top: 10px;
            /* Reset for mobile view */
            visibility: visible;
            opacity: 1;
            pointer-events: auto;
        }

        .navmenu.mobile-nav-active .dropdown-item {
            font-size: 18px;
            padding: 8px 0;
        }

        /* Animation Keyframes */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">
            <div class="logo d-flex align-items-center me-auto me-lg-0">
                <a href="{{ route('user.homepage') }}">
                    <h1 class="logo-text">UCSICONIC<span class="text-primary">Library</span></h1>
                </a>
            </div>

            <nav id="navmenu" class="navmenu">
                <ul class="d-flex mb-0 list-unstyled">
                    <li><a href="{{ route('user.homepage') }}" class="{{ request()->routeIs('user.homepage') ? 'active' : '' }}">Home</a></li>
                    <li><a href="{{ route('user.books.index') }}" class="{{ request()->routeIs('user.books.index') ? 'active' : '' }}">Books</a></li>
                    <li><a href="{{ route('user.borrowed') }}" class="{{ request()->routeIs('user.borrowed') ? 'active' : '' }}" >My Borrowed Books</a></li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle {{ request()->routeIs([ 'user.profile']) ? 'active' : '' }}" data-bs-toggle="dropdown" aria-expanded="false">
                            My Account <i class="bi bi-chevron-down ms-1"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item {{ request()->routeIs('user.profile') ? 'active' : '' }}" href="{{ route('user.profile') }}">My Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
                        </ul>
                    </li>
                </ul>

                <i class="mobile-nav-toggle d-xl-none bi bi-list" style="cursor:pointer;"></i>
            </nav>
        </div>
    </header>

    <main id="main-content" class="container ">
        @yield('content')
            @yield('scripts')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // This script is specifically for the mobile navigation toggle and does not conflict with the dropdown.
        const mobileNavToggle = document.querySelector('.mobile-nav-toggle');
        const navmenu = document.querySelector('#navmenu');

        mobileNavToggle.addEventListener('click', () => {
            navmenu.classList.toggle('mobile-nav-active');
        });
    </script>
</body>

</html>
