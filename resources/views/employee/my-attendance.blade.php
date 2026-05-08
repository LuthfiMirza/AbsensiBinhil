@extends('layouts.app')
@section('title', 'Absensi Saya')
@section('header', 'Absensi Saya')
@section('subtitle', 'Catat kehadiran pribadi dan lihat riwayat bulan ini')

@section('content')
@php
    $todayDate = \Carbon\Carbon::parse($today);
    $statusClass = 'status-empty';
    $statusLabel = 'Belum Check-in';
    $actionType = 'check_in';
    $actionLabel = 'Check In Sekarang';
    $actionDisabled = false;
    $lateText = $attendance?->late_minutes > 0 ? $attendance->late_minutes.' menit' : '0 menit';

    if ($isHoliday) {
        $statusClass = 'status-holiday';
        $statusLabel = 'Hari Libur';
        $actionLabel = 'Hari ini libur';
        $actionDisabled = true;
    } elseif ($attendance?->check_out) {
        $statusClass = $attendance->status === 'late' ? 'status-late' : 'status-on-time';
        $statusLabel = 'Sudah Check-out';
        $actionLabel = 'Absensi hari ini selesai';
        $actionDisabled = true;
    } elseif ($attendance?->check_in) {
        $statusClass = $attendance->status === 'late' ? 'status-late' : 'status-on-time';
        $statusLabel = $attendance->status === 'late' ? 'Terlambat' : 'Tepat Waktu';
        $actionType = 'check_out';
        $actionLabel = 'Check Out Sekarang';
    }
@endphp

