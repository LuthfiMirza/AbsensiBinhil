<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Absensi Kebersihan')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 font-sans">

    {{-- Sidebar --}}
    <div class="flex h-screen">
        <aside class="w-64 bg-gray-900 text-white flex flex-col">
            <div class="px-6 py-5 border-b border-gray-700">
                <h1 class="text-lg font-bold">🧹 Absensi Komplek</h1>
                <p class="text-xs text-gray-400 mt-1">Panel Admin</p>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-1">
                <a href="{{ route('attendances.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
                          {{ request()->routeIs('attendances.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                    📋 Absensi Hari Ini
                </a>
                <a href="{{ route('attendances.create') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
                          {{ request()->routeIs('attendances.create') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                    ✅ Input Absensi
                </a>
                <a href="{{ route('employees.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
                          {{ request()->routeIs('employees.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                    👥 Data Petugas
                </a>
                <a href="{{ route('reports.monthly') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
                          {{ request()->routeIs('reports.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                    📊 Laporan Bulanan
                </a>
            </nav>

            <div class="px-4 py-4 border-t border-gray-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full text-left text-sm text-gray-400 hover:text-white px-3 py-2">
                        🚪 Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 overflow-y-auto">
            {{-- Top Bar --}}
            <div class="bg-white border-b px-8 py-4 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">@yield('header')</h2>
                <span class="text-sm text-gray-500">{{ now()->translatedFormat('l, d F Y') }}</span>
            </div>

            <div class="px-8 py-6">
                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="mb-4 bg-green-100 text-green-800 px-4 py-3 rounded-lg text-sm">
                        ✅ {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 bg-red-100 text-red-800 px-4 py-3 rounded-lg text-sm">
                        ❌ {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>