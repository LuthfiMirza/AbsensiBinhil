@extends('layouts.app')
@section('title', 'Master Tugas')
@section('header', 'Master Tugas')
@section('subtitle', 'Template pekerjaan standar untuk checklist harian petugas')
@section('content')
<div class="page-header">
    <div><h2>Master Tugas</h2><p>Kelola template tugas yang bisa dipakai berulang.</p></div>
    <a href="{{ route('task-templates.create') }}" class="btn-primary">Tambah Master Tugas</a>
</div>
<div class="table-card">
    <div class="table-header"><div><h3>Daftar Template</h3><p>Nonaktifkan template lama agar histori tugas tetap aman.</p></div></div>
    <div class="table-responsive"><table class="app-table"><thead><tr><th>Nama</th><th>Area</th><th>Shift</th><th>Urutan</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
        @forelse($taskTemplates as $template)
            <tr>
                <td><strong>{{ $template->name }}</strong><br><span class="text-muted">{{ Str::limit($template->description, 70) }}</span></td>
                <td>{{ $template->default_area ?: '-' }}</td>
                <td>{{ $template->default_shift ? ucfirst($template->default_shift) : '-' }}</td>
                <td>{{ $template->sort_order }}</td>
                <td><span class="status-badge {{ $template->is_active ? 'status-on-time' : 'status-empty' }}">{{ $template->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                <td><div class="form-actions" style="margin:0;gap:8px;"><a class="btn-secondary" href="{{ route('task-templates.edit', $template) }}">Edit</a><form method="POST" action="{{ route('task-templates.destroy', $template) }}">@csrf @method('DELETE')<button class="btn-secondary" type="submit">{{ $template->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button></form></div></td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-muted" style="text-align:center;padding:42px;">Belum ada master tugas.</td></tr>
        @endforelse
    </tbody></table></div>
    {{ $taskTemplates->links() }}
</div>
@endsection
