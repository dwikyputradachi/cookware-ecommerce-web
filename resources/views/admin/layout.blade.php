<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') — Murazon</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        :root {
            --brand-dark:   #1e3a8a;
            --brand:        #1e40af;
            --brand-light:  #3b82f6;
            --sidebar-w:    252px;
            --topbar-h:     64px;
            --bg:           #f1f5f9;
            --surface:      #ffffff;
            --border:       #e2e8f0;
            --text:         #1e293b;
            --text-muted:   #64748b;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
            margin: 0;
        }

        /* ── Sidebar ── */
        .admin-sidebar {
            background: linear-gradient(160deg, var(--brand) 0%, var(--brand-dark) 100%);
            width: var(--sidebar-w);
            min-height: 100vh;
            position: fixed;
            left: 0; top: 0;
            z-index: 40;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 22px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex; align-items: center; gap: 12px;
        }

        .sidebar-brand-icon {
            width: 38px; height: 38px;
            background: rgba(255,255,255,0.15);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; color: white;
            border: 1px solid rgba(255,255,255,0.2);
            flex-shrink: 0;
        }

        .sidebar-brand-text { line-height: 1.2; }
        .sidebar-brand-text strong { color: white; font-size: 15px; font-weight: 700; display: block; }
        .sidebar-brand-text span   { color: rgba(255,255,255,0.55); font-size: 11px; }

        .sidebar-section-label {
            padding: 20px 20px 8px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.08em;
            color: rgba(255,255,255,0.35);
            text-transform: uppercase;
        }

        .nav-item {
            display: flex; align-items: center; gap: 11px;
            padding: 11px 20px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 13.5px; font-weight: 500;
            transition: all 0.2s;
            border-left: 3px solid transparent;
            position: relative;
        }

        .nav-item .nav-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px;
            background: rgba(255,255,255,0.07);
            flex-shrink: 0;
            transition: background 0.2s;
        }

        .nav-item:hover {
            color: white;
            background: rgba(255,255,255,0.08);
            border-left-color: rgba(255,255,255,0.3);
        }

        .nav-item.active {
            color: white;
            background: rgba(255,255,255,0.12);
            border-left-color: #93c5fd;
        }

        .nav-item.active .nav-icon {
            background: rgba(255,255,255,0.18);
            color: #bfdbfe;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 16px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .admin-info {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px;
            background: rgba(255,255,255,0.08);
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .admin-avatar {
            width: 32px; height: 32px;
            background: rgba(255,255,255,0.2);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; color: white; flex-shrink: 0;
        }

        .admin-info-text strong { color: white; font-size: 12.5px; font-weight: 600; display: block; line-height: 1.3; }
        .admin-info-text span   { color: rgba(255,255,255,0.45); font-size: 11px; }

        .btn-logout {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            width: 100%;
            padding: 9px;
            background: rgba(239,68,68,0.15);
            color: #fca5a5;
            border: 1px solid rgba(239,68,68,0.25);
            border-radius: 9px;
            font-family: inherit; font-size: 13px; font-weight: 600;
            cursor: pointer; transition: all 0.2s;
        }
        .btn-logout:hover { background: rgba(239,68,68,0.25); color: #fecaca; }

        /* ── Main ── */
        .admin-main {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
        }

        .admin-topbar {
            height: var(--topbar-h);
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 32px;
            position: sticky; top: 0; z-index: 30;
        }

        .topbar-left { display: flex; align-items: center; gap: 8px; }
        .topbar-breadcrumb { font-size: 12px; color: var(--text-muted); }
        .topbar-breadcrumb span { color: var(--text); font-weight: 600; }

        .topbar-right { display: flex; align-items: center; gap: 16px; }

        .topbar-time {
            font-size: 12px;
            color: var(--text-muted);
            background: var(--bg);
            padding: 6px 12px;
            border-radius: 20px;
            border: 1px solid var(--border);
        }

        .admin-content { padding: 28px 32px; }

        /* ── Flash messages ── */
        .flash {
            display: flex; align-items: center; gap: 12px;
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 13.5px; font-weight: 500;
        }
        .flash.success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }
        .flash.error   { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; }

        /* ── Stat card (shared) ── */
        .stat-card {
            background: var(--surface);
            border-radius: 14px;
            padding: 22px;
            border: 1px solid var(--border);
            display: flex; align-items: center; gap: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; flex-shrink: 0;
        }
        .stat-icon.blue   { background: #dbeafe; color: var(--brand); }
        .stat-icon.green  { background: #dcfce7; color: #16a34a; }
        .stat-icon.orange { background: #ffedd5; color: #ea580c; }
        .stat-icon.red    { background: #fef2f2; color: #dc2626; }
        .stat-icon.purple { background: #f3e8ff; color: #9333ea; }

        .stat-label { font-size: 12px; color: var(--text-muted); font-weight: 500; margin-bottom: 3px; }
        .stat-value { font-size: 22px; font-weight: 800; color: var(--text); line-height: 1; }
    </style>
</head>
<body>

    <div class="admin-sidebar">
        {{-- Brand --}}
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon"><i class="fas fa-shield-halved"></i></div>
            <div class="sidebar-brand-text">
                <strong>Murazon</strong>
                <span>Admin Panel</span>
            </div>
        </div>

        {{-- Nav --}}
        <div class="sidebar-section-label">Menu Utama</div>
        <nav>
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-chart-line"></i></div>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.products.index') }}" class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-box"></i></div>
                <span>Kelola Produk</span>
            </a>
            <a href="{{ route('admin.orders.index') }}" class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-clipboard-check"></i></div>
                <span>Verifikasi Pesanan</span>
            </a>
        </nav>

        <div class="sidebar-section-label">Lainnya</div>
        <nav>
            <a href="/" class="nav-item" target="_blank">
                <div class="nav-icon"><i class="fas fa-store"></i></div>
                <span>Lihat Toko</span>
            </a>
        </nav>

        {{-- Footer --}}
        <div class="sidebar-footer">
            <div class="admin-info">
                <div class="admin-avatar"><i class="fas fa-user"></i></div>
                <div class="admin-info-text">
                    <strong>{{ auth('admin')->user()->name ?? 'Admin' }}</strong>
                    <span>Administrator</span>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-right-from-bracket"></i> Keluar
                </button>
            </form>
        </div>
    </div>

    <div class="admin-main">
        <div class="admin-topbar">
            <div class="topbar-left">
                <span class="topbar-breadcrumb">Admin / <span>@yield('page-title', 'Dashboard')</span></span>
            </div>
            <div class="topbar-right">
                <div class="topbar-time" id="clock"></div>
            </div>
        </div>

        <div class="admin-content">
            @if ($message = Session::get('success'))
                <div class="flash success">
                    <i class="fas fa-check-circle"></i> {{ $message }}
                </div>
            @endif
            @if ($message = Session::get('error'))
                <div class="flash error">
                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            document.getElementById('clock').textContent = now.toLocaleString('id-ID', {
                weekday: 'short', day: 'numeric', month: 'short',
                hour: '2-digit', minute: '2-digit'
            });
        }
        updateClock();
        setInterval(updateClock, 1000);
    </script>
</body>
</html>