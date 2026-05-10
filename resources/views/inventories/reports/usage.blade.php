@extends('layouts.app')
@section('title', 'Laporan Penggunaan Inventaris')
@section('header', 'Laporan Inventaris')
@section('subtitle', 'Rekap stok masuk, keluar, alokasi, dan alert stok rendah')

@section('content')
@php
    $totalIn = $items->sum('stock_in');
    $totalOut = $items->sum('stock_out');
    $totalAllocated = $items->sum('allocated');
    $lowStock = $items->where('is_low_stock', true)->count();
@endphp

<div class="page-header">
    <div>
        <h2>Penggunaan Inventaris</h2>
        <p>Periode {{ \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') }}.</p>
    </div>
    <a href="{{ route('inventories.index') }}" class="btn-secondary">Master Barang</a>
</div>

<form method="GET" class="filter-card">
    <div class="filter-grid">
        <div class="form-field">
            <label for="month">Bulan</label>
            <select id="month" name="month" class="form-control">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ \Carbon\Carbon::createFromDate(null, $m, 1)->translatedFormat('F') }}</option>
                @endfor
            </select>
        </div>
        <div class="form-field">
            <label for="year">Tahun</label>
            <select id="year" name="year" class="form-control">
                @foreach($years as $y)
                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-field">
            <label for="item_id">Barang</label>
            <select id="item_id" name="item_id" class="form-control">
                <option value="">Semua Barang</option>
                @foreach($masterItems as $masterItem)
                    <option value="{{ $masterItem->id }}" {{ $itemId === $masterItem->id ? 'selected' : '' }}>{{ $masterItem->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-field">
            <label for="area">Area</label>
            <select id="area" name="area" class="form-control">
                <option value="">Semua Area</option>
                @foreach($areas as $areaOption)
                    <option value="{{ $areaOption }}" {{ $area === $areaOption ? 'selected' : '' }}>{{ $areaOption }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn-primary" type="submit">Terapkan</button>
        <a href="{{ route('inventory-reports.usage') }}" class="btn-secondary">Reset</a>
    </div>
</form>

<div class="stats-grid">
    <div class="stat-card" style="--accent:#4f7d45;--accent-soft:#edf5e9;"><p class="stat-label">Stok Masuk</p><p class="stat-value">{{ $totalIn }}</p><p class="stat-note">periode ini</p></div>
    <div class="stat-card" style="--accent:#d99a25;--accent-soft:#fff4df;"><p class="stat-label">Stok Keluar</p><p class="stat-value">{{ $totalOut }}</p><p class="stat-note">pemakaian umum</p></div>
    <div class="stat-card" style="--accent:#3d6f82;--accent-soft:#eef3f6;"><p class="stat-label">Alokasi</p><p class="stat-value">{{ $totalAllocated }}</p><p class="stat-note">ke petugas/area</p></div>
    <div class="stat-card" style="--accent:#c0392b;--accent-soft:#fff0ee;"><p class="stat-label">Stok Rendah</p><p class="stat-value">{{ $lowStock }}</p><p class="stat-note">butuh perhatian</p></div>
</div>

<div class="table-card">
    <div class="table-header">
        <div>
            <h3>Rekap per Barang</h3>
            <p>Stok saat ini dihitung dari seluruh transaksi historis; masuk/keluar/alokasi mengikuti filter periode.</p>
        </div>
    </div>
    <div class="table-responsive">
        <table class="app-table">
            <thead><tr><th>Barang</th><th>Masuk</th><th>Keluar</th><th>Alokasi</th><th>Stok Saat Ini</th><th>Minimum</th><th>Status</th></tr></thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td><strong>{{ $item['name'] }}</strong><div class="small-text">{{ $item['unit'] }}</div></td>
                        <td>{{ $item['stock_in'] }}</td>
                        <td>{{ $item['stock_out'] }}</td>
                        <td>{{ $item['allocated'] }}</td>
                        <td><strong>{{ $item['current_stock'] }}</strong></td>
                        <td>{{ $item['minimum_stock'] }}</td>
                        <td><span class="status-badge {{ $item['is_low_stock'] ? 'status-absent' : 'status-on-time' }}">{{ $item['is_low_stock'] ? 'Stok Rendah' : 'Aman' }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-muted" style="text-align:center;padding:42px;">Belum ada data barang.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
