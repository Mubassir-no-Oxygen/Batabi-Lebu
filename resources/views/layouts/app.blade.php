<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Batabi Lebu – Bangladesh's direct farmer-to-buyer agricultural marketplace. Buy fresh crops directly from verified farmers.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Batabi Lebu') | Fresh From the Farm</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

    <style>
        :root {
            --green-dark:  #1a5c2e;
            --green-mid:   #2d8a4e;
            --green-light: #4caf73;
            --green-pale:  #e8f5ec;
            --orange:      #e8890a;
            --orange-pale: #fff3dc;
            --text-dark:   #1a1a2e;
            --text-muted:  #6c757d;
            --card-shadow: 0 4px 20px rgba(0,0,0,.08);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8faf9;
            color: var(--text-dark);
        }

        /* ─── Navbar ─── */
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: var(--green-dark) !important;
        }
        .navbar-brand span { color: var(--orange); }
        .navbar { background: #fff; border-bottom: 2px solid var(--green-pale); }
        .nav-link { font-weight: 500; color: var(--text-dark) !important; transition: color .2s; }
        .nav-link:hover, .nav-link.active { color: var(--green-mid) !important; }
        .btn-primary-custom {
            background: var(--green-mid);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            padding: .45rem 1.2rem;
            transition: background .2s, transform .2s;
        }
        .btn-primary-custom:hover { background: var(--green-dark); transform: translateY(-1px); }
        .btn-outline-custom {
            border: 2px solid var(--green-mid);
            color: var(--green-mid);
            border-radius: 8px;
            font-weight: 600;
            padding: .45rem 1.2rem;
            background: transparent;
            transition: all .2s;
        }
        .btn-outline-custom:hover { background: var(--green-mid); color: #fff; }

        /* ─── Cards ─── */
        .card {
            border: none;
            border-radius: 14px;
            box-shadow: var(--card-shadow);
        }
        .card-header-green {
            background: linear-gradient(135deg, var(--green-dark), var(--green-mid));
            color: #fff;
            border-radius: 14px 14px 0 0 !important;
            padding: 1rem 1.5rem;
        }

        /* ─── Badges ─── */
        .badge-pending  { background: #fff3cd; color: #856404; border: 1px solid #ffe083; }
        .badge-approved { background: #d1f7e0; color: #0a5c2e; border: 1px solid #6de09e; }
        .badge-rejected { background: #fde8e8; color: #7d1111; border: 1px solid #f5b4b4; }
        .badge-accepted { background: #d1eeff; color: #0a4080; border: 1px solid #87c7f5; }
        .badge-completed{ background: #ede8ff; color: #3d1fa5; border: 1px solid #b49ef5; }

        /* ─── Stat Cards ─── */
        .stat-card {
            border-radius: 14px;
            padding: 1.5rem;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        .stat-card::after {
            content: '';
            position: absolute;
            right: -20px; top: -20px;
            width: 100px; height: 100px;
            border-radius: 50%;
            background: rgba(255,255,255,.12);
        }
        .stat-card .stat-icon { font-size: 2.5rem; opacity: .9; }
        .stat-card .stat-value { font-size: 2rem; font-weight: 700; }
        .stat-card .stat-label { font-size: .85rem; opacity: .85; }
        .stat-green  { background: linear-gradient(135deg, #1a5c2e, #2d8a4e); }
        .stat-orange { background: linear-gradient(135deg, #c17200, #e8890a); }
        .stat-blue   { background: linear-gradient(135deg, #0a4080, #1a7fd4); }
        .stat-purple { background: linear-gradient(135deg, #4a1080, #8e44ad); }

        /* ─── Crop Cards ─── */
        .crop-card { transition: transform .25s, box-shadow .25s; cursor: pointer; }
        .crop-card:hover { transform: translateY(-5px); box-shadow: 0 12px 30px rgba(0,0,0,.12); }
        .crop-card img { height: 180px; object-fit: cover; border-radius: 14px 14px 0 0; }
        .crop-placeholder {
            height: 180px;
            background: linear-gradient(135deg, var(--green-pale), #c8ebd4);
            display: flex; align-items: center; justify-content: center;
            border-radius: 14px 14px 0 0;
            font-size: 4rem; color: var(--green-mid);
        }

        /* ─── Tables ─── */
        .table th { background: var(--green-pale); color: var(--green-dark); font-weight: 600; border: none; }
        .table td { vertical-align: middle; }
        .table-hover tbody tr:hover { background: var(--green-pale); }

        /* ─── Alerts ─── */
        .alert { border-radius: 10px; border: none; }
        .alert-success { background: #d1f7e0; color: #0a5c2e; }
        .alert-danger  { background: #fde8e8; color: #7d1111; }
        .alert-info    { background: #d1eeff; color: #0a4080; }
        .alert-warning { background: #fff3cd; color: #856404; }

        /* ─── Sidebar ─── */
        .sidebar { background: #fff; border-right: 2px solid var(--green-pale); min-height: calc(100vh - 60px); }
        .sidebar .nav-link { color: var(--text-dark); border-radius: 8px; margin-bottom: 4px; padding: .6rem 1rem; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: var(--green-pale);
            color: var(--green-dark);
        }
        .sidebar .nav-link i { margin-right: 8px; }

        /* ─── Footer ─── */
        footer { background: var(--green-dark); color: #c8ebd4; padding: 2.5rem 0 1rem; margin-top: 4rem; }
        footer a { color: #8fd4a8; text-decoration: none; }
        footer a:hover { color: #fff; }

        /* ─── Utilities ─── */
        .section-title { font-family: 'Playfair Display', serif; color: var(--green-dark); }
        .text-green { color: var(--green-mid); }
        .bg-green-pale { background: var(--green-pale); }
        .rounded-xl { border-radius: 14px !important; }
        .form-control, .form-select {
            border-radius: 8px;
            border: 1.5px solid #d4e8da;
            padding: .55rem .9rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--green-mid);
            box-shadow: 0 0 0 3px rgba(45,138,78,.15);
        }
        .btn { border-radius: 8px; font-weight: 600; }
        .btn-success { background: var(--green-mid); border: none; }
        .btn-success:hover { background: var(--green-dark); }
    </style>

    @stack('styles')
</head>
<body>

<!-- ─── Navbar ─── -->
<nav class="navbar navbar-expand-lg sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            🍊 Batabi <span>Lebu</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="bi bi-house"></i> Home
                    </a>
                </li>

                @auth
                    @if(auth()->user()->isFarmer())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('farmer.*') ? 'active' : '' }}" href="{{ route('farmer.dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('farmer.crops.index') }}">
                                <i class="bi bi-flower1"></i> My Crops
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('farmer.orders.index') }}">
                                <i class="bi bi-bag-check"></i> Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('farmer.reviews.*') ? 'active' : '' }}" href="{{ route('farmer.reviews.index') }}">
                                <i class="bi bi-star"></i> My Reviews
                            </a>
                        </li>
                    @elseif(auth()->user()->isBuyer())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('buyer.*') ? 'active' : '' }}" href="{{ route('buyer.dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('buyer.crops.index') }}">
                                <i class="bi bi-search"></i> Browse Crops
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('buyer.orders.index') }}">
                                <i class="bi bi-bag"></i> My Orders
                            </a>
                        </li>
                    @elseif(auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-shield-check"></i> Admin Panel
                            </a>
                        </li>
                    @endif

                    <li class="nav-item me-2">
                        <a class="nav-link position-relative {{ request()->routeIs('notifications.*') ? 'active' : '' }}" href="{{ route('notifications.index') }}" title="Notifications">
                            <i class="bi bi-bell fs-5"></i>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="position-absolute translate-middle badge rounded-pill bg-danger" style="top: 8px; right: -15px; font-size: 0.6rem;">
                                    {{ auth()->user()->unreadNotifications->count() > 99 ? '99+' : auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="userDropdown">
                            <i class="bi bi-person-circle"></i> {{ Str::limit(auth()->user()->name, 15) }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><span class="dropdown-item-text text-muted small">{{ auth()->user()->role }}</span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger" type="submit" id="logout-btn">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="btn btn-outline-custom" href="{{ route('login') }}" id="nav-login-btn">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary-custom" href="{{ route('register') }}" id="nav-register-btn">Register</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- ─── Flash Messages ─── -->
<div class="container mt-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>

<!-- ─── Main Content ─── -->
<main>
    @yield('content')
</main>

<!-- ─── Footer ─── -->
<footer>
    <div class="container">
        <div class="row g-4 mb-3">
            <div class="col-md-4">
                <h5 class="text-white fw-bold mb-2">🍊 Batabi Lebu</h5>
                <p class="small">Bangladesh's trusted direct farmer-to-buyer agricultural marketplace. Fresh from the farm, delivered to you.</p>
            </div>
            <div class="col-md-4">
                <h6 class="text-white fw-semibold mb-2">Quick Links</h6>
                <ul class="list-unstyled small">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('register') }}">Register as Farmer</a></li>
                    <li><a href="{{ route('register') }}">Register as Buyer</a></li>
                    <li><a href="{{ route('login') }}">Login</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="text-white fw-semibold mb-2">Contact</h6>
                <p class="small">
                    <i class="bi bi-envelope me-1"></i> support@batabi-lebu.com<br>
                    <i class="bi bi-telephone me-1"></i> +880 1700-000000
                </p>
            </div>
        </div>
        <hr style="border-color: rgba(255,255,255,.2)">
        <p class="text-center small mb-0">&copy; {{ date('Y') }} Batabi Lebu. All rights reserved.</p>
    </div>
</footer>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')
</body>
</html>
