<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin — DQIN AC')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.11/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.7.0/css/select.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary: #164841;
            --primary-dark: #0f332e;
            --sidebar-bg: #1a1a2e;
            --sidebar-w: 250px;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', Inter, sans-serif; background: #f5f5f5; color: #333; margin: 0; }

        .sidebar {
            position: fixed; top: 0; left: 0; height: 100vh; width: var(--sidebar-w);
            background: var(--sidebar-bg); color: #fff; z-index: 1000;
            transition: transform .3s; overflow-y: auto;
        }
        .sidebar.hidden { transform: translateX(-100%); }
        .sidebar-brand {
            padding: 20px 16px; background: var(--primary);
            font-size: 1.1rem; font-weight: 700; display: flex;
            align-items: center; gap: 10px;
        }
        .sidebar-brand .brand-icon {
            width: 32px; height: 32px; background: rgba(255,255,255,.2);
            border-radius: 8px; display: flex; align-items: center;
            justify-content: center; font-weight: 800; font-size: .85rem;
        }
        .sidebar-menu { padding: 12px 0; }
        .menu-label {
            padding: 8px 16px 4px; font-size: .7rem;
            text-transform: uppercase; color: #888; letter-spacing: 1px;
        }
        .menu-item a {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 16px; color: #ccc; text-decoration: none;
            transition: all .2s; font-size: .875rem;
        }
        .menu-item a:hover, .menu-item a.active {
            background: rgba(255,255,255,.1); color: #fff;
            border-left: 3px solid var(--primary);
        }
        .menu-item a i { width: 18px; text-align: center; font-size: .9rem; }

        .topbar {
            position: fixed; top: 0; left: var(--sidebar-w); right: 0;
            height: 60px; background: #fff; box-shadow: 0 1px 4px rgba(0,0,0,.08);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 24px; z-index: 999; transition: left .3s;
        }
        .topbar.full { left: 0; }
        .topbar-left { display: flex; align-items: center; gap: 12px; }
        .topbar-left h6 { margin: 0; font-size: 1rem; font-weight: 600; color: #333; }
        .btn-toggle {
            background: none; border: none; font-size: 1.2rem;
            cursor: pointer; color: #555; padding: 4px 8px;
        }
        .topbar-right { display: flex; align-items: center; gap: 16px; }
        .user-info { font-size: .85rem; text-align: right; }
        .user-info strong { display: block; font-weight: 600; }
        .user-info span { color: #888; font-size: .75rem; }
        .btn-logout {
            background: var(--primary); color: #fff; border: none;
            padding: 6px 14px; border-radius: 6px; cursor: pointer;
            font-size: .85rem; text-decoration: none;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-logout:hover { background: var(--primary-dark); }

        .main-content {
            margin-left: var(--sidebar-w); margin-top: 60px;
            padding: 24px; min-height: calc(100vh - 60px);
            transition: margin-left .3s;
        }
        .main-content.full { margin-left: 0; }

        .card {
            background: #fff; border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,.06); overflow: hidden;
        }
        .card-header {
            padding: 16px 20px; border-bottom: 1px solid #f0f0f0;
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-header h5 { margin: 0; font-size: 1rem; font-weight: 600; color: #333; }
        .card-body { padding: 20px; }
        .p-0 { padding: 0 !important; }
        .stats-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px; margin-bottom: 24px;
        }
        .stat-card {
            background: #fff; border-radius: 12px; padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,.06);
            display: flex; align-items: center; gap: 16px;
        }
        .stat-icon {
            width: 52px; height: 52px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.35rem; color: #fff; flex-shrink: 0;
        }
        .stat-info h3 { margin: 0; font-size: 1.35rem; font-weight: 700; color: #222; line-height: 1.2; }
        .stat-info p { margin: 4px 0 0; font-size: .8rem; color: #888; }
        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: .875rem; }
        thead th {
            background: #f8f9fa; padding: 12px 16px;
            text-align: left; font-weight: 600; color: #555;
            border-bottom: 2px solid #e9ecef; white-space: nowrap;
        }
        tbody td { padding: 12px 16px; border-bottom: 1px solid #f0f0f0; color: #444; }
        tbody tr:hover { background: #fafafa; }
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px; border-radius: 8px; border: none;
            cursor: pointer; font-size: .875rem; font-weight: 500;
            text-decoration: none; transition: all .2s;
        }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-dark); }
        .btn-secondary { background: #6c757d; color: #fff; }
        .btn-danger { background: #dc3545; color: #fff; }
        .btn-sm { padding: 5px 10px; font-size: .8rem; }
        .form-group { margin-bottom: 16px; }
        .form-label { display: block; margin-bottom: 6px; font-weight: 500; font-size: .875rem; }
        .form-control {
            width: 100%; padding: 10px 12px; border: 1px solid #ddd;
            border-radius: 8px; font-size: .875rem; transition: border .2s; background: #fff;
        }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(22,72,65,.15); }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 20px; font-size: .75rem; font-weight: 600; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-info { background: #d1ecf1; color: #0c5460; }
        .badge-secondary { background: #e2e3e5; color: #383d41; }
        .dataTables_wrapper { padding: 20px; }
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #ddd; border-radius: 8px; padding: 6px 10px; font-size: .85rem;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 4px 10px; border-radius: 6px; font-size: .82rem;
            border: 1px solid #ddd !important; margin: 0 2px;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: var(--primary) !important; color: #fff !important; border-color: var(--primary) !important;
        }
        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.5); z-index: 999;
        }
        .sidebar-overlay.show { display: block; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .topbar { left: 0 !important; }
            .main-content { margin-left: 0 !important; padding: 16px; }
        }
    </style>
    @stack('styles')
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">D</div>
        <span>DQIN AC</span>
    </div>
    <nav class="sidebar-menu">
        @foreach ([
            ['label' => 'Utama', 'items' => [
                ['route' => 'admin.dashboard', 'icon' => 'fa-solid fa-gauge-high', 'name' => 'Dashboard', 'perm' => 'dashboard.view'],
            ]],
            ['label' => 'Data Master', 'items' => [
                ['route' => 'admin.customers.index', 'icon' => 'fa-solid fa-users', 'name' => 'Customers', 'perm' => 'customers.view'],
                ['route' => 'admin.customer-units.index', 'icon' => 'fa-solid fa-computer', 'name' => 'Unit AC', 'perm' => 'customer-units.view'],
                ['route' => 'admin.technicians.index', 'icon' => 'fa-solid fa-user-gear', 'name' => 'Technicians', 'perm' => 'technicians.view'],
            ]],
            ['label' => 'Operasional', 'items' => [
                ['route' => 'admin.work-orders.index', 'icon' => 'fa-solid fa-clipboard-list', 'name' => 'Work Orders', 'perm' => 'work-orders.view', 'also' => 'admin.work-orders.detail'],
                ['route' => 'admin.service-reports.index', 'icon' => 'fa-solid fa-file-lines', 'name' => 'Service Reports', 'perm' => 'service-reports.view'],
                ['route' => 'admin.complaints.index', 'icon' => 'fa-solid fa-circle-exclamation', 'name' => 'Complaints', 'perm' => 'complaints.view'],
            ]],
            ['label' => 'Inventory', 'items' => [
                ['route' => 'admin.spareparts.index', 'icon' => 'fa-solid fa-boxes-stacked', 'name' => 'Spareparts', 'perm' => 'spareparts.view'],
                ['route' => 'admin.freon-inventory.index', 'icon' => 'fa-solid fa-droplet', 'name' => 'Freon', 'perm' => 'freon.view'],
            ]],
            ['label' => 'Keuangan', 'items' => [
                ['route' => 'admin.quotations.index', 'icon' => 'fa-solid fa-file-invoice', 'name' => 'Quotations', 'perm' => 'quotations.view'],
                ['route' => 'admin.invoices.index', 'icon' => 'fa-solid fa-receipt', 'name' => 'Invoices', 'perm' => 'invoices.view'],
                ['route' => 'admin.payments.index', 'icon' => 'fa-solid fa-credit-card', 'name' => 'Payments', 'perm' => 'payments.view'],
            ]],
            ['label' => 'Pengaturan', 'items' => [
                ['route' => 'admin.maintenance-contracts.index', 'icon' => 'fa-solid fa-handshake', 'name' => 'Contracts', 'perm' => 'contracts.view'],
                ['route' => 'admin.roles.index', 'icon' => 'fa-solid fa-shield-halved', 'name' => 'Roles', 'perm' => 'roles.view'],
                ['route' => 'admin.permissions.index', 'icon' => 'fa-solid fa-key', 'name' => 'Permissions', 'perm' => 'permissions.view'],
            ]],
        ] as $group)
            <div class="menu-label">{{ $group['label'] }}</div>
            @foreach ($group['items'] as $item)
                @if(auth()->user()->hasPermission($item['perm']))
                @php $active = request()->routeIs($item['route']) || (isset($item['also']) && request()->routeIs($item['also'])); @endphp
                <div class="menu-item">
                    <a href="{{ route($item['route']) }}" class="{{ $active ? 'active' : '' }}">
                        <i class="{{ $item['icon'] }}"></i> {{ $item['name'] }}
                    </a>
                </div>
                @endif
            @endforeach
        @endforeach
    </nav>
</aside>

<header class="topbar" id="topbar">
    <div class="topbar-left">
        <button class="btn-toggle" onclick="toggleSidebar()">
            <i class="fa-solid fa-bars"></i>
        </button>
        <h6>@yield('page-title', 'Dashboard')</h6>
    </div>
    <div class="topbar-right">
        <div class="user-info">
            <strong>{{ Auth::user()->name }}</strong>
            <span>{{ ucfirst(Auth::user()->role) }}</span>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Log Out</span>
            </button>
        </form>
    </div>
</header>

<main class="main-content" id="mainContent">
    @yield('content')
</main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.11/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    let sidebarOpen = window.innerWidth > 768;

    function toggleSidebar() {
        const s = document.getElementById('sidebar');
        const o = document.getElementById('sidebarOverlay');
        const m = document.getElementById('mainContent');
        const t = document.getElementById('topbar');
        if (window.innerWidth <= 768) {
            s.classList.toggle('show');
            o.classList.toggle('show');
        } else {
            sidebarOpen = !sidebarOpen;
            s.classList.toggle('hidden', !sidebarOpen);
            m.classList.toggle('full', !sidebarOpen);
            t.classList.toggle('full', !sidebarOpen);
        }
    }

    if (window.innerWidth <= 768) {
        document.getElementById('sidebar').classList.remove('show');
    }

    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });
    @if(session('success')) Toast.fire({ icon: 'success', title: @json(session('success')) }); @endif
    @if(session('error')) Toast.fire({ icon: 'error', title: @json(session('error')) }); @endif
</script>
@stack('scripts')
</body>
</html>
