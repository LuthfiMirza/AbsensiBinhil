@extends('layouts.app')
@section('title', 'Tambah Petugas')
@section('header', 'Tambah Petugas')

@section('content')
<div class="max-w-lg mx-auto bg-white rounded-xl shadow-sm p-6">
    <form method="POST" action="{{ route('employees.store') }}">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Petugas</label>
                <input type="text" name="employee_code" value="{{ old('employee_code') }}"
                       placeholder="cth: PTG-001" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('employee_code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Area Tugas</label>
                <input type="text" name="area" value="{{ old('area') }}"
                       placeholder="cth: Blok A, Taman Depan..." required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Shift</label>
                <select name="shift" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Shift --</option>
                    <option value="pagi"  {{ old('shift') === 'pagi'  ? 'selected' : '' }}>Pagi (06:00 - 14:00)</option>
                    <option value="siang" {{ old('shift') === 'siang' ? 'selected' : '' }}>Siang (14:00 - 22:00)</option>
                    <option value="sore"  {{ old('shift') === 'sore'  ? 'selected' : '' }}>Sore (22:00 - 06:00)</option>
                </select>
                @error('shift')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">No. HP (opsional)</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                       placeholder="08xxxxxxxxxx"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div class="flex gap-3 mt-6">
            <button type="submit"
                    class="flex-1 bg-blue-600 text-white py-2 rounded-lg font-medium hover:bg-blue-700 transition">
                Simpan
            </button>
            <a href="{{ route('employees.index') }}"
               class="flex-1 text-center border border-gray-300 text-gray-700 py-2 rounded-lg hover:bg-gray-50 transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection