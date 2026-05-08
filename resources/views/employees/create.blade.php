@extends('layouts.app')
@section('title', 'Tambah Petugas')
@section('header', 'Tambah Petugas')
@section('subtitle', 'Tambahkan petugas baru beserta area dan shift kerja')

@section('content')
<style>
    .employee-create-page {
        max-width: 1280px;
        width: 100%;
        margin: 0 auto;
    }

    .employee-create-page .employee-create-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: 22px;
        align-items: start;
    }

    .employee-create-page .form-card {
        max-width: none;
        width: 100%;
        margin: 0;
    }

    .employee-create-page .employee-form-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: 18px;
        align-items: start;
    }

    .employee-create-page .employee-side-panel {
        display: grid;
        gap: 18px;
    }

    .employee-create-page .guide-list {
        margin: 0;
        padding-left: 18px;
        color: var(--color-muted);
        line-height: 1.7;
    }

    .employee-create-page .access-link-box {
        padding: 16px;
        border-radius: 16px;
        background: #fafaf7;
        border: 1px solid rgba(94, 102, 64, .12);
    }

    .employee-create-page .access-link-box code {
        display: block;
        margin-top: 8px;
        color: var(--color-primary);
        white-space: normal;
        word-break: break-all;
    }

    @media (min-width: 760px) {
        .employee-create-page .employee-form-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .employee-create-page .employee-form-grid .full-span {
            grid-column: 1 / -1;
        }
    }

    @media (min-width: 1040px) {
        .employee-create-page .employee-create-grid {
            grid-template-columns: minmax(0, 1fr) minmax(340px, .42fr);
        }
    }
</style>

<div class="employee-create-page">
    <div class="page-header">
        <div>
            <h2>Form Petugas Baru</h2>
            <p>Lengkapi data petugas sesuai area kerja.</p>
        </div>
        <a href="{{ route('employees.index') }}" class="btn-secondary">Kembali</a>
    </div>

    <div class="employee-create-grid">
        <div class="card form-card">
            <form method="POST" action="{{ route('employees.store') }}">
                @csrf

                <div class="table-header" style="padding:0 0 18px;margin-bottom:22px;">
                    <div>
                        <h3>Data Petugas</h3>
                        <p>Informasi ini dipakai untuk area kerja, shift, dan rekap absensi.</p>
                    </div>
                </div>

                <div class="employee-form-grid">
                    <div class="form-field">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="form-control">
                        @error('name')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-field">
                        <label>Kode Petugas</label>
                        <input type="text" name="employee_code" value="{{ old('employee_code') }}" placeholder="PTG-001" required class="form-control">
                        @error('employee_code')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-field">
                        <label>Area/Blok</label>
                        <input type="text" name="area" value="{{ old('area') }}" placeholder="Blok A" required class="form-control">
                        @error('area')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-field">
                        <label>Shift</label>
                        <select name="shift" required class="form-control">
                            <option value="">Pilih Shift</option>
                            <option value="pagi" {{ old('shift') === 'pagi' ? 'selected' : '' }}>Pagi (06:00 - 14:00)</option>
                            <option value="siang" {{ old('shift') === 'siang' ? 'selected' : '' }}>Siang (14:00 - 22:00)</option>
                            <option value="sore" {{ old('shift') === 'sore' ? 'selected' : '' }}>Sore (22:00 - 06:00)</option>
                        </select>
                        @error('shift')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-field full-span">
                        <label>No. HP</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" class="form-control">
                        @error('phone')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="table-header" style="padding:24px 0 12px;margin-top:8px;">
                    <div>
                        <h3>Akun Login Petugas</h3>
                        <p>Email dan password ini digunakan petugas untuk masuk ke halaman Absensi Saya.</p>
                    </div>
                </div>

                <div class="employee-form-grid">
                    <div class="form-field full-span">
                        <label>Email Login</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="petugas@email.com" required class="form-control">
                        @error('email')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-field">
                        <label>Password</label>
                        <input type="password" name="password" required class="form-control">
                        @error('password')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-field">
                        <label>Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required class="form-control">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">Simpan Petugas</button>
                    <a href="{{ route('employees.index') }}" class="btn-secondary">Batal</a>
                </div>
            </form>
        </div>

        <aside class="employee-side-panel">
            <div class="card form-card">
                <div class="table-header" style="padding:0 0 14px;margin-bottom:16px;">
                    <div>
                        <h3>Panduan Akun Petugas</h3>
                        <p>Pastikan data akun mudah digunakan petugas saat login.</p>
                    </div>
                </div>
                <ul class="guide-list">
                    <li>Email login harus unik dan aktif digunakan petugas.</li>
                    <li>Password wajib diisi dan dikonfirmasi sama persis.</li>
                    <li>Akun otomatis dibuat dengan role <code>employee</code>.</li>
                    <li>Akun otomatis terhubung ke data petugas yang baru dibuat.</li>
                </ul>
            </div>

            <div class="card form-card">
                <div class="table-header" style="padding:0 0 14px;margin-bottom:16px;">
                    <div>
                        <h3>Akses Petugas</h3>
                        <p>Setelah disimpan, petugas bisa langsung login memakai email dan password yang dibuat.</p>
                    </div>
                </div>
                <div class="access-link-box">
                    <strong>Halaman login</strong>
                    <code>{{ url('/login') }}</code>
                </div>
                <div class="access-link-box" style="margin-top:12px;">
                    <strong>Halaman absensi petugas</strong>
                    <code>{{ url('/my-attendance') }}</code>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
