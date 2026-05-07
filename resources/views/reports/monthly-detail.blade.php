@extends('layouts.app')
@section('title', 'Detail Laporan Bulanan')
@section('header', 'Detail Laporan Bulanan')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6 mb-6 flex flex-wrap justify-between gap-4">
    <div>
        <h3 class="font-semibold text-gray-800">{{ $employee->name }} — {{ $bulan }}</h3>
        <p class="text-sm text-gray-500 mt-1">{{ $employee->employee_code }} · {{ $employee->area }} · {{ ucfirst($employee->shift) }}</p>
        <p class="text-xs text-gray-400 mt-1">Hari kerja bulan ini: {{ $workingDays }} hari. Hari Minggu ditandai libur.</p>
    </div>
    <a href="{{ route('reports.monthly', ['month' => $month, 'year' => $year]) }}"
       class="self-start border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">
        Kembali
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 text-left">Tanggal</th>
                    <th class="px-6 py-3 text-left">Area / Blok</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Jam Masuk</th>
                    <th class="px-6 py-3 text-left">Jam Pulang</th>
                    <th class="px-6 py-3 text-left">Menit Terlambat</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($days as $day)
                <tr class="hover:bg-gray-50 {{ ! $day['is_working_day'] ? 'bg-gray-50' : '' }}">
                    <td class="px-6 py-4 text-gray-700">
                        {{ $day['date']->translatedFormat('d F Y') }}
                        <div class="text-xs text-gray-400">{{ $day['date']->translatedFormat('l') }}</div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $day['area'] }}</td>
                    <td class="px-6 py-4">
                        @if(! $day['is_working_day'])
                            <span class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded-full font-medium">Libur</span>
                        @elseif($day['status'] === 'on_time')
                            <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full font-medium">Tepat Waktu</span>
                        @elseif($day['status'] === 'late')
                            <span class="bg-orange-100 text-orange-700 text-xs px-2 py-1 rounded-full font-medium">Terlambat</span>
                        @elseif($day['status'] === 'absent')
                            <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full font-medium">Absent</span>
                        @else
                            <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full font-medium">Belum ada data</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-mono text-gray-800">{{ $day['check_in'] ? \Carbon\Carbon::parse($day['check_in'])->format('H:i') : '--:--' }}</td>
                    <td class="px-6 py-4 font-mono text-gray-800">{{ $day['check_out'] ? \Carbon\Carbon::parse($day['check_out'])->format('H:i') : '--:--' }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $day['late_minutes'] > 0 ? $day['late_minutes'].' menit' : '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
