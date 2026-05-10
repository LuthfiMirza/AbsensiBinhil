@extends('layouts.app')
@section('title', 'Dashboard Koordinator')
@section('header', 'Dashboard Koordinator')
@section('subtitle', 'Ringkasan operasional petugas kebersihan Bintaro Hill')

@section('content')
<div class="page-header">
    <div>
        <h2>Operasional Hari Ini</h2>
        <p>{{ $today->translatedFormat('l, d F Y') }} · pantau absensi, performa, dan kebutuhan tindak lanjut.</p>
    </div>
</div>

@if($today->isSunday())
    <div class="alert" style="background:#faf7ee;color:#7a642e;border-color:#eadbb8;">Hari ini libur, absensi tidak dihitung sebagai hari kerja.</div>
@endif

<div class="stats-grid">
    <div class="stat-card" style="--accent:#5e6640;--accent-soft:#eef1e6;"><p class="stat-label">Total Petugas Aktif</p><p class="stat-value">{{ $todaySummary['total_active'] }}</p><p class="stat-note">terdaftar aktif</p></div>
    <div class="stat-card" style="--accent:#4f7d45;--accent-soft:#edf5e9;"><p class="stat-label">Hadir</p><p class="stat-value">{{ $todaySummary['hadir'] }}</p><p class="stat-note">termasuk terlambat</p></div>
    <div class="stat-card" style="--accent:#d99a25;--accent-soft:#fff4df;"><p class="stat-label">Terlambat</p><p class="stat-value">{{ $todaySummary['terlambat'] }}</p><p class="stat-note">melewati toleransi</p></div>
    <div class="stat-card" style="--accent:#c0392b;--accent-soft:#fff0ee;"><p class="stat-label">Belum Absen</p><p class="stat-value">{{ $todaySummary['belum_absen'] }}</p><p class="stat-note">belum ada record</p></div>
</div>

<div class="stats-grid">
    <div class="stat-card" style="--accent:#3d6f82;--accent-soft:#eef3f6;"><p class="stat-label">Izin</p><p class="stat-value">{{ $todaySummary['izin'] }}</p><p class="stat-note">dengan keterangan</p></div>
    <div class="stat-card" style="--accent:#6f5b84;--accent-soft:#f1eef6;"><p class="stat-label">Sakit</p><p class="stat-value">{{ $todaySummary['sakit'] }}</p><p class="stat-note">tidak masuk sakit</p></div>
    <div class="stat-card" style="--accent:#c0392b;--accent-soft:#fff0ee;"><p class="stat-label">Alfa</p><p class="stat-value">{{ $todaySummary['alfa'] }}</p><p class="stat-note">tanpa keterangan</p></div>
    <div class="stat-card" style="--accent:#77776f;--accent-soft:#efefea;"><p class="stat-label">Libur</p><p class="stat-value">{{ $todaySummary['libur'] }}</p><p class="stat-note">tidak menurunkan skor</p></div>
</div>

<div class="stats-grid">
    <div class="stat-card" style="--accent:#5e6640;--accent-soft:#eef1e6;"><p class="stat-label">Skor Bulan Ini</p><p class="stat-value">{{ $monthSummary['average_score'] }}</p><p class="stat-note">rata-rata performa</p></div>
    <div class="stat-card" style="--accent:#4f7d45;--accent-soft:#edf5e9;"><p class="stat-label">Petugas Terbaik</p><p class="stat-value">{{ $monthSummary['best_count'] }}</p><p class="stat-note">skor tertinggi bulan ini</p></div>
    <div class="stat-card" style="--accent:#d99a25;--accent-soft:#fff4df;"><p class="stat-label">Telat Bulan Ini</p><p class="stat-value">{{ $monthSummary['total_late'] }}</p><p class="stat-note">total kejadian</p></div>
    <div class="stat-card" style="--accent:#c0392b;--accent-soft:#fff0ee;"><p class="stat-label">Alfa Bulan Ini</p><p class="stat-value">{{ $monthSummary['total_alpha'] }}</p><p class="stat-note">total alfa</p></div>
</div>

<div class="stats-grid">
    <div class="stat-card" style="--accent:#5e6640;--accent-soft:#eef1e6;"><p class="stat-label">{{ $quarterSummary['label'] }}</p><p class="stat-value">{{ $quarterSummary['average_score'] }}</p><p class="stat-note">rata-rata skor quarter</p></div>
    <div class="stat-card" style="--accent:#d99a25;--accent-soft:#fff4df;"><p class="stat-label">Telat Quarter</p><p class="stat-value">{{ $quarterSummary['total_late'] }}</p><p class="stat-note">quarter berjalan</p></div>
    <div class="stat-card" style="--accent:#c0392b;--accent-soft:#fff0ee;"><p class="stat-label">Alfa Quarter</p><p class="stat-value">{{ $quarterSummary['total_alpha'] }}</p><p class="stat-note">quarter berjalan</p></div>
    <a href="{{ route('inventories.index') }}" class="stat-card" style="text-decoration:none;--accent:#c0392b;--accent-soft:#fff0ee;"><p class="stat-label">Stok Barang Rendah</p><p class="stat-value">{{ $lowStockItems->count() }}</p><p class="stat-note">{{ $lowStockItems->isEmpty() ? 'Semua stok aman' : $lowStockItems->take(2)->pluck('name')->join(', ') }}</p></a>
</div>

<div class="card">
    <div class="table-header" style="padding:0;">
        <div><h3>Quick Actions</h3><p>Akses cepat untuk aktivitas koordinator.</p></div>
        <div class="form-actions" style="margin:0;">
            <a href="{{ route('attendances.create') }}" class="btn-primary">Input Absensi</a>
            <a href="{{ route('employees.index') }}" class="btn-secondary">Data Petugas</a>
            <a href="{{ route('reports.monthly') }}" class="btn-secondary">Laporan Bulanan</a>
        </div>
    </div>
</div>
@endsection