<style>
    .my-attendance-page {
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
    }

    .my-attendance-page .mobile-attendance-header {
        display: none;
    }

    .my-attendance-page .hero-card {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 18px;
        align-items: center;
        margin-bottom: 18px;
    }

    .my-attendance-page .employee-name {
        margin: 0;
        font-size: 24px;
        line-height: 1.2;
        font-weight: 800;
        color: var(--color-text);
    }

    .my-attendance-page .employee-meta {
        margin: 8px 0 0;
        color: var(--color-muted);
        font-size: 14px;
    }

    .my-attendance-page .today-status {
        display: grid;
        gap: 8px;
        justify-items: end;
    }

    .my-attendance-page .today-date {
        color: var(--color-muted);
        font-size: 13px;
        font-weight: 700;
    }

    .my-attendance-page .attendance-info-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 18px;
    }

    .my-attendance-page .info-card {
        padding: 18px;
    }

    .my-attendance-page .info-label {
        margin: 0;
        color: var(--color-muted);
        font-size: 12px;
        font-weight: 700;
    }

    .my-attendance-page .info-value {
        margin: 8px 0 0;
        color: var(--color-text);
        font-size: 24px;
        line-height: 1;
        font-weight: 800;
        font-variant-numeric: tabular-nums;
    }

    .my-attendance-page .action-card {
        margin-bottom: 22px;
    }

    .my-attendance-page .attendance-action-button {
        width: 100%;
        min-height: 58px;
        border-radius: 18px;
        font-size: 16px;
    }

    .my-attendance-page .attendance-action-button:disabled {
        cursor: not-allowed;
        background: #efefea;
        color: #77776f;
        border-color: var(--color-border);
    }

    .my-attendance-page .action-hint {
        margin: 12px 0 0;
        color: var(--color-muted);
        font-size: 13px;
        text-align: center;
    }

    .my-attendance-page .desktop-history-table {
        display: block;
    }

    .my-attendance-page .mobile-history-list {
        display: none;
    }

    @media (max-width: 768px) {
        .my-attendance-page {
            max-width: 100%;
        }

        .my-attendance-page .desktop-page-header {
            display: none;
        }

        .my-attendance-page .mobile-attendance-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 16px;
        }

        .my-attendance-page .mobile-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
            flex: 1;
        }

        .my-attendance-page .mobile-brand img {
            width: 44px;
            height: auto;
            flex-shrink: 0;
        }

        .my-attendance-page .mobile-title {
            margin: 0;
            font-size: 18px;
            line-height: 1.15;
            font-weight: 800;
        }

        .my-attendance-page .mobile-name {
            margin: 4px 0 0;
            color: var(--color-muted);
            font-size: 12px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 190px;
        }

        .my-attendance-page .mobile-logout-button {
            width: auto;
            min-height: 42px;
            padding: 0 14px;
            border-radius: 999px;
        }

        .my-attendance-page .hero-card {
            grid-template-columns: 1fr;
            padding: 18px;
        }

        .my-attendance-page .employee-name {
            font-size: 22px;
        }

        .my-attendance-page .today-status {
            justify-items: start;
        }

        .my-attendance-page .attendance-info-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .my-attendance-page .info-card {
            padding: 16px;
            border-radius: 18px;
        }

        .my-attendance-page .info-value {
            font-size: 21px;
        }

        .my-attendance-page .action-card {
            padding: 18px;
            border-radius: 20px;
        }

        .my-attendance-page .attendance-action-button {
            min-height: 58px;
            font-size: 16px;
            border-radius: 16px;
        }

        .my-attendance-page .desktop-history-table {
            display: none;
        }

        .my-attendance-page .mobile-history-list {
            display: grid;
            gap: 12px;
        }

        .my-attendance-page .mobile-history-card {
            background: #ffffff;
            border: 1px solid var(--color-border);
            border-radius: 18px;
            padding: 16px;
        }

        .my-attendance-page .history-card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 12px;
            min-width: 0;
        }

        .my-attendance-page .history-date {
            margin: 0;
            min-width: 0;
            overflow-wrap: anywhere;
            font-size: 15px;
            font-weight: 800;
            color: var(--color-text);
        }

        .my-attendance-page .history-detail-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
        }

        .my-attendance-page .history-detail-label {
            margin: 0 0 4px;
            color: var(--color-muted);
            font-size: 11px;
            font-weight: 700;
        }

        .my-attendance-page .history-detail-value {
            margin: 0;
            color: var(--color-text);
            font-size: 13px;
            font-weight: 800;
        }
    }

    @media (max-width: 380px) {
        .my-attendance-page .attendance-info-grid,
        .my-attendance-page .history-detail-grid {
            grid-template-columns: 1fr;
        }

        .my-attendance-page .mobile-name {
            max-width: 150px;
        }

        .my-attendance-page .mobile-logout-button {
            width: auto;
            padding-inline: 12px;
        }
    }
</style>

