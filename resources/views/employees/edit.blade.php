@extends('layouts.app')
@section('title', 'Edit Petugas')
@section('header', 'Edit Petugas')
@section('subtitle', 'Perbarui area, shift, dan kontak petugas')

@section('content')
<div class="page-header"><div><h2>{{ $employee->name }}</h2><p>{{ $employee->employee_code }} · {{ $employee->area }}</p></div><a href="{{ route('employees.index') }}" class="btn-secondary">Kembali</a></div>

<div class="card form-card">
    <form method="POST" action="{{ route('employees.update', $employee) }}">
        @csrf @method('PUT')
        <div class="filter-grid" style="align-items:start;">
            <div class="form-field" style="flex:1;min-width:240px;"><label>Nama Lengkap</label><input type="text" name="name" value="{{ old('name', $employee->name) }}" required class="form-control">@error('name')<p class="field-error">{{ $message }}</p>@enderror</div>
            <div class="form-field" style="flex:1;min-width:220px;"><label>Kode Petugas</label><input type="text" value="{{ $employee->employee_code }}" disabled class="form-control" style="background:#fafaf7;color:#888;"></div>
            <div class="form-field" style="flex:1;min-width:220px;"><label>Area/Blok</label><input type="text" name="area" value="{{ old('area', $employee->area) }}" required class="form-control">@error('area')<p class="field-error">{{ $message }}</p>@enderror</div>
            <div class="form-field" style="flex:1;min-width:220px;"><label>Shift</label><select name="shift" required class="form-control"><option value="pagi" {{ $employee->shift === 'pagi' ? 'selected' : '' }}>Pagi (06:00 - 14:00)</option><option value="siang" {{ $employee->shift === 'siang' ? 'selected' : '' }}>Siang (14:00 - 22:00)</option><option value="sore" {{ $employee->shift === 'sore' ? 'selected' : '' }}>Sore (22:00 - 06:00)</option></select>@error('shift')<p class="field-error">{{ $message }}</p>@enderror</div>
            <div class="form-field" style="flex:1;min-width:220px;"><label>No. HP</label><input type="text" name="phone" value="{{ old('phone', $employee->phone) }}" class="form-control">@error('phone')<p class="field-error">{{ $message }}</p>@enderror</div>
        </div>
        <div class="form-actions"><button type="submit" class="btn-primary">Update Petugas</button><a href="{{ route('employees.index') }}" class="btn-secondary">Batal</a></div>
    </form>
</div>
@endsection
