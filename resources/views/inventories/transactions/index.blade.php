@extends('layouts.app')
@section('title', 'Riwayat Inventaris')
@section('header', 'Riwayat Inventaris')
@section('subtitle', 'Pantau stok masuk, stok keluar, dan alokasi barang')

@section('content')
<div class="page-header"><div><h2>Transaksi Barang</h2><p>Filter transaksi berdasarkan barang, tipe, area, dan periode.</p></div><a href="{{ route('inventory-transactions.create') }}" class="btn-primary">Catat Transaksi</a></div>
<form method="GET" class="filter-card"><div class="filter-grid">
    <div class="form-field"><label for="item_id">Barang</label><select id="item_id" name="item_id" class="form-control"><option value="">Semua</option>@foreach($items as $item)<option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>@endforeach</select></div>
    <div class="form-field"><label for="type">Jenis</label><select id="type" name="type" class="form-control"><option value="">Semua</option>@foreach($typeLabels as $value => $label)<option value="{{ $value }}" {{ request('type') === $value ? 'selected' : '' }}>{{ $label }}</option>@endforeach</select></div>
    <div class="form-field"><label for="area">Area</label><select id="area" name="area" class="form-control"><option value="">Semua</option>@foreach($areas as $area)<option value="{{ $area }}" {{ request('area') === $area ? 'selected' : '' }}>{{ $area }}</option>@endforeach</select></div>
    <div class="form-field"><label for="month">Bulan</label><input id="month" name="month" type="number" min="1" max="12" value="{{ request('month') }}" class="form-control" placeholder="1-12"></div>
    <div class="form-field"><label for="year">Tahun</label><input id="year" name="year" type="number" value="{{ request('year') }}" class="form-control" placeholder="{{ date('Y') }}"></div>
    <button class="btn-primary" type="submit">Filter</button><a href="{{ route('inventory-transactions.index') }}" class="btn-secondary">Reset</a>
</div></form>
<div class="table-card"><div class="table-header"><div><h3>Riwayat Transaksi</h3><p>Data terbaru tampil paling atas.</p></div></div><div class="table-responsive"><table class="app-table"><thead><tr><th>Tanggal</th><th>Barang</th><th>Jenis</th><th>Jumlah</th><th>Petugas/Area</th><th>Sumber/Tujuan</th><th>Catatan</th></tr></thead><tbody>
@forelse($transactions as $trx)<tr><td>{{ $trx->transaction_date->translatedFormat('d M Y') }}</td><td><strong>{{ $trx->item?->name }}</strong></td><td><span class="status-badge {{ $trx->type === 'in' ? 'status-on-time' : ($trx->type === 'allocation' ? 'status-permission' : 'status-late') }}">{{ $trx->type_label }}</span></td><td>{{ $trx->quantity }} {{ $trx->item?->unit }}</td><td>{{ $trx->employee?->name ?? '-' }}<div class="small-text">{{ $trx->area ?? $trx->employee?->area ?? '-' }}</div></td><td>{{ $trx->source ?? '-' }}</td><td>{{ $trx->notes ?? '-' }}</td></tr>@empty<tr><td colspan="7" class="text-muted" style="text-align:center;padding:42px;">Belum ada transaksi.</td></tr>@endforelse
</tbody></table></div><div style="padding:16px;">{{ $transactions->links() }}</div></div>
@endsection
