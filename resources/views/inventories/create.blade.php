@extends('layouts.app')
@section('title', 'Tambah Barang')
@section('header', 'Tambah Barang')
@section('subtitle', 'Tambahkan master barang inventaris')

@section('content')
<div class="page-header"><div><h2>Form Barang</h2><p>Isi nama barang, satuan, dan batas minimum stok.</p></div><a href="{{ route('inventories.index') }}" class="btn-secondary">Kembali</a></div>
<div class="card form-card">
    <form method="POST" action="{{ route('inventories.store') }}">
        @csrf
        @include('inventories.partials.form', ['item' => null])
    </form>
</div>
@endsection
