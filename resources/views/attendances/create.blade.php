@extends('layouts.app')
@section('title', 'Input Absensi')
@section('header', 'Input Absensi')

@section('content')
<div class="max-w-lg mx-auto">

    {{-- Check In / Check Out --}}
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h3 class="font-semibold text-gray-800 mb-4">Check In / Check Out</h3>

        <form method="POST" action="{{ route('attendances.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Petugas</label>
                <select name="employee_id" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Petugas --</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">
                            {{ $emp->name }} — {{ $emp->area }} ({{ ucfirst($emp->shift) }})
                        </option>
                    @endforeach
                </select>
                @error('employee_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Absensi</label>
                <div class="flex gap-3">
                    <label class="flex-1 border-2 border-gray-200 rounded-lg p-3 cursor-pointer
                                  has-[:checked]:border-green-500 has-[:checked]:bg-green-50">
                        <input type="radio" name="type" value="check_in" class="sr-only" checked>
                        <div class="text-center">
                            <div class="text-2xl mb-1">🟢</div>
                            <div class="text-sm font-medium text-gray-800">Check In</div>
                            <div class="text-xs text-gray-400">Masuk kerja</div>
                        </div>
                    </label>
                    <label class="flex-1 border-2 border-gray-200 rounded-lg p-3 cursor-pointer
                                  has-[:checked]:border-red-500 has-[:checked]:bg-red-50">
                        <input type="radio" name="type" value="check_out" class="sr-only">
                        <div class="text-center">
                            <div class="text-2xl mb-1">🔴</div>
                            <div class="text-sm font-medium text-gray-800">Check Out</div>
                            <div class="text-xs text-gray-400">Selesai kerja</div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Jam sekarang --}}
            <div class="bg-gray-50 rounded-lg px-4 py-3 mb-4 text-center">
                <p class="text-xs text-gray-500">Waktu Sekarang</p>
                <p class="text-2xl font-mono font-bold text-gray-800" id="clock">--:--:--</p>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-lg font-medium
                           hover:bg-blue-700 transition">
                Simpan Absensi
            </button>
        </form>
    </div>

    {{-- Form Tandai Tidak Hadir --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Tandai Tidak Hadir</h3>
        <form method="POST" action="{{ route('attendances.absent') }}">
            @csrf
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Petugas</label>
                <select name="employee_id" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-400">
                    <option value="">-- Pilih Petugas --</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                <input type="date" name="date" value="{{ date('Y-m-d') }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-400">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <input type="text" name="notes" placeholder="Sakit, izin, dll..."
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-400">
            </div>
            <button type="submit"
                    class="w-full bg-red-500 text-white py-2 rounded-lg text-sm font-medium hover:bg-red-600 transition">
                Tandai Tidak Hadir
            </button>
        </form>
    </div>
</div>

<script>
    // Live clock
    function updateClock() {
        const now = new Date();
        document.getElementById('clock').textContent =
            now.toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit', second: '2-digit'});
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
@endsection