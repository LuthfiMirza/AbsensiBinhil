<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-950 min-h-screen flex flex-col items-center justify-center p-6">

    {{-- Logo & Judul --}}
    <div class="text-center mb-10">
        <div class="w-16 h-16 bg-gray-800 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h1 class="text-white text-2xl font-semibold tracking-tight">Panel Admin</h1>
        <p class="text-gray-500 text-sm mt-1">Absensi Kebersihan Komplek</p>
    </div>

    {{-- Card Form --}}
    <div class="w-full max-w-sm bg-white rounded-3xl p-8 shadow-2xl">

        <h2 class="text-gray-900 text-lg font-semibold mb-1">Selamat datang!</h2>
        <p class="text-gray-400 text-sm mb-6">Masuk sebagai administrator</p>

        {{-- Flash error --}}
        @if(session('error'))
            <div class="mb-5 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-5 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-2">
                    Email
                </label>
                <input type="email" name="email"
                       value="{{ old('email') }}"
                       placeholder="admin@email.com"
                       required autofocus autocomplete="email"
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm
                              text-gray-900 bg-gray-50
                              focus:outline-none focus:ring-2 focus:ring-gray-900 focus:bg-white
                              transition placeholder:text-gray-300">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-8">
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-2">
                    Password
                </label>
                <input type="password" name="password"
                       required autocomplete="current-password"
                       placeholder="••••••••"
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm
                              text-gray-900 bg-gray-50
                              focus:outline-none focus:ring-2 focus:ri