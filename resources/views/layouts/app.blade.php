<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Batabi Lebu Dashboard')</title>
    <!-- Modern Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Boxicons for rich UI icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        /* PREMIUM VANILLA CSS SYSTEM */
        :root {
            --primary: #10b981;
            --primary-light: #34d399;
            --primary-dark: #059669;
            --accent: #f59e0b;
            --bg-color: #f8fafc;
            --surface: #ffffff;
            --surface-hover: #f1f5f9;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.3);
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1);
            
            --sidebar-width: 280px;
            --radius-md: 12px;
            --radius-lg: 20px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        [data-theme="dark"] {
            --bg-color: #0f172a;
            --surface: #1e293b;
            --surface-hover: #334155;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border: #334155;
            --glass-bg: rgba(30, 41, 59, 0.7);
            --glass-border: rgba(255, 255, 255, 0.05);
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.5);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.5);
            --shadow-lg: 0 20px 25px -5px rgba(0,0,0,0.5);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            line-height: 1.6;
            transition: var(--transition);
            min-height: 100vh;
        }

        /* LAYOUT STRUCTURE */
        .app-layout {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 100;
            transition: var(--transition);
        }

        .sidebar-header {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
            border-bottom: 1px solid var(--border);
        }

        .sidebar-menu {
            padding: 1.5rem 1rem;
            flex-grow: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .menu-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            color: var(--text-muted);
            margin: 1rem 0 0.5rem 1rem;
            letter-spacing: 1px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.85rem 1.2rem;
            border-radius: var(--radius-md);
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 600;
            font-size: 1.05rem;
            transition: var(--transition);
        }

        .sidebar-link i { font-size: 1.4rem; }

        .sidebar-link:hover {
            color: var(--primary);
            background: var(--surface-hover);
            transform: translateX(5px);
        }

        .sidebar-link.active {
            color: white;
            background: var(--primary);
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
        }

        .sidebar-footer {
            padding: 1.5rem;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* MAIN CONTENT AREA */
        .main-content {
            @if(!request()->routeIs('login') && !request()->routeIs('register'))
            margin-left: var(--sidebar-width);
            @endif
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            /* Animated background gradient */
            background-image: 
                radial-gradient(at 0% 0%, hsla(152,70%,85%,0.3) 0, transparent 50%), 
                radial-gradient(at 100% 100%, hsla(38,90%,85%,0.3) 0, transparent 50%);
            background-attachment: fixed;
        }

        [data-theme="dark"] .main-content {
            background-image: 
                radial-gradient(at 0% 0%, hsla(152,30%,15%,0.5) 0, transparent 50%), 
                radial-gradient(at 100% 100%, hsla(38,30%,15%,0.5) 0, transparent 50%);
        }

        .topbar {
            height: 70px;
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--glass-border);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .content-container {
            padding: 2rem;
            flex-grow: 1;
        }

        .theme-toggle {
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 1.5rem;
            cursor: pointer;
            transition: var(--transition);
            margin-right: 1.5rem;
        }
        .theme-toggle:hover { color: var(--text-main); transform: scale(1.1); }

        /* CONTAINERS & UTILS */
        .glass-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 2rem;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .glass-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }

        /* BUTTONS */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius-md);
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: var(--transition);
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
            box-shadow: 0 4px 14px 0 rgba(16, 185, 129, 0.39);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .btn-outline {
            background-color: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        .btn-outline:hover { background-color: var(--primary); color: white; }

        .btn-danger {
            background-color: #ef4444;
            color: white;
        }
        .btn-danger:hover { background-color: #dc2626; }

        /* METRICS GRID */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .metric-card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
            transition: var(--transition);
        }

        .metric-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-md); }

        .metric-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(16, 185, 129, 0.1);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }

        .metric-info h4 { color: var(--text-muted); font-weight: 500; font-size: 1rem; }
        .metric-info h2 { font-size: 2rem; color: var(--text-main); font-weight: 800; line-height: 1; margin-top: 0.2rem; }

        /* TABLES */
        .table-responsive { width: 100%; overflow-x: auto; }
        .table { width: 100%; border-collapse: collapse; font-family: 'Inter', sans-serif; font-size: 0.95rem; }
        .table th { text-align: left; padding: 1rem; color: var(--text-muted); border-bottom: 2px solid var(--border); font-weight: 600; }
        .table td { padding: 1.25rem 1rem; border-bottom: 1px solid var(--border); color: var(--text-main); vertical-align: middle; }
        .table tr:hover td { background-color: var(--surface-hover); }

        /* BADGES */
        .badge { padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600; display: inline-block; }
        .badge-success { background: rgba(16, 185, 129, 0.15); color: #059669; }
        .badge-warning { background: rgba(245, 158, 11, 0.15); color: #b45309; }
        .badge-danger { background: rgba(239, 68, 68, 0.15); color: #b91c1c; }
        [data-theme="dark"] .badge-success { color: #34d399; }
        [data-theme="dark"] .badge-warning { color: #fbbf24; }
        [data-theme="dark"] .badge-danger { color: #f87171; }

        /* FORMS */
        .form-group { margin-bottom: 1.5rem; }
        .form-label { display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main); }
        .form-control {
            width: 100%; padding: 0.75rem 1rem; border-radius: var(--radius-md); border: 1px solid var(--border);
            background: var(--surface); color: var(--text-main); font-family: inherit; font-size: 1rem;
            transition: var(--transition);
        }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2); }
        select.form-control { cursor: pointer; }

        .alert {
            padding: 1rem 1.5rem; border-radius: var(--radius-md); margin-bottom: 2rem; font-weight: 500;
            animation: slideIn 0.3s ease-out;
        }
        .alert-success { background: rgba(16, 185, 129, 0.1); border-left: 4px solid var(--primary); color: #059669; }
        [data-theme="dark"] .alert-success { color: #34d399; }

        @keyframes slideIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

<div class="app-layout">
    @if(!request()->routeIs('login') && !request()->routeIs('register'))
    <!-- LEFT SIDEBAR -->
    <aside class="sidebar">
        <a href="/" class="sidebar-header" style="text-decoration: none;">
            <i class='bx bx-leaf'></i> Batabi Lebu
        </a>
        
        <div class="sidebar-menu">
            @if(request()->is('farmer/*'))
                <!-- FARMER NAVIGATION -->
                <div class="menu-label">Farm Central</div>
                <a href="{{ route('farmer.dashboard') }}" class="sidebar-link {{ request()->routeIs('farmer.dashboard') ? 'active' : '' }}">
                    <i class='bx bx-grid-alt'></i> Dashboard
                </a>
                <a href="{{ route('farmer.crops.index') }}" class="sidebar-link {{ request()->routeIs('farmer.crops.*') ? 'active' : '' }}">
                    <i class='bx bx-layer'></i> Manage Crops
                </a>
                <a href="{{ route('farmer.orders.index') }}" class="sidebar-link {{ request()->routeIs('farmer.orders.*') ? 'active' : '' }}">
                    <i class='bx bx-notepad'></i> Incoming Orders
                </a>

                <div class="menu-label">Support & Resources</div>
                <a href="#" onclick="event.preventDefault();" class="sidebar-link" style="opacity: 0.6; cursor: not-allowed;" title="Coming Soon">
                    <i class='bx bx-shopping-bag'></i> Buy Supplies <span class="badge" style="background:var(--border); font-size:0.6rem; padding: 0.1rem 0.4rem;">Soon</span>
                </a>
                <a href="#" onclick="event.preventDefault();" class="sidebar-link" style="opacity: 0.6; cursor: not-allowed;" title="Coming Soon">
                    <i class='bx bx-cloud-lightning'></i> Weather Alerts <span class="badge" style="background:var(--border); font-size:0.6rem; padding: 0.1rem 0.4rem;">Soon</span>
                </a>
                <a href="#" onclick="event.preventDefault();" class="sidebar-link" style="opacity: 0.6; cursor: not-allowed;" title="Coming Soon">
                    <i class='bx bx-shield-plus'></i> Emergency Support <span class="badge" style="background:var(--border); font-size:0.6rem; padding: 0.1rem 0.4rem;">Soon</span>
                </a>
            @else
                <!-- BUYER NAVIGATION -->
                <div class="menu-label">Buyer Portal</div>
                <a href="{{ route('buyer.dashboard') }}" class="sidebar-link {{ request()->routeIs('buyer.dashboard') ? 'active' : '' }}">
                    <i class='bx bx-grid-alt'></i> Dashboard
                </a>
                <a href="{{ route('buyer.crops.index') }}" class="sidebar-link {{ request()->routeIs('buyer.crops.*') ? 'active' : '' }}">
                    <i class='bx bx-store-alt'></i> Browse Market
                </a>
                <a href="{{ route('buyer.orders.index') }}" class="sidebar-link {{ request()->routeIs('buyer.orders.*') ? 'active' : '' }}">
                    <i class='bx bx-history'></i> Order History
                </a>
            @endif
        </div>

        <div class="sidebar-footer">
             @auth
             <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 35px; height: 35px; border-radius: 50%; background: var(--primary-light); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <div style="font-weight: 600; font-size: 0.9rem;">{{ Auth::user()->name }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 500;">
                        @if(request()->is('farmer/*') && Auth::user()->farmer)
                            Verified Farmer (ID: {{ Auth::user()->farmer->id }})
                        @else
                            Verified Buyer
                        @endif
                    </div>
                </div>
             </div>
             @endauth
        </div>
    </aside>
    @endif

    <!-- RIGHT MAIN CONTENT -->
    <main class="main-content">
        <!-- TOPBAR -->
        <header class="topbar">
            <button class="theme-toggle" onclick="toggleTheme()" id="themeIcon">
                <i class='bx bx-moon'></i>
            </button>
            @auth
                <a href="{{ route('logout') }}" class="btn btn-outline" style="padding: 0.4rem 1rem; font-size: 0.85rem;"><i class='bx bx-log-out'></i> Logout</a>
            @endauth
        </header>

        <!-- PAGE CONTENT -->
        <div class="content-container">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class='bx bx-check-circle' style="vertical-align: middle; font-size: 1.2rem; margin-right: 5px;"></i>
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

<script>
    const html = document.documentElement;
    const icon = document.querySelector('#themeIcon i');

    if (localStorage.getItem('theme') === 'dark' || 
       (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        html.setAttribute('data-theme', 'dark');
        icon.className = 'bx bx-sun';
    }

    function toggleTheme() {
        if (html.getAttribute('data-theme') === 'light') {
            html.setAttribute('data-theme', 'dark');
            icon.className = 'bx bx-sun';
            localStorage.setItem('theme', 'dark');
        } else {
            html.setAttribute('data-theme', 'light');
            icon.className = 'bx bx-moon';
            localStorage.setItem('theme', 'light');
        }
    }
</script>
</body>
</html>
