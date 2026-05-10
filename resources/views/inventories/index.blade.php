@extends('layouts.app')
@section('title', 'Inventaris Barang')
@section('header', 'Inventaris Barang')
@section('subtitle', 'Kelola master barang dan pantau stok operasional')

@section('content')
@php
    $totalStock = $items->sum(fn ($item) => $item->current_stock);
@endphp
<div class="page-header">
    <div>
        <h2>Master Barang</h2>
        <p>Total {{ $items->count() }} barang · {{ $lowStockItems->count() }} perlu restock.</p>
    </div>
    <div class="form-actions" style="margin:0;">
        <a href="{{ route('inventory-transactions.create') }}" class="btn-primary">Catat Transaksi</a>
        <a href="{{ route('inventories.create') }}" class="btn-secondary">Tambah Barang</a>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card" style="--accent:#5e6640;--accent-soft:#eef1e6;"><p class="stat-label">Jenis Barang</p><p class="stat-value">{{ $items->count() }}</p><p class="stat-note">master barang</p></div>
    <div class="stat-card" style="--accent:#4f7d45;--accent-soft:#edf5e9;"><p class="stat-label">Total Stok</p><p class="stat-value">{{ $totalStock }}</p><p class="stat-note">akumulasi unit</p></div>
    <div class="stat-card" style="--accent:#c0392b;--accent-soft:#fff0ee;"><p class="stat-label">Stok Rendah</p><p class="stat-value">{{ $lowStockItems->count() }}</p><p class="stat-note">di/bawah minimum</p></div>
    <a href="{{ route('inventory-reports.usage') }}" class="stat-card" style="text-decoration:none;--accent:#d99a25;--accent-soft:#fff4df;"><p class="stat-label">Laporan</p><p class="stat-value">↗</p><p class="stat-note">penggunaan inventaris</p></a>
</div>

@if($lowStockItems->isNotEmpty())
    <div class="alert alert-error">
        Stok rendah: {{ $lowStockItems->map(fn ($item) => $item->name.' ('.$item->current_stock.' '.$item->unit.')')->join(', ') }}.
    </div>
@endif

<div class="table-card">
    <div class="table-header">
        <div><h3>Daftar Barang</h3><p>Stok dihitung dari stok masuk dikurangi stok keluar dan alokasi.</p></div>
    </div>
    <div class="table-responsive">
        <table class="app-table">
            <thead><tr><th>Barang</th><th>Satuan</th><th>Stok Saat Ini</th><th>Minimum</th><th>Status</th><th>Catatan</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td><strong>{{ $item->name }}</strong><div class="small-text">{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</div></td>
                        <td>{{ $item->unit }}</td>
                        <td><strong>{{ $item->current_stock }}</strong></td>
                        <td>{{ $item->minimum_stock }}</td>
                        <td><span class="status-badge {{ $item->is_low_stock ? 'status-absent' : 'status-on-time' }}">{{ $item->is_low_stock ? 'Stok Rendah' : 'Aman' }}</span></td>
                        <td>{{ $item->description ?? '-' }}</td>
                        <td><a href="{{ route('inventories.edit', $item) }}" class="btn-link-soft">Edit</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-muted" style="text-align:center;padding:42px;">Belum ada barang inventaris.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
