@extends('layouts.app')
@section('title', 'Laporan Bulanan')
@section('header', 'Laporan Bulanan')

@section('content')

<form method="GET" class="bg-white rounded-xl shadow-sm px-6 py-4 mb-6 flex flex-wrap gap-4 items-end">
    <div>
        <label class="block text-xs text-gray-500 mb-1">Bulan</label>
        <select name="month" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::createFromDate(null, $m, 1)->translatedFormat('F') }}
                </option>
            @endfor
        </select>
    </div>
    <div>
        <label class="block text-xs text-gray-500 mb-1">Tahun</label>
        <select name="year" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            @foreach($years as $y)
                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs text-gray-500 mb-1">Area / Blok</label>
        <select name="area" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Area</option>
            @foreach($areas as $areaOption)
                <option value="{{ $areaOption }}" {{ $areaOption === $area ? 'selected' : '' }}>{{ $areaOption }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
        Tampilkan
    </button>
    <a href="{{ route('reports.monthly.export', request()->only(['month', 'year', 'area'])) }}"
       class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">
        Export CSV
    </a>
</form>

<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <h3 class="font-semibold text-gray-800 mb-1">Grafik Kehadiran — {{ $bulan }}</h3>
    <p class="text-xs text-gray-400 mb-4">Hari kerja bulan ini: {{ $workingDays }} hari. Hari Minggu tidak dihitung.</p>
    <canvas id="attendanceChart" height="80"></canvas>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b">
        <h3 class="font-semibold text-gray-800">Performa Petugas — {{ $bulan }}</h3>
        <p class="text-xs text-gray-400 mt-1">Diurutkan berdasarkan skor tertinggi</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 text-left">Ranking</th>
                    <th class="px-6 py-3 text-left">Petugas</th>
                    <th class="px-6 py-3 text-center">Hadir</th>
                    <th class="px-6 py-3 text-center">Tepat Waktu</th>
                    <th class="px-6 py-3 text-center">Terlambat</th>
                    <th class="px-6 py-3 text-center">Tidak Hadir</th>
                    <th class="px-6 py-3 text-center">Avg. Telat</th>
                    <th class="px-6 py-3 text-center">Skor</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($employees as $i => $emp)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        @if($i === 0) 🥇
                        @elseif($i === 1) 🥈
                        @elseif($i === 2) 🥉
                        @else <span class="text-gray-500">#{{ $i + 1 }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-800">{{ $emp['name'] }}</div>
                        <div class="text-xs text-gray-400">{{ $emp['area'] }} · {{ ucfirst($emp['shift']) }}</div>
                    </td>
                    <td class="px-6 py-4 text-center text-gray-700">{{ $emp['hadir'] }}</td>
                    <td class="px-6 py-4 text-center"><span class="text-green-600 font-medium">{{ $emp['on_time'] }}</span></td>
                    <td class="px-6 py-4 text-center"><span class="text-orange-500 font-medium">{{ $emp['terlambat'] }}</span></td>
                    <td class="px-6 py-4 text-center"><span class="text-red-500 font-medium">{{ $emp['tidak_hadir'] }}</span></td>
                    <td class="px-6 py-4 text-center text-gray-600">{{ $emp['avg_terlambat'] > 0 ? $emp['avg_terlambat'] . ' mnt' : '-' }}</td>
                    <td class="px-6 py-4 text-center">
                        <div class="inline-flex items-center gap-1">
                            <div class="w-16 bg-gray-200 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full {{ $emp['skor'] >= 80 ? 'bg-green-500' : ($emp['skor'] >= 60 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                     style="width: {{ min($emp['skor'], 100) }}%"></div>
                            </div>
                            <span class="text-xs font-bold {{ $emp['skor'] >= 80 ? 'text-green-600' : ($emp['skor'] >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $emp['skor'] }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('reports.monthly.detail', ['employee' => $emp['id'], 'month' => $month, 'year' => $year]) }}"
                           class="text-blue-600 hover:underline text-xs font-medium">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-12 text-center text-gray-400">Belum ada data petugas untuk filter ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
const ctx = document.getElementById('attendanceChart');
if (ctx) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($employees->pluck('name')),
            datasets: [
                { label: 'Hadir', data: @json($employees->pluck('hadir')), backgroundColor: '#3b82f6' },
                { label: 'Tepat Waktu', data: @json($employees->pluck('on_time')), backgroundColor: '#22c55e' },
                { label: 'Terlambat', data: @json($employees->pluck('terlambat')), backgroundColor: '#f97316' },
                { label: 'Tidak Hadir', data: @json($employees->pluck('tidak_hadir')), backgroundColor: '#ef4444' }
            ]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true, precision: 0 } } }
    });
}
</script>
@endsection
