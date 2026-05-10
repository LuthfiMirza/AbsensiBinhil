@extends('layouts.app')
@section('title', 'Edit Barang')
@section('header', 'Edit Barang')
@section('subtitle', 'Perbarui master barang inventaris')

@section('content')
<div class="page-header"><div><h2>{{ $item->name }}</h2><p>Stok saat ini {{ $item->current_stock }} {{ $item->unit }}.</p></div><a href="{{ route('inventories.index') }}" class="btn-secondary">Kembali</a></div>
<div class="card form-card">
    <form method="POST" action="{{ route('inventories.update', $item) }}">
        @csrf @method('PUT')
        @include('inventories.partials.form', ['item' => $item])
    </form>
    <form method="POST" action="{{ route('inventories.destroy', $item) }}" onsubmit="return confirm('Nonaktifkan barang ini?')" style="margin-top:12px;">
        @csrf @method('DELETE')
        <button type="submit" class="btn-danger">Nonaktifkan Barang</button>
    </form>
</div>
@endsection
