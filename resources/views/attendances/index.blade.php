@extends('layouts.app')
@section('title', 'Absensi Hari Ini')
@section('header', 'Absensi Hari Ini')

@section('content')

{{-- Counter Cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl p-5 shadow-sm border-l-4 border-blue-500">
        <p class="text-xs text-gray-500 uppercase tracking-wide">Total Hadir</p>
        <p class="text-3xl font-bold text-blue-600 mt-1">{{ $counter['hadir'] }}</p>
        <p class="text-xs text-gray-400 mt-1">petugas</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border-l-4 border-green-500">
        <p class="text-xs text-gray-500 uppercase tracking-wide">Tepat Waktu</p>
        <p class="text-3xl font-bold text-green-600 mt-1">{{ $counter['on_time'] }}</p>
        <p class="text-xs text-gray-400 mt-1">petugas</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border-l-4 border-orange-500">
        <p class="text-xs text-gray-500 uppercase tracking-wide">Terlambat</p>
        <p class="text-3xl font-bold text-orange-500 mt-1">{{ $counter['terlambat'] }}</p>
        <p class="text-xs text-gray-400 mt-1">petugas</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border-l-4 border-red-500">
        <p class="text-xs text-gray-500 uppercase tracking-wide">Belum Absen</p>
        <p class="text-3xl font-bold text-red-500 mt-1">{{ $counter['belum_absen'] }}</p>
        <p class="text-xs text-gray-400 mt-1">petugas</p>
    </div>
</div>

{{-- Tabel Absensi Hari Ini --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b flex justify-between items-center">
        <h3 class="font-semibold text-gray-800">
            Rekap — {{ \Carbon\Carbon::parse($today)->translatedFormat('d F Y') }}
        </h3>
        <a href="{{ route('attendances.create') }}"
           class="bg-blue-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-700">
            + Input Absensi
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 text-left">Petugas</th>
                    <th class="px-6 py-3 text-left">Area / Shift</th>
                    <th class="px-6 py-3 text-left">Check In</th>
                    <th class="px-6 py-3 text-left">Check Out</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Keterlambatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($attendances as $att)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-800">
                        {{ $att->employee->name }}
                        <div class="text-xs text-gray-400">{{ $att->employee->employee_code }}</div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $att->employee->area }}
                        <div class="text-xs text-gray-400 capitalize">{{ $att->employee->shift }}</div>
                    </td>
                    <td class="px-6 py-4 font-mono text-gray-800">
                        {{ $att->check_in ? \Carbon\Carbon::parse($att->check_in)->format('H:i') : '--:--' }}
                    </td>
                    <td class="px-6 py-4 font-mono text-gray-800">
                        {{ $att->check_out ? \Carbon\Carbon::parse($att->check_out)->format('H:i') : '--:--' }}
                    </td>
                    <td class="px-6 py-4">
                        @if($att->status === 'on_time')
                            <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full font-medium">
                                Tepat Waktu
                            </span>
                        @elseif($att->status === 'late')
                            <span class="bg-orange-100 text-orange-700 text-xs px-2 py-1 rounded-full font-medium">
                                Terlambat
                            </span>
                        @else
                            <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full font-medium">
                                Tidak Hadir
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $att->late_minutes > 0 ? $att->late_minutes . ' menit' : '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        Belum ada data absensi hari ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection