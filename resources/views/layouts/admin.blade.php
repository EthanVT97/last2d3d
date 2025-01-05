<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background: #212529;
            color: #fff;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,.75);
            padding: 1rem;
            font-size: 0.9rem;
        }
        .sidebar .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,.1);
        }
        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,.1);
        }
        .sidebar .nav-link i {
            margin-right: 0.5rem;
            width: 20px;
            text-align: center;
        }
        .content {
            padding: 2rem;
            width: 100%;
        }
        .navbar {
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,.04);
        }
        .sb-sidenav-menu-heading {
            padding: 1rem;
            font-size: 0.75rem;
            font-weight: bold;
            text-transform: uppercase;
            color: rgba(255,255,255,.4);
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar" style="width: 250px;">
            <div class="p-3 d-flex align-items-center">
                <i class="fas fa-user-shield me-2"></i>
                <h5 class="text-white mb-0">Admin Panel</h5>
            </div>
            <ul class="nav flex-column">
                <!-- Dashboard Section -->
                <div class="sb-sidenav-menu-heading">Core</div>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>

                <!-- Lottery Management Section -->
                <div class="sb-sidenav-menu-heading">Lottery Management</div>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                        <i class="fas fa-cog"></i> Lottery Settings
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.plays.*') ? 'active' : '' }}" href="{{ route('admin.plays.index') }}">
                        <i class="fas fa-gamepad"></i> Plays
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.results.*') ? 'active' : '' }}" href="{{ route('admin.results.index') }}">
                        <i class="fas fa-trophy"></i> Results
                    </a>
                </li>

                <!-- User Management Section -->
                <div class="sb-sidenav-menu-heading">User Management</div>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <i class="fas fa-users"></i> Users
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.agents.*') ? 'active' : '' }}" href="{{ route('admin.agents.index') }}">
                        <i class="fas fa-user-tie"></i> Agents
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.deposit-accounts.*') ? 'active' : '' }}" href="{{ route('admin.deposit-accounts.index') }}">
                        <i class="fas fa-wallet"></i> Deposit Accounts
                    </a>
                </li>

                <!-- Financial Management -->
                <div class="sb-sidenav-menu-heading">Financial</div>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}" href="{{ route('admin.transactions.index') }}">
                        <i class="fas fa-exchange-alt"></i> ငွေလွှဲမှုများ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.reports.transactions') ? 'active' : '' }}" href="{{ route('admin.reports.transactions') }}">
                        <i class="fas fa-history"></i> ငွေလွှဲမှု မှတ်တမ်း
                    </a>
                </li>

                <!-- Reports Section -->
                <div class="sb-sidenav-menu-heading">Reports</div>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.reports.profit') ? 'active' : '' }}" href="{{ route('admin.reports.profit') }}">
                        <i class="fas fa-chart-line"></i> Profit Report
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.reports.expense') ? 'active' : '' }}" href="{{ route('admin.reports.expense') }}">
                        <i class="fas fa-file-invoice-dollar"></i> Expense Report
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="content">
            <!-- Top Navigation -->
            <nav class="navbar navbar-expand-lg navbar-light mb-4">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between w-100">
                        <h4 class="mb-0">@yield('title', 'Dashboard')</h4>
                        <div class="dropdown">
                            <button class="btn btn-link dropdown-toggle text-dark text-decoration-none" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.profile') }}">
                                        <i class="fas fa-user-cog me-2"></i> Profile
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
