@extends('layouts.app')
@section('title', 'Edit Petugas')
@section('header', 'Edit Petugas')

@section('content')
<div class="max-w-lg mx-auto bg-white rounded-xl shadow-sm p-6">
    <form method="POST" action="{{ route('employees.update', $employee) }}">
        @csrf @method('PUT')
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $employee->name) }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Petugas</label>
                <input type="text" value="{{ $employee->employee_code }}" disabled
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 text-gray-400">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Area Tugas</label>
                <input type="text" name="area" value="{{ old('area', $employee->area) }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Shift</label>
                <select name="shift" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="pagi"  {{ $employee->shift === 'pagi'  ? 'selected' : '' }}>Pagi (06:00 - 14:00)</option>
                    <option value="siang" {{ $employee->shift === 'siang' ? 'selected' : '' }}>Siang (14:00 - 22:00)</option>
                    <option value="sore"  {{ $employee->shift === 'sore'  ? 'selected' : '' }}>Sore (22:00 - 06:00)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
        <div class="flex gap-3 mt-6">
            <button type="submit"
                    class="flex-1 bg-blue-600 text-white py-2 rounded-lg font-medium hover:bg-blue-700 transition">
                Update
            </button>
            <a href="{{ route('employees.index') }}"
               class="flex-1 text-center border border-gray-300 text-gray-700 py-2 rounded-lg hover:bg-gray-50">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection