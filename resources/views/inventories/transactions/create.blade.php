@extends('layouts.app')
@section('title', 'Catat Transaksi Inventaris')
@section('header', 'Transaksi Inventaris')
@section('subtitle', 'Catat stok masuk, stok keluar, atau alokasi ke petugas/area')

@section('content')
<div class="page-header"><div><h2>Form Transaksi</h2><p>Stok keluar dan alokasi akan mengurangi stok barang.</p></div><a href="{{ route('inventory-transactions.index') }}" class="btn-secondary">Riwayat Transaksi</a></div>
<div class="card form-card">
    <form method="POST" action="{{ route('inventory-transactions.store') }}">
        @csrf
        <div class="filter-grid" style="align-items:start;margin-bottom:16px;">
            <div class="form-field"><label for="inventory_item_id">Barang</label><select id="inventory_item_id" name="inventory_item_id" class="form-control" required><option value="">Pilih Barang</option>@foreach($items as $item)<option value="{{ $item->id }}" {{ old('inventory_item_id') == $item->id ? 'selected' : '' }}>{{ $item->name }} · stok {{ $item->current_stock }} {{ $item->unit }}</option>@endforeach</select>@error('inventory_item_id')<p class="field-error">{{ $message }}</p>@enderror</div>
            <div class="form-field"><label for="transaction_date">Tanggal</label><input id="transaction_date" name="transaction_date" type="date" value="{{ old('transaction_date', date('Y-m-d')) }}" class="form-control" required>@error('transaction_date')<p class="field-error">{{ $message }}</p>@enderror</div>
            <div class="form-field"><label for="type">Jenis</label><select id="type" name="type" class="form-control" required>@foreach($typeLabels as $value => $label)<option value="{{ $value }}" {{ old('type') === $value ? 'selected' : '' }}>{{ $label }}</option>@endforeach</select>@error('type')<p class="field-error">{{ $message }}</p>@enderror</div>
            <div class="form-field"><label for="quantity">Jumlah</label><input id="quantity" name="quantity" type="number" min="1" value="{{ old('quantity', 1) }}" class="form-control" required>@error('quantity')<p class="field-error">{{ $message }}</p>@enderror</div>
        </div>
        <div class="filter-grid" style="align-items:start;margin-bottom:16px;">
            <div class="form-field"><label for="employee_id">Petugas Penerima</label><select id="employee_id" name="employee_id" class="form-control"><option value="">Tidak terkait petugas</option>@foreach($employees as $employee)<option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }} — {{ $employee->area }}</option>@endforeach</select>@error('employee_id')<p class="field-error">{{ $message }}</p>@enderror</div>
            <div class="form-field"><label for="area">Area/Blok</label><input id="area" name="area" value="{{ old('area') }}" class="form-control" placeholder="Contoh: Blok A / Taman">@error('area')<p class="field-error">{{ $message }}</p>@enderror</div>
            <div class="form-field"><label for="source">Sumber / Tujuan</label><input id="source" name="source" value="{{ old('source') }}" class="form-control" placeholder="Toko, gudang, area tujuan...">@error('source')<p class="field-error">{{ $message }}</p>@enderror</div>
        </div>
        <div class="form-field"><label for="notes">Catatan</label><textarea id="notes" name="notes" class="form-control" style="min-height:100px;padding-top:12px;">{{ old('notes') }}</textarea>@error('notes')<p class="field-error">{{ $message }}</p>@enderror</div>
        <div class="form-actions"><button type="submit" class="btn-primary">Simpan Transaksi</button><a href="{{ route('inventories.index') }}" class="btn-secondary">Batal</a></div>
    </form>
</div>
@endsection
