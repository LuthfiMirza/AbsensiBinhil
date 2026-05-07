@extends('layouts.app')
@section('title', 'Input Absensi')
@section('header', 'Input Absensi')
@section('subtitle', 'Catat check-in, check-out, dan ketidakhadiran petugas')

@section('content')
<div class="page-header">
    <div>
        <h2>Form Absensi Petugas</h2>
        <p>Pilih petugas dan jenis absensi sesuai aktivitas hari ini.</p>
    </div>
    <a href="{{ route('attendances.index') }}" class="btn-secondary">Kembali</a>
</div>

<div class="card form-card">
    <div class="table-header" style="padding:0 0 18px;margin-bottom:22px;">
        <div>
            <h3>Check In / Check Out</h3>
            <p>Waktu akan dicatat otomatis berdasarkan jam sistem.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('attendances.store') }}">
        @csrf
        <div class="form-field" style="margin-bottom:18px;">
            <label for="employee_id">Petugas</label>
            <select id="employee_id" name="employee_id" required class="form-control">
                <option value="">Pilih Petugas</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                        {{ $emp->name }} — {{ $emp->area }} ({{ ucfirst($emp->shift) }})
                    </option>
                @endforeach
            </select>
            @error('employee_id')<p class="field-error">{{ $message }}</p>@enderror
        </div>

        <div class="form-field" style="margin-bottom:18px;">
            <label>Jenis Absensi</label>
            <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;">
                <label class="card" style="padding:16px;cursor:pointer;box-shadow:none;">
                    <input type="radio" name="type" value="check_in" checked style="accent-color:var(--color-primary);">
                    <strong style="margin-left:8px;">Check In</strong>
                    <div class="small-text" style="margin-top:6px;">Masuk kerja</div>
                </label>
                <label class="card" style="padding:16px;cursor:pointer;box-shadow:none;">
                    <input type="radio" name="type" value="check_out" style="accent-color:var(--color-primary);">
                    <strong style="margin-left:8px;">Check Out</strong>
                    <div class="small-text" style="margin-top:6px;">Selesai kerja</div>
                </label>
            </div>
        </div>

        <div class="card" style="padding:18px;text-align:center;background:#fafaf7;box-shadow:none;margin-bottom:18px;">
            <p class="small-text" style="margin:0 0 6px;">Waktu Sekarang</p>
            <p id="clock" style="margin:0;font-size:30px;font-weight:800;letter-spacing:.04em;color:var(--color-primary);font-variant-numeric:tabular-nums;">--:--:--</p>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Simpan Absensi</button>
            <a href="{{ route('attendances.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>

<div class="card form-card" style="margin-top:22px;">
    <div class="table-header" style="padding:0 0 18px;margin-bottom:22px;">
        <div>
            <h3>Tandai Tidak Hadir</h3>
            <p>Gunakan untuk mencatat izin, sakit, atau petugas tidak masuk.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('attendances.absent') }}">
        @csrf
        <div class="filter-grid" style="align-items:start;">
            <div class="form-field" style="flex:1;min-width:220px;">
                <label for="absent_employee_id">Petugas</label>
                <select id="absent_employee_id" name="employee_id" required class="form-control">
                    <option value="">Pilih Petugas</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->name }} — {{ $emp->area }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-field">
                <label for="date">Tanggal</label>
                <input id="date" type="date" name="date" value="{{ date('Y-m-d') }}" required class="form-control">
            </div>
            <div class="form-field" style="flex:1;min-width:220px;">
                <label for="notes">Keterangan</label>
                <input id="notes" type="text" name="notes" placeholder="Sakit, izin, dll..." class="form-control">
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-danger">Tandai Tidak Hadir</button>
        </div>
    </form>
</div>

<script>
    function updateClock() {
        const clock = document.getElementById('clock');
        if (!clock) return;
        const now = new Date();
        clock.textContent = now.toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit', second: '2-digit'});
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
@endsection
