@extends('layouts.app')
@section('title', 'Data Petugas')
@section('header', 'Data Petugas')
@section('subtitle', 'Kelola petugas, area/blok, shift, dan kontak')

@section('content')
<div class="page-header">
    <div>
        <h2>Daftar Petugas Aktif</h2>
        <p>Total {{ $employees->count() }} petugas aktif terdaftar.</p>
    </div>
    <a href="{{ route('employees.create') }}" class="btn-primary">Tambah Petugas</a>
</div>

<div class="table-card">
    <div class="table-header"><div><h3>Petugas Kebersihan</h3><p>Data area/blok dan shift kerja setiap petugas.</p></div></div>
    <div class="table-responsive">
        <table class="app-table">
            <thead><tr><th>Nama / Kode</th><th>Area/Blok</th><th>Shift</th><th>No. HP</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($employees as $emp)
                    <tr>
                        <td><strong>{{ $emp->name }}</strong><div class="small-text">{{ $emp->employee_code }}</div></td>
                        <td><span class="area-badge">{{ $emp->area }}</span></td>
                        <td><span class="status-badge status-empty">{{ ucfirst($emp->shift) }}</span></td>
                        <td>{{ $emp->phone ?? '-' }}</td>
                        <td>
                            <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                                <a href="{{ route('employees.edit', $emp) }}" class="btn-link-soft">Edit</a>
                                <form method="POST" action="{{ route('employees.destroy', $emp) }}" onsubmit="return confirm('Nonaktifkan petugas ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger" style="min-height:34px;padding:0 12px;">Nonaktifkan</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-muted" style="text-align:center;padding:42px;">Belum ada petugas. <a href="{{ route('employees.create') }}">Tambah sekarang</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
