@extends('layouts.app')
@section('title', 'Tugas Saya')
@section('header', 'Tugas Saya')
@section('subtitle', 'Checklist pekerjaan hari ini')
@section('content')
@php $progress = $summary['total'] ? round(($summary['completed'] / $summary['total']) * 100) : 0; @endphp
<style>
    .my-tasks-page { max-width: 920px; }
    .my-tasks-header { display:none; }
    .task-progress { display:grid; grid-template-columns: 1.4fr repeat(3, 1fr); gap:14px; margin-bottom:22px; }
    .task-card-list { display:grid; gap:14px; }
    .task-item { padding:18px; }
    .task-top { display:flex; justify-content:space-between; gap:12px; align-items:flex-start; }
    .task-title { margin:0; font-size:17px; font-weight:800; color:var(--color-text); }
    .task-meta, .task-desc, .task-notes { margin:7px 0 0; color:var(--color-muted); font-size:13px; }
    .task-actions { display:flex; flex-wrap:wrap; gap:10px; margin-top:14px; }
    .task-notes-input { min-height:46px; padding-top:12px; }
    @media (max-width:768px) {
        .my-tasks-page { max-width:100%; }
        .desktop-page-header { display:none; }
        .my-tasks-header { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:16px; }
        .mobile-brand { display:flex; align-items:center; gap:10px; min-width:0; }
        .mobile-brand img { width:44px; height:auto; }
        .mobile-title { margin:0; font-size:18px; font-weight:800; line-height:1.15; }
        .mobile-name { margin:4px 0 0; color:var(--color-muted); font-size:12px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:190px; }
        .task-progress { grid-template-columns:repeat(2, minmax(0, 1fr)); gap:12px; }
        .task-progress .stat-card:first-child { grid-column:1 / -1; }
        .task-top { display:block; }
        .task-top .status-badge { margin-top:10px; }
        .task-actions, .task-actions form, .task-actions button, .task-actions a { width:100%; }
    }
</style>
<div class="my-tasks-page">
    <div class="my-tasks-header">
        <div class="mobile-brand"><img src="{{ asset('images/logobintarohill.png') }}" alt="Bintaro Hill"><div><p class="mobile-title">Tugas Saya</p><p class="mobile-name">{{ $employee->name }}</p></div></div>
        <a href="{{ route('my-attendance.index') }}" class="btn-secondary" style="width:auto;">Absensi</a>
    </div>
    <div class="page-header desktop-page-header"><div><h2>Tugas Saya</h2><p>{{ $employee->name }} · {{ \Carbon\Carbon::parse($today)->translatedFormat('l, d F Y') }}</p></div><a href="{{ route('my-attendance.index') }}" class="btn-secondary">Absensi Saya</a></div>

    <div class="task-progress">
        <div class="stat-card" style="--accent:#5e6640;--accent-soft:#eef1e6;"><p class="stat-label">Progress Hari Ini</p><p class="stat-value">{{ $progress }}%</p><p class="stat-note">{{ $summary['completed'] }} dari {{ $summary['total'] }} tugas selesai</p></div>
        <div class="stat-card" style="--accent:#4f7d45;--accent-soft:#edf5e9;"><p class="stat-label">Selesai</p><p class="stat-value">{{ $summary['completed'] }}</p><p class="stat-note">sudah checklist</p></div>
        <div class="stat-card" style="--accent:#d99a25;--accent-soft:#fff4df;"><p class="stat-label">Proses</p><p class="stat-value">{{ $summary['in_progress'] }}</p><p class="stat-note">sedang dikerjakan</p></div>
        <div class="stat-card" style="--accent:#c0392b;--accent-soft:#fff0ee;"><p class="stat-label">Belum</p><p class="stat-value">{{ $summary['pending'] }}</p><p class="stat-note">perlu dikerjakan</p></div>
    </div>

    <div class="table-card" style="padding:22px;margin-bottom:22px;">
        <div class="table-header" style="padding:0 0 16px;"><div><h3>Checklist Hari Ini</h3><p>Tandai mulai atau selesai untuk tugas milik Anda.</p></div></div>
        <div class="task-card-list">
            @forelse($todayTasks as $task)
                <div class="card task-item">
                    <div class="task-top"><div><h3 class="task-title">{{ $task->title }}</h3><p class="task-meta">{{ $task->area ?: $employee->area }} · Shift {{ ucfirst($task->shift ?: $employee->shift) }}</p></div><span class="status-badge {{ $task->statusBadgeClass() }}">{{ $task->statusLabel() }}</span></div>
                    @if($task->description)<p class="task-desc">{{ $task->description }}</p>@endif
                    @if($task->notes)<p class="task-notes"><strong>Catatan:</strong> {{ $task->notes }}</p>@endif
                    @if(! $task->isCompleted())
                        <div class="task-actions">
                            @if($task->status === \App\Models\DailyTask::STATUS_PENDING)<form method="POST" action="{{ route('my-tasks.start', $task) }}">@csrf<button type="submit" class="btn-secondary">Mulai</button></form>@endif
                            <form method="POST" action="{{ route('my-tasks.complete', $task) }}" style="flex:1;min-width:260px;">@csrf<div style="display:flex;gap:10px;align-items:start;flex-wrap:wrap;"><textarea name="notes" class="form-control task-notes-input" placeholder="Catatan singkat (opsional)" style="flex:1;min-width:180px;"></textarea><button type="submit" class="btn-primary">Tandai Selesai</button></div></form>
                        </div>
                    @else
                        <p class="task-notes">Selesai {{ $task->completed_at?->format('H:i') }} oleh {{ $task->completedBy?->name ?: 'Anda' }}.</p>
                    @endif
                </div>
            @empty
                <div class="card task-item"><h3 class="task-title">Belum ada tugas untuk hari ini</h3><p class="task-desc">Silakan cek kembali nanti atau hubungi koordinator.</p></div>
            @endforelse
        </div>
    </div>

    <div class="table-card" style="padding:22px;"><div class="table-header" style="padding:0 0 16px;"><div><h3>Riwayat Terbaru</h3><p>10 tugas terakhir sebelum hari ini.</p></div></div><div class="task-card-list">@forelse($recentTasks as $task)<div class="card task-item"><div class="task-top"><div><h3 class="task-title">{{ $task->title }}</h3><p class="task-meta">{{ $task->task_date->format('d/m/Y') }} · {{ $task->area ?: '-' }}</p></div><span class="status-badge {{ $task->statusBadgeClass() }}">{{ $task->statusLabel() }}</span></div></div>@empty<p class="text-muted">Belum ada riwayat tugas.</p>@endforelse</div></div>
</div>
@endsection
