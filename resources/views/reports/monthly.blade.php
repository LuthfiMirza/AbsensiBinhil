@extends('layouts.app')
@section('title', 'Laporan Bulanan')
@section('header', 'Laporan Bulanan')
@section('subtitle', 'Evaluasi kehadiran dan ketepatan waktu petugas')

@section('content')
@php
    $totalPetugas = $employees->count();
    $avgScore = $totalPetugas ? round($employees->avg('skor'), 1) : 0;
    $totalLate = $employees->sum('terlambat');
    $totalAbsent = $employees->sum('alfa');
    $totalPermission = $employees->sum('izin');
    $totalSick = $employees->sum('sakit');
@endphp

<div class="page-header">
    <div>
        <h2>Performa Bulanan</h2>
        <p>{{ $bulan }} · {{ $workingDays }} hari kerja, hari Minggu tidak dihitung.</p>
    </div>
</div>

<form method="GET" class="filter-card">
    <div class="filter-grid">
        <div class="form-field">
            <label for="month">Bulan</label>
            <select id="month" name="month" class="form-control">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ \Carbon\Carbon::createFromDate(null, $m, 1)->translatedFormat('F') }}</option>
                @endfor
            </select>
        </div>
        <div class="form-field">
            <label for="year">Tahun</label>
            <select id="year" name="year" class="form-control">
                @foreach($years as $y)
                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-field">
            <label for="area">Area/Blok</label>
            <select id="area" name="area" class="form-control">
                <option value="">Semua Area</option>
                @foreach($areas as $areaOption)
                    <option value="{{ $areaOption }}" {{ $areaOption === $area ? 'selected' : '' }}>{{ $areaOption }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn-primary">Terapkan Filter</button>
        <a href="{{ route('reports.monthly.export', request()->only(['month', 'year', 'area'])) }}" class="btn-secondary">Export CSV</a>
    </div>
</form>

<div class="stats-grid">
    <div class="stat-card" style="--accent:#5e6640;--accent-soft:#eef1e6;"><p class="stat-label">Total Petugas</p><p class="stat-value">{{ $totalPetugas }}</p><p class="stat-note">dalam filter</p></div>
    <div class="stat-card" style="--accent:#4f7d45;--accent-soft:#edf5e9;"><p class="stat-label">Rata-rata Skor</p><p class="stat-value">{{ $avgScore }}</p><p class="stat-note">performa</p></div>
    <div class="stat-card" style="--accent:#d99a25;--accent-soft:#fff4df;"><p class="stat-label">Total Terlambat</p><p class="stat-value">{{ $totalLate }}</p><p class="stat-note">kejadian</p></div>
    <div class="stat-card" style="--accent:#c0392b;--accent-soft:#fff0ee;"><p class="stat-label">Total Alfa</p><p class="stat-value">{{ $totalAbsent }}</p><p class="stat-note">tanpa keterangan</p></div>
</div>
<div class="stats-grid">
    <div class="stat-card" style="--accent:#3d6f82;--accent-soft:#eef3f6;"><p class="stat-label">Total Izin</p><p class="stat-value">{{ $totalPermission }}</p><p class="stat-note">izin tercatat</p></div>
    <div class="stat-card" style="--accent:#6f5b84;--accent-soft:#f1eef6;"><p class="stat-label">Total Sakit</p><p class="stat-value">{{ $totalSick }}</p><p class="stat-note">sakit tercatat</p></div>
    <div class="stat-card" style="--accent:#77776f;--accent-soft:#efefea;"><p class="stat-label">Libur Khusus</p><p class="stat-value">{{ $employees->sum('libur') }}</p><p class="stat-note">tidak menurunkan skor</p></div>
    <div class="stat-card" style="--accent:#77776f;--accent-soft:#efefea;"><p class="stat-label">Belum Ada Data</p><p class="stat-value">{{ $employees->sum('belum_ada_data') }}</p><p class="stat-note">hari kerja kosong</p></div>
</div>

<div class="card" style="margin-bottom:22px;">
    <h3 style="margin:0 0 14px;font-size:17px;">Grafik Kehadiran</h3>
    <canvas id="attendanceChart" height="80"></canvas>
</div>

<div class="table-card">
    <div class="table-header">
        <div>
            <h3>Tabel Performa Petugas</h3>
            <p>Diurutkan berdasarkan skor tertinggi.</p>
        </div>
    </div>
    <div class="table-responsive">
        <table class="app-table">
            <thead>
                <tr>
                    <th>Ranking</th>
                    <th>Petugas</th>
                    <th>Area/Blok</th>
                    <th>Hadir</th>
                    <th>Tepat Waktu</th>
                    <th>Terlambat</th>
                    <th>Izin</th>
                    <th>Sakit</th>
                    <th>Alfa</th>
                    <th>Libur</th>
                    <th>Belum Data</th>
                    <th>Avg. Telat</th>
                    <th>Skor</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $i => $emp)
                    @php $scoreClass = $emp['skor'] >= 80 ? 'score-high' : ($emp['skor'] >= 60 ? 'score-mid' : 'score-low'); @endphp
                    <tr>
                        <td><strong>#{{ $i + 1 }}</strong></td>
                        <td><strong>{{ $emp['name'] }}</strong><div class="small-text">{{ ucfirst($emp['shift']) }}</div></td>
                        <td><span class="area-badge">{{ $emp['area'] }}</span></td>
                        <td>{{ $emp['hadir'] }}</td>
                        <td><span class="status-badge status-on-time">{{ $emp['on_time'] }}</span></td>
                        <td><span class="status-badge status-late">{{ $emp['terlambat'] }}</span></td>
                        <td><span class="status-badge status-permission">{{ $emp['izin'] }}</span></td>
                        <td><span class="status-badge status-sick">{{ $emp['sakit'] }}</span></td>
                        <td><span class="status-badge status-absent">{{ $emp['alfa'] }}</span></td>
                        <td><span class="status-badge status-holiday">{{ $emp['libur'] }}</span></td>
                        <td><span class="status-badge status-empty">{{ $emp['belum_ada_data'] }}</span></td>
                        <td>{{ $emp['avg_terlambat'] > 0 ? $emp['avg_terlambat'].' menit' : '-' }}</td>
                        <td><span class="score-badge {{ $scoreClass }}">{{ $emp['skor'] }}</span></td>
                        <td><a href="{{ route('reports.monthly.detail', ['employee' => $emp['id'], 'month' => $month, 'year' => $year]) }}" class="btn-link-soft">Lihat Detail</a></td>
                    </tr>
                @empty
                    <tr><td colspan="14" class="text-muted" style="text-align:center;padding:42px;">Belum ada data petugas untuk filter ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
const ctx = document.getElementById('attendanceChart');
if (ctx) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($employees->pluck('name')),
            datasets: [
                { label: 'Hadir', data: @json($employees->pluck('hadir')), backgroundColor: '#5e6640' },
                { label: 'Tepat Waktu', data: @json($employees->pluck('on_time')), backgroundColor: '#4f7d45' },
                { label: 'Terlambat', data: @json($employees->pluck('terlambat')), backgroundColor: '#d99a25' },
                { label: 'Izin', data: @json($employees->pluck('izin')), backgroundColor: '#3d6f82' },
                { label: 'Sakit', data: @json($employees->pluck('sakit')), backgroundColor: '#6f5b84' },
                { label: 'Alfa', data: @json($employees->pluck('alfa')), backgroundColor: '#c0392b' }
            ]
        },
        options: { responsive: true, plugins: { legend: { labels: { boxWidth: 12 } } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
    });
}
</script>
@endsection