<div class="my-attendance-page">
    <div class="mobile-attendance-header">
        <div class="mobile-brand">
            <img src="{{ asset('images/logobintarohill.png') }}" alt="Bintaro Hill">
            <div>
                <p class="mobile-title">Absensi Saya</p>
                <p class="mobile-name">{{ $employee->name }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-secondary mobile-logout-button">Logout</button>
        </form>
    </div>

    <div class="page-header desktop-page-header">
        <div>
            <h2>{{ $employee->name }}</h2>
            <p>{{ $employee->employee_code }} · {{ $employee->area }} · {{ ucfirst($employee->shift) }}</p>
        </div>
    </div>

    <div class="card hero-card">
        <div>
            <h2 class="employee-name">{{ $employee->name }}</h2>
            <p class="employee-meta">{{ $employee->employee_code }} · {{ $employee->area }} · Shift {{ ucfirst($employee->shift) }}</p>
        </div>
        <div class="today-status">
            <span class="today-date">{{ $todayDate->translatedFormat('l, d M Y') }}</span>
            <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
        </div>
    </div>

    <div class="attendance-info-grid">
        <div class="card info-card">
            <p class="info-label">Jam Masuk</p>
            <p class="info-value">{{ $attendance?->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '--:--' }}</p>
        </div>
        <div class="card info-card">
            <p class="info-label">Jam Pulang</p>
            <p class="info-value">{{ $attendance?->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '--:--' }}</p>
        </div>
        <div class="card info-card">
            <p class="info-label">Telat</p>
            <p class="info-value">{{ $lateText }}</p>
        </div>
        <div class="card info-card">
            <p class="info-label">Status</p>
            <p class="info-value" style="font-size:18px;line-height:1.2;">{{ $statusLabel }}</p>
        </div>
    </div>

    <div class="card action-card">
        <div class="table-header" style="padding:0 0 16px;margin-bottom:18px;">
            <div>
                <h3>Absensi Hari Ini</h3>
                <p>Absensi otomatis tercatat untuk akun Anda sendiri.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('my-attendance.store') }}">
            @csrf
            <input type="hidden" name="type" value="{{ $actionType }}">
            <button type="submit" class="btn-primary attendance-action-button" @disabled($actionDisabled)>{{ $actionLabel }}</button>
        </form>
        <p class="action-hint">
            @if($isHoliday)
                Hari Minggu tidak dihitung sebagai hari kerja.
            @elseif(! $attendance?->check_in)
                Tekan tombol saat mulai bekerja.
            @elseif(! $attendance?->check_out)
                Jangan lupa check-out saat selesai bekerja.
            @else
                Terima kasih, absensi hari ini sudah lengkap.
            @endif
        </p>
    </div>

    <div class="table-card">
        <div class="table-header">
            <div>
                <h3>Riwayat Terbaru</h3>
                <p>Hanya menampilkan data absensi milik Anda.</p>
            </div>
        </div>

        <div class="table-responsive desktop-history-table">
            <table class="app-table">
                <thead><tr><th>Tanggal</th><th>Status</th><th>Jam Masuk</th><th>Jam Pulang</th><th>Terlambat</th></tr></thead>
                <tbody>
                    @forelse($history as $row)
                        <tr>
                            <td>{{ $row->date->translatedFormat('d M Y') }}</td>
                            <td>@if($row->status === 'on_time')<span class="status-badge status-on-time">Tepat Waktu</span>@elseif($row->status === 'late')<span class="status-badge status-late">Terlambat</span>@else<span class="status-badge status-absent">Tidak Hadir</span>@endif</td>
                            <td>{{ $row->check_in ? \Carbon\Carbon::parse($row->check_in)->format('H:i') : '--:--' }}</td>
                            <td>{{ $row->check_out ? \Carbon\Carbon::parse($row->check_out)->format('H:i') : '--:--' }}</td>
                            <td>{{ $row->late_minutes > 0 ? $row->late_minutes.' menit' : '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-muted" style="text-align:center;padding:36px;">Belum ada data absensi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mobile-history-list">
            @forelse($history as $row)
                <div class="mobile-history-card">
                    <div class="history-card-top">
                        <p class="history-date">{{ $row->date->translatedFormat('l, d M Y') }}</p>
                        @if($row->status === 'on_time')
                            <span class="status-badge status-on-time">Tepat Waktu</span>
                        @elseif($row->status === 'late')
                            <span class="status-badge status-late">Terlambat</span>
                        @else
                            <span class="status-badge status-absent">Tidak Hadir</span>
                        @endif
                    </div>
                    <div class="history-detail-grid">
                        <div><p class="history-detail-label">Masuk</p><p class="history-detail-value">{{ $row->check_in ? \Carbon\Carbon::parse($row->check_in)->format('H:i') : '--:--' }}</p></div>
                        <div><p class="history-detail-label">Pulang</p><p class="history-detail-value">{{ $row->check_out ? \Carbon\Carbon::parse($row->check_out)->format('H:i') : '--:--' }}</p></div>
                        <div><p class="history-detail-label">Telat</p><p class="history-detail-value">{{ $row->late_minutes > 0 ? $row->late_minutes.' menit' : '0 menit' }}</p></div>
                    </div>
                </div>
            @empty
                <div class="mobile-history-card text-muted" style="text-align:center;">Belum ada data absensi.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
