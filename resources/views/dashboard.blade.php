@extends('layouts.app')
@section('title', 'Dashboard')
@section('header', 'Dashboard')
@section('subtitle', 'Ringkasan operasional absensi petugas Bintaro Hill')

@section('content')
<div class="page-header">
    <div>
        <h2>Selamat Datang</h2>
        <p>Gunakan menu di samping untuk memantau absensi, menginput kehadiran, dan melihat laporan bulanan.</p>
    </div>
</div>

<div class="stats-grid">
    <a href="{{ route('attendances.index') }}" class="stat-card" style="text-decoration:none;--accent:#5e6640;--accent-soft:#eef1e6;">
        <p class="stat-label">Absensi Harian</p>
        <p class="stat-value">↗</p>
        <p class="stat-note">Pantau kehadiran hari ini</p>
    </a>
    <a href="{{ route('attendances.create') }}" class="stat-card" style="text-decoration:none;--accent:#4f7d45;--accent-soft:#edf5e9;">
        <p class="stat-label">Input Absensi</p>
        <p class="stat-value">+</p>
        <p class="stat-note">Catat check-in/check-out</p>
    </a>
    <a href="{{ route('employees.index') }}" class="stat-card" style="text-decoration:none;--accent:#d99a25;--accent-soft:#fff4df;">
        <p class="stat-label">Data Petugas</p>
        <p class="stat-value">☷</p>
        <p class="stat-note">Kelola area dan shift</p>
    </a>
    <a href="{{ route('reports.monthly') }}" class="stat-card" style="text-decoration:none;--accent:#5e6640;--accent-soft:#eef1e6;">
        <p class="stat-label">Laporan Bulanan</p>
        <p class="stat-value">▦</p>
        <p class="stat-note">Evaluasi performa petugas</p>
    </a>
</div>
@endsection
