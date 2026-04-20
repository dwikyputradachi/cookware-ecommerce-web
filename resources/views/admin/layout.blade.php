<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            -webkit-font-smoothing: antialiased; 
            -moz-osx-font-smoothing: grayscale;
        }
        
        .admin-sidebar {
            background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            z-index: 40;
        }

        .admin-main {
            margin-left: 250px;
            min-height: 100vh;
            background: #f8fafc;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
        }

        .nav-item:hover,
        .nav-item.active {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            border-left: 3px solid #60a5fa;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-icon.blue {
            background: #dbeafe;
            color: #1e40af;
        }

        .stat-icon.green {
            background: #dcfce7;
            color: #16a34a;
        }

        .stat-icon.orange {
            background: #fed7aa;
            color: #ea580c;
        }

        .stat-icon.red {
            background: #fecaca;
            color: #dc2626;
        }
    </style>
</head>
<body class="bg-[#F8FAFC] text-[#1E293B]">

    {{-- Sidebar --}}
    <div class="admin-sidebar">
        <div class="p-6 border-b border-blue-400/30">
            <h1 class="text-white text-xl font-bold">Admin Panel</h1>
            <p class="text-blue-200 text-xs mt-1">Santo Cookware</p>
        </div>

        <nav class="mt-6">
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.products.index') }}" class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="fas fa-box"></i>
                <span>Kelola Produk</span>
            </a>

            <a href="/" class="nav-item">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Toko</span>
            </a>
        </nav>
    </div>

    {{-- Main Content --}}
    <div class="admin-main">
        <div class="top-0 z-30 bg-white border-b border-gray-200 shadow-sm">
            <div class="px-8 py-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                <div class="text-sm text-gray-600">
                    <i class="fas fa-user-circle mr-2"></i>Admin
                </div>
            </div>
        </div>

        <div class="p-8">
            {{-- Flash Messages --}}
            @if ($message = Session::get('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-3">
                    <i class="fas fa-check-circle text-green-600"></i>
                    <span class="text-green-800">{{ $message }}</span>
                </div>
            @endif

            @if ($message = Session::get('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-center gap-3">
                    <i class="fas fa-exclamation-circle text-red-600"></i>
                    <span class="text-red-800">{{ $message }}</span>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

</body>
</html>
