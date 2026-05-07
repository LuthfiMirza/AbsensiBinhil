@extends('layouts.app')
@section('title', 'Absensi Saya')
@section('header', 'Absensi Saya')
@section('subtitle', 'Catat kehadiran pribadi dan lihat riwayat bulan ini')

@section('content')
<div class="page-header">
    <div>
        <h2>{{ $employee->name }}</h2>
        <p>{{ $employee->employee_code }} · {{ $employee->area }} · {{ ucfirst($employee->shift) }}</p>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card" style="--accent:#5e6640;--accent-soft:#eef1e6;"><p class="stat-label">Tanggal</p><p class="stat-value" style="font-size:24px;">{{ \Carbon\Carbon::parse($today)->format('d M') }}</p><p class="stat-note">hari ini</p></div>
    <div class="stat-card" style="--accent:#4f7d45;--accent-soft:#edf5e9;"><p class="stat-label">Jam Masuk</p><p class="stat-value" style="font-size:24px;">{{ $attendance?->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '--:--' }}</p><p class="stat-note">check-in</p></div>
    <div class="stat-card" style="--accent:#d99a25;--accent-soft:#fff4df;"><p class="stat-label">Jam Pulang</p><p class="stat-value" style="font-size:24px;">{{ $attendance?->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '--:--' }}</p><p class="stat-note">check-out</p></div>
    <div class="stat-card" style="--accent:#c0392b;--accent-soft:#fff0ee;"><p class="stat-label">Status</p><p class="stat-value" style="font-size:24px;">{{ $attendance?->status ? str_replace('_', ' ', ucfirst($attendance->status)) : '-' }}</p><p class="stat-note">kehadiran</p></div>
</div>

<div class="card form-card">
    <div class="table-header" style="padding:0 0 18px;margin-bottom:22px;"><div><h3>Check In / Check Out</h3><p>Absensi ini otomatis tercatat untuk akun Anda sendiri.</p></div></div>
    <form method="POST" action="{{ route('my-attendance.store') }}">
        @csrf
        <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;margin-bottom:18px;">
            <label class="card" style="padding:16px;cursor:pointer;box-shadow:none;"><input type="radio" name="type" value="check_in" checked style="accent-color:var(--color-primary);"><strong style="margin-left:8px;">Check In</strong><div class="small-text" style="margin-top:6px;">Masuk kerja</div></label>
            <label class="card" style="padding:16px;cursor:pointer;box-shadow:none;"><input type="radio" name="type" value="check_out" style="accent-color:var(--color-primary);"><strong style="margin-left:8px;">Check Out</strong><div class="small-text" style="margin-top:6px;">Selesai kerja</div></label>
        </div>
        <button type="submit" class="btn-primary">Simpan Absensi Saya</button>
    </form>
</div>

<div class="table-card" style="margin-top:22px;">
    <div class="table-header"><div><h3>Riwayat Bulan Ini</h3><p>Hanya menampilkan data absensi milik Anda.</p></div></div>
    <div class="table-responsive">
        <table class="app-table">
            <thead><tr><th>Tanggal</th><th>Status</th><th>Jam Masuk</th><th>Jam Pulang</th><th>Terlambat</th></tr></thead>
            <tbody>
                @forelse($history as $row)
                    <tr><td>{{ $row->date->translatedFormat('d M Y') }}</td><td>@if($row->status === 'on_time')<span class="status-badge status-on-time">Tepat Waktu</span>@elseif($row->status === 'late')<span class="status-badge status-late">Terlambat</span>@else<span class="status-badge status-absent">Tidak Hadir</span>@endif</td><td>{{ $row->check_in ? \Carbon\Carbon::parse($row->check_in)->format('H:i') : '--:--' }}</td><td>{{ $row->check_out ? \Carbon\Carbon::parse($row->check_out)->format('H:i') : '--:--' }}</td><td>{{ $row->late_minutes > 0 ? $row->late_minutes.' menit' : '-' }}</td></tr>
                @empty
                    <tr><td colspan="5" class="text-muted" style="text-align:center;padding:36px;">Belum ada riwayat absensi bulan ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
