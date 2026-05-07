@extends('layouts.app')
@section('title', 'Absensi Harian')
@section('header', 'Absensi Harian')
@section('subtitle', 'Pantau kehadiran petugas berdasarkan tanggal')

@section('content')
<div class="page-header">
    <div>
        <h2>Ringkasan Kehadiran</h2>
        <p>Data absensi petugas untuk {{ \Carbon\Carbon::parse($today)->translatedFormat('d F Y') }}</p>
    </div>
    <a href="{{ route('attendances.create') }}" class="btn-primary">Input Absensi</a>
</div>

<form method="GET" class="filter-card">
    <div class="filter-grid">
        <div class="form-field">
            <label for="date">Tanggal</label>
            <input id="date" type="date" name="date" value="{{ $today }}" class="form-control">
        </div>
        <button type="submit" class="btn-primary">Terapkan Filter</button>
        <a href="{{ route('attendances.index') }}" class="btn-secondary">Hari Ini</a>
    </div>
</form>

<div class="stats-grid">
    <div class="stat-card" style="--accent:#5e6640;--accent-soft:#eef1e6;">
        <p class="stat-label">Hadir</p>
        <p class="stat-value">{{ $counter['hadir'] }}</p>
        <p class="stat-note">petugas hadir</p>
    </div>
    <div class="stat-card" style="--accent:#4f7d45;--accent-soft:#edf5e9;">
        <p class="stat-label">Tepat Waktu</p>
        <p class="stat-value">{{ $counter['on_time'] }}</p>
        <p class="stat-note">sesuai jadwal</p>
    </div>
    <div class="stat-card" style="--accent:#d99a25;--accent-soft:#fff4df;">
        <p class="stat-label">Terlambat</p>
        <p class="stat-value">{{ $counter['terlambat'] }}</p>
        <p class="stat-note">melewati toleransi</p>
    </div>
    <div class="stat-card" style="--accent:#c0392b;--accent-soft:#fff0ee;">
        <p class="stat-label">Belum Absen</p>
        <p class="stat-value">{{ $counter['belum_absen'] }}</p>
        <p class="stat-note">petugas aktif</p>
    </div>
</div>

<div class="table-card">
    <div class="table-header">
        <div>
            <h3>Data Absensi Petugas</h3>
            <p>Jam masuk, jam pulang, status, dan area/blok kerja.</p>
        </div>
        <a href="{{ route('attendances.create') }}" class="btn-secondary">Tambah Data</a>
    </div>

    <div class="table-responsive">
        <table class="app-table">
            <thead>
                <tr>
                    <th>Petugas</th>
                    <th>Area/Blok</th>
                    <th>Shift</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Status</th>
                    <th>Terlambat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $att)
                    <tr>
                        <td>
                            <strong>{{ $att->employee->name }}</strong>
                            <div class="small-text">{{ $att->employee->employee_code }}</div>
                        </td>
                        <td><span class="area-badge">{{ $att->employee->area }}</span></td>
                        <td><span class="small-text">{{ ucfirst($att->employee->shift) }}</span></td>
                        <td>{{ $att->check_in ? \Carbon\Carbon::parse($att->check_in)->format('H:i') : '--:--' }}</td>
                        <td>{{ $att->check_out ? \Carbon\Carbon::parse($att->check_out)->format('H:i') : '--:--' }}</td>
                        <td>
                            @if($att->status === 'on_time')
                                <span class="status-badge status-on-time">Tepat Waktu</span>
                            @elseif($att->status === 'late')
                                <span class="status-badge status-late">Terlambat</span>
                            @else
                                <span class="status-badge status-absent">Tidak Hadir</span>
                            @endif
                        </td>
                        <td>{{ $att->late_minutes > 0 ? $att->late_minutes.' menit' : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-muted" style="text-align:center;padding:42px;">Belum ada data absensi pada tanggal ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
