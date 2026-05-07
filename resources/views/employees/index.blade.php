@extends('layouts.app')
@section('title', 'Data Petugas')
@section('header', 'Data Petugas')

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b flex justify-between items-center">
        <h3 class="font-semibold text-gray-800">Daftar Petugas Aktif</h3>
        <a href="{{ route('employees.create') }}"
           class="bg-blue-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-700">
            + Tambah Petugas
        </a>
    </div>

    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-6 py-3 text-left">Nama / Kode</th>
                <th class="px-6 py-3 text-left">Area</th>
                <th class="px-6 py-3 text-left">Shift</th>
                <th class="px-6 py-3 text-left">No. HP</th>
                <th class="px-6 py-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($employees as $emp)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="font-medium text-gray-800">{{ $emp->name }}</div>
                    <div class="text-xs text-gray-400">{{ $emp->employee_code }}</div>
                </td>
                <td class="px-6 py-4 text-gray-600">{{ $emp->area }}</td>
                <td class="px-6 py-4">
                    <span class="capitalize bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full">
                        {{ $emp->shift }}
                    </span>
                </td>
                <td class="px-6 py-4 text-gray-600">{{ $emp->phone ?? '-' }}</td>
                <td class="px-6 py-4">
                    <div class="flex gap-2">
                        <a href="{{ route('employees.edit', $emp) }}"
                           class="text-blue-600 hover:underline text-xs">Edit</a>
                        <form method="POST" action="{{ route('employees.destroy', $emp) }}"
                              onsubmit="return confirm('Nonaktifkan petugas ini?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:underline text-xs">Nonaktifkan</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                    Belum ada petugas. <a href="{{ route('employees.create') }}" class="text-blue-600">Tambah sekarang</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection