@extends('layouts.app')
@section('title', 'Detail Kehadiran')
@section('header', 'Detail Kehadiran')
@section('subtitle', 'Riwayat tanggal per tanggal untuk satu petugas')

@section('content')
@php
    $workingRows = $days->where('is_working_day', true);
    $hadir = $workingRows->filter(fn ($day) => in_array($day['status'], ['on_time', 'late']))->count();
    $onTime = $workingRows->where('status', 'on_time')->count();
    $late = $workingRows->where('status', 'late')->count();
    $absent = $workingRows->where('status', 'absent')->count();
@endphp

<div class="page-header">
    <div>
        <h2>{{ $employee->name }}</h2>
        <p>{{ $employee->employee_code }} · {{ $employee->area }} · {{ ucfirst($employee->shift) }} · {{ $bulan }}</p>
    </div>
    <a href="{{ route('reports.monthly', ['month' => $month, 'year' => $year]) }}" class="btn-secondary">Kembali</a>
</div>

<div class="stats-grid">
    <div class="stat-card" style="--accent:#5e6640;--accent-soft:#eef1e6;"><p class="stat-label">Hadir</p><p class="stat-value">{{ $hadir }}</p><p class="stat-note">dari {{ $workingDays }} hari kerja</p></div>
    <div class="stat-card" style="--accent:#4f7d45;--accent-soft:#edf5e9;"><p class="stat-label">Tepat Waktu</p><p class="stat-value">{{ $onTime }}</p><p class="stat-note">sesuai jadwal</p></div>
    <div class="stat-card" style="--accent:#d99a25;--accent-soft:#fff4df;"><p class="stat-label">Terlambat</p><p class="stat-value">{{ $late }}</p><p class="stat-note">record late</p></div>
    <div class="stat-card" style="--accent:#c0392b;--accent-soft:#fff0ee;"><p class="stat-label">Tidak Hadir</p><p class="stat-value">{{ $absent }}</p><p class="stat-note">record absent</p></div>
</div>

<div class="table-card">
    <div class="table-header">
        <div>
            <h3>Detail Harian</h3>
            <p>Hari Minggu ditandai libur dan tidak dihitung sebagai hari kerja.</p>
        </div>
    </div>
    <div class="table-responsive">
        <table class="app-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Hari</th>
                    <th>Area/Blok</th>
                    <th>Status</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Telat</th>
                </tr>
            </thead>
            <tbody>
                @foreach($days as $day)
                    <tr style="{{ ! $day['is_working_day'] ? 'background:#fafaf7;' : '' }}">
                        <td><strong>{{ $day['date']->translatedFormat('d M Y') }}</strong></td>
                        <td>{{ $day['date']->translatedFormat('l') }}</td>
                        <td><span class="area-badge">{{ $day['area'] }}</span></td>
                        <td>
                            @if(! $day['is_working_day'])
                                <span class="status-badge status-holiday">Libur</span>
                            @elseif($day['status'] === 'on_time')
                                <span class="status-badge status-on-time">Tepat Waktu</span>
                            @elseif($day['status'] === 'late')
                                <span class="status-badge status-late">Terlambat</span>
                            @elseif($day['status'] === 'absent')
                                <span class="status-badge status-absent">Tidak Hadir</span>
                            @else
                                <span class="status-badge status-empty">Belum Ada Data</span>
                            @endif
                        </td>
                        <td>{{ $day['check_in'] ? \Carbon\Carbon::parse($day['check_in'])->format('H:i') : '--:--' }}</td>
                        <td>{{ $day['check_out'] ? \Carbon\Carbon::parse($day['check_out'])->format('H:i') : '--:--' }}</td>
                        <td>{{ $day['late_minutes'] > 0 ? $day['late_minutes'].' menit' : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
