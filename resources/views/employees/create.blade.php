@extends('layouts.app')
@section('title', 'Tambah Petugas')
@section('header', 'Tambah Petugas')
@section('subtitle', 'Tambahkan petugas baru beserta area dan shift kerja')

@section('content')
<div class="page-header"><div><h2>Form Petugas Baru</h2><p>Lengkapi data petugas sesuai area kerja.</p></div><a href="{{ route('employees.index') }}" class="btn-secondary">Kembali</a></div>

<div class="card form-card">
    <form method="POST" action="{{ route('employees.store') }}">
        @csrf
        <div class="filter-grid" style="align-items:start;">
            <div class="form-field" style="flex:1;min-width:240px;"><label>Nama Lengkap</label><input type="text" name="name" value="{{ old('name') }}" required class="form-control">@error('name')<p class="field-error">{{ $message }}</p>@enderror</div>
            <div class="form-field" style="flex:1;min-width:220px;"><label>Kode Petugas</label><input type="text" name="employee_code" value="{{ old('employee_code') }}" placeholder="PTG-001" required class="form-control">@error('employee_code')<p class="field-error">{{ $message }}</p>@enderror</div>
            <div class="form-field" style="flex:1;min-width:220px;"><label>Area/Blok</label><input type="text" name="area" value="{{ old('area') }}" placeholder="Blok A" required class="form-control">@error('area')<p class="field-error">{{ $message }}</p>@enderror</div>
            <div class="form-field" style="flex:1;min-width:220px;"><label>Shift</label><select name="shift" required class="form-control"><option value="">Pilih Shift</option><option value="pagi" {{ old('shift') === 'pagi' ? 'selected' : '' }}>Pagi (06:00 - 14:00)</option><option value="siang" {{ old('shift') === 'siang' ? 'selected' : '' }}>Siang (14:00 - 22:00)</option><option value="sore" {{ old('shift') === 'sore' ? 'selected' : '' }}>Sore (22:00 - 06:00)</option></select>@error('shift')<p class="field-error">{{ $message }}</p>@enderror</div>
            <div class="form-field" style="flex:1;min-width:220px;"><label>No. HP</label><input type="text" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" class="form-control">@error('phone')<p class="field-error">{{ $message }}</p>@enderror</div>
        </div>
        <div class="form-actions"><button type="submit" class="btn-primary">Simpan Petugas</button><a href="{{ route('employees.index') }}" class="btn-secondary">Batal</a></div>
    </form>
</div>
@endsection
