<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bintaro Hill')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --color-primary: #5e6640;
            --color-primary-hover: #4c5333;
            --color-primary-soft: #eef1e6;
            --color-bg: #f5f5f3;
            --color-card: #ffffff;
            --color-text: #222222;
            --color-muted: #777777;
            --color-border: #e1e1dc;
            --color-danger: #c0392b;
            --color-warning: #d99a25;
            --color-success: #4f7d45;
        }

        .app-shell, .app-shell * { box-sizing: border-box; }
        body { margin: 0; background: var(--color-bg); color: var(--color-text); font-family: 'Poppins', Arial, sans-serif; }
        .app-shell { min-height: 100vh; display: flex; background: var(--color-bg); }
        .app-sidebar { position: sticky; top: 0; width: 260px; height: 100vh; flex-shrink: 0; display: flex; flex-direction: column; background: #fff; border-right: 1px solid var(--color-border); padding: 22px 18px; }
        .brand { display: flex; align-items: center; gap: 12px; padding: 4px 6px 22px; border-bottom: 1px solid var(--color-border); }
        .brand img { width: 74px; height: auto; object-fit: contain; flex-shrink: 0; }
        .brand-title { font-size: 17px; font-weight: 700; color: var(--color-text); line-height: 1.2; }
        .brand-subtitle { margin-top: 3px; font-size: 12px; color: var(--color-muted); }
        .sidebar-nav { display: flex; flex-direction: column; gap: 8px; padding: 22px 0; flex: 1; }
        .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 14px; border-radius: 14px; color: #555; text-decoration: none; font-size: 14px; font-weight: 600; transition: 0.2s ease; }
        .nav-link svg { width: 19px; height: 19px; color: #858575; flex-shrink: 0; }
        .nav-link:hover { background: var(--color-primary-soft); color: var(--color-primary-hover); }
        .nav-link.active { background: var(--color-primary); color: #fff; box-shadow: 0 8px 20px rgba(94, 102, 64, 0.18); }
        .nav-link.active svg { color: #fff; }
        .sidebar-footer { border-top: 1px solid var(--color-border); padding-top: 16px; }
        .logout-button { width: 100%; border: 0; background: transparent; cursor: pointer; text-align: left; font: inherit; }
        .app-main { min-width: 0; flex: 1; display: flex; flex-direction: column; }
        .app-topbar { min-height: 82px; display: flex; align-items: center; justify-content: space-between; gap: 18px; padding: 20px 34px; background: rgba(245, 245, 243, 0.92); border-bottom: 1px solid var(--color-border); backdrop-filter: blur(8px); }
        .topbar-title h1 { margin: 0; font-size: 25px; line-height: 1.2; font-weight: 700; color: var(--color-text); }
        .topbar-title p { margin: 6px 0 0; color: var(--color-muted); font-size: 14px; }
        .user-pill { display: flex; align-items: center; gap: 10px; min-width: 0; padding: 9px 12px; border: 1px solid var(--color-border); border-radius: 999px; background: #fff; color: var(--color-text); }
        .user-avatar { width: 32px; height: 32px; border-radius: 999px; display: flex; align-items: center; justify-content: center; background: var(--color-primary-soft); color: var(--color-primary); font-weight: 700; font-size: 13px; }
        .user-name { max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 14px; font-weight: 600; }
        .app-content { width: 100%; max-width: 1240px; padding: 30px 34px 42px; }
        .page-header { display: flex; justify-content: space-between; gap: 16px; align-items: flex-start; margin-bottom: 22px; }
        .page-header h2 { margin: 0; font-size: 24px; font-weight: 700; color: var(--color-text); }
        .page-header p { margin: 7px 0 0; font-size: 14px; color: var(--color-muted); }
        .card, .table-card, .filter-card, .stat-card { background: var(--color-card); border: 1px solid var(--color-border); border-radius: 20px; box-shadow: 0 10px 28px rgba(34, 34, 34, 0.05); }
        .card, .filter-card { padding: 22px; }
        .filter-card { margin-bottom: 22px; }
        .filter-grid { display: flex; flex-wrap: wrap; align-items: end; gap: 14px; }
        .form-field { display: flex; flex-direction: column; gap: 7px; min-width: 160px; }
        .form-field label { font-size: 13px; font-weight: 600; color: var(--color-text); }
        .form-control { width: 100%; min-height: 46px; border: 1px solid var(--color-border); border-radius: 14px; background: #fff; padding: 0 14px; color: var(--color-text); font: inherit; font-size: 14px; outline: none; transition: 0.2s ease; }
        .form-control:focus { border-color: var(--color-primary); box-shadow: 0 0 0 3px rgba(94, 102, 64, 0.12); }
        .btn-primary, .btn-secondary, .btn-danger, .btn-link-soft { display: inline-flex; align-items: center; justify-content: center; min-height: 44px; padding: 0 18px; border-radius: 14px; border: 1px solid transparent; text-decoration: none; cursor: pointer; font: inherit; font-size: 14px; font-weight: 700; transition: 0.2s ease; white-space: nowrap; }
        .btn-primary { background: var(--color-primary); color: #fff; }
        .btn-primary:hover { background: var(--color-primary-hover); }
        .btn-secondary { background: #fff; color: var(--color-text); border-color: var(--color-border); }
        .btn-secondary:hover { background: #f8f8f5; }
        .btn-danger { background: #fff6f5; color: var(--color-danger); border-color: #f0c6c0; }
        .btn-link-soft { min-height: 34px; padding: 0 12px; background: var(--color-primary-soft); color: var(--color-primary); }
        .stats-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; margin-bottom: 22px; }
        .stat-card { padding: 20px; position: relative; overflow: hidden; }
        .stat-card::after { content: ''; position: absolute; right: -22px; top: -24px; width: 86px; height: 86px; border-radius: 999px; background: var(--accent-soft, var(--color-primary-soft)); }
        .stat-label { color: var(--color-muted); font-size: 13px; font-weight: 600; margin: 0; }
        .stat-value { margin: 8px 0 3px; font-size: 34px; line-height: 1; font-weight: 800; color: var(--accent, var(--color-primary)); }
        .stat-note { margin: 0; color: #9a9a92; font-size: 12px; }
        .table-card { overflow: hidden; }
        .table-header { display: flex; justify-content: space-between; align-items: center; gap: 14px; padding: 20px 22px; border-bottom: 1px solid var(--color-border); }
        .table-header h3 { margin: 0; font-size: 17px; font-weight: 700; }
        .table-header p { margin: 5px 0 0; color: var(--color-muted); font-size: 13px; }
        .table-responsive { overflow-x: auto; }
        .app-table { width: 100%; border-collapse: collapse; min-width: 760px; font-size: 14px; }
        .app-table th { background: #fafaf7; color: #68685f; text-align: left; padding: 14px 18px; font-size: 12px; letter-spacing: .03em; text-transform: uppercase; font-weight: 700; border-bottom: 1px solid var(--color-border); }
        .app-table td { padding: 16px 18px; border-bottom: 1px solid #eeeeea; color: var(--color-text); vertical-align: middle; }
        .app-table tr:hover td { background: #fbfbf8; }
        .app-table tr:last-child td { border-bottom: 0; }
        .text-muted { color: var(--color-muted); }
        .small-text { font-size: 12px; color: var(--color-muted); }
        .status-badge, .area-badge, .score-badge { display: inline-flex; align-items: center; justify-content: center; gap: 6px; border-radius: 999px; padding: 6px 10px; font-size: 12px; font-weight: 700; white-space: nowrap; }
        .status-on-time, .score-high { background: #edf5e9; color: var(--color-success); }
        .status-late, .score-mid { background: #fff4df; color: var(--color-warning); }
        .status-absent, .score-low { background: #fff0ee; color: var(--color-danger); }
        .status-permission { background: #eef3f6; color: #3d6f82; }
        .status-sick { background: #f1eef6; color: #6f5b84; }
        .status-empty, .status-holiday { background: #efefea; color: #77776f; }
        .area-badge { background: var(--color-primary-soft); color: var(--color-primary); }
        .alert { margin-bottom: 18px; padding: 14px 16px; border-radius: 16px; border: 1px solid var(--color-border); background: #fff; font-size: 14px; }
        .alert-success { border-color: #cfe2c8; background: #f2f8ef; color: var(--color-success); }
        .alert-error { border-color: #efc9c3; background: #fff5f3; color: var(--color-danger); }
        .form-card { max-width: 760px; }
        .form-actions { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 22px; }
        .field-error { margin: 7px 0 0; color: var(--color-danger); font-size: 13px; }
        @media (max-width: 900px) {
            .app-shell { display: block; }
            .app-sidebar { position: relative; width: 100%; height: auto; padding: 16px; border-right: 0; border-bottom: 1px solid var(--color-border); }
            .brand { padding-bottom: 14px; }
            .brand img { width: 58px; }
            .sidebar-nav { padding: 14px 0 0; flex-direction: row; overflow-x: auto; }
            .nav-link { flex: 0 0 auto; }
            .sidebar-footer { display: block; padding-top: 12px; margin-top: 12px; }
            .app-topbar { padding: 18px 20px; align-items: flex-start; }
            .user-pill { display: none; }
            .app-content { padding: 22px 18px 34px; max-width: 100%; }
            .app-shell-employee .app-sidebar,
            .app-shell-employee .app-topbar { display: none; }
            .app-shell-employee .app-content { padding: 16px; }
            .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 520px) {
            .stats-grid { grid-template-columns: 1fr; }
            .page-header { display: block; }
            .filter-grid { display: grid; grid-template-columns: 1fr; }
            .btn-primary, .btn-secondary { width: 100%; }
            .topbar-title h1 { font-size: 21px; }
        }
    </style>
</head>
<body>
@php
    $user = auth()->user();
    $initial = $user ? strtoupper(substr($user->name, 0, 1)) : 'A';
@endphp
<div class="app-shell {{ $user?->isEmployee() ? 'app-shell-employee' : 'app-shell-admin' }}">
    <aside class="app-sidebar">
        <div class="brand">
            <img src="{{ asset('images/logobintarohill.png') }}" alt="Bintaro Hill">
            <div>
                <div class="brand-title">Bintaro Hill</div>
                <div class="brand-subtitle">Sistem Absensi Petugas</div>
            </div>
        </div>

        <nav class="sidebar-nav" aria-label="Navigasi utama">
            @if($user?->isEmployee())
            <a href="{{ route('my-attendance.index') }}" class="nav-link {{ request()->routeIs('my-attendance.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3.75M16 7V3.75M4.75 9.25h14.5M6.5 5.75h11A1.75 1.75 0 0119.25 7.5v10.25A1.75 1.75 0 0117.5 19.5h-11a1.75 1.75 0 01-1.75-1.75V7.5A1.75 1.75 0 016.5 5.75z" /></svg>
                Absensi Saya
            </a>
            @else
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12l8.25-7.5 8.25 7.5M5.25 10.5v8.25h4.5v-5.25h4.5v5.25h4.5V10.5" /></svg>
                Dashboard
            </a>
            <a href="{{ route('attendances.index') }}" class="nav-link {{ request()->routeIs('attendances.index') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3.75M16 7V3.75M4.75 9.25h14.5M6.5 5.75h11A1.75 1.75 0 0119.25 7.5v10.25A1.75 1.75 0 0117.5 19.5h-11a1.75 1.75 0 01-1.75-1.75V7.5A1.75 1.75 0 016.5 5.75z" /></svg>
                Absensi Harian
            </a>
            <a href="{{ route('attendances.create') }}" class="nav-link {{ request()->routeIs('attendances.create') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" /></svg>
                Input Absensi
            </a>
            <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 7.5a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 1115 0" /></svg>
                Data Petugas
            </a>
            <a href="{{ route('reports.monthly') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 19V9m7 10V5m7 14v-7" /></svg>
                Laporan Bulanan
            </a>
            <a href="{{ route('inventories.index') }}" class="nav-link {{ request()->routeIs('inventories.*') || request()->routeIs('inventory-transactions.*') || request()->routeIs('inventory-reports.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.75 7.5l7.25-3.25 7.25 3.25M4.75 7.5v8.75L12 20l7.25-3.75V7.5M4.75 7.5L12 11.25m7.25-3.75L12 11.25m0 0V20" /></svg>
                Inventaris
            </a>
            @endif
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link logout-button">
                    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.75A1.75 1.75 0 0014 4H6.75A1.75 1.75 0 005 5.75v12.5A1.75 1.75 0 006.75 20H14a1.75 1.75 0 001.75-1.75V15M12 12h8m0 0l-3-3m3 3l-3 3" /></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <main class="app-main">
        <header class="app-topbar">
            <div class="topbar-title">
                <h1>@yield('header', 'Dashboard')</h1>
                <p>@yield('subtitle', 'Pantau absensi dan performa petugas')</p>
            </div>
            <div class="user-pill">
                <div class="user-avatar">{{ $initial }}</div>
                <div class="user-name">{{ $user?->name ?? 'Admin' }}</div>
            </div>
        </header>

        <section class="app-content">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            @yield('content')
        </section>
    </main>
</div>
</body>
</html>
