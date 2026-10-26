@extends('layouts.admin')

@php
    $title = 'Tambah Pengguna Baru';
@endphp

@push('styles')
<style>
    /* =========================================================
       ADMIN - TAMBAH PENGGUNA (BPS DESIGN SYSTEM)
    ========================================================= */
    :root {
        --bps-navy: #082052;
        --bps-blue: #024b86;
        --bps-blue-light: #e0f2fe;
        --bps-blue-subtle: #f0f7ff;
        
        --bps-orange: #f37021;
        --bps-orange-dark: #ea580c;
        --bps-orange-light: #fff7ed;

        --bps-green: #16a34a;
        --bps-green-dark: #15803d;

        --dash-slate-800: #1e293b;
        --dash-slate-700: #334155;
        --dash-slate-600: #475569;
        --dash-slate-500: #64748b;
        --dash-slate-400: #94a3b8;
        --dash-slate-200: #e2e8f0;
        --dash-slate-100: #f1f5f9;
        --dash-slate-50: #f8fafc;

        --dash-radius-xl: 16px;
        --dash-radius-lg: 12px;
        --dash-radius-md: 8px;
    }

    .form-page-wrapper {
        display: flex;
        flex-direction: column;
        gap: 20px;
        width: 100%;
        max-width: 960px;
        margin: 0 auto;
        color: var(--dash-slate-800);
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    }

    .form-header-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        background: #ffffff;
        padding: 20px 24px;
        border-radius: var(--dash-radius-xl);
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(8, 32, 82, 0.05);
    }

    .form-header-title h2 {
        margin: 0 0 4px 0;
        font-size: 20px;
        font-weight: 800;
        color: var(--bps-navy);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-header-title p {
        margin: 0;
        color: var(--dash-slate-500);
        font-size: 13px;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 15px;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: var(--dash-radius-md);
        color: var(--dash-slate-700);
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-back:hover {
        background: #f1f5f9;
        color: var(--bps-navy);
        border-color: #94a3b8;
    }

    .form-card {
        background: #ffffff;
        border-radius: var(--dash-radius-xl);
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(8, 32, 82, 0.05);
        overflow: hidden;
    }

    .form-card-header {
        padding: 18px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 12px;
        background: #fafafa;
    }

    .form-card-icon {
        width: 38px;
        height: 38px;
        border-radius: var(--dash-radius-md);
        background: var(--bps-blue-light);
        color: var(--bps-blue);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .form-card-header h3 {
        margin: 0;
        font-size: 15px;
        font-weight: 800;
        color: var(--bps-navy);
    }

    .form-card-header p {
        margin: 2px 0 0 0;
        font-size: 12px;
        color: var(--dash-slate-500);
    }

    .form-body {
        padding: 24px;
    }

    .form-grid-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 20px;
    }

    .form-label {
        font-size: 13px;
        font-weight: 700;
        color: var(--dash-slate-700);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-label i {
        color: var(--bps-blue);
        font-size: 14px;
    }

    .input-wrapper {
        position: relative;
    }

    .form-input, .form-select {
        width: 100%;
        padding: 10px 14px;
        border-radius: var(--dash-radius-md);
        border: 1px solid #cbd5e1;
        font-size: 13.5px;
        color: var(--dash-slate-800);
        background: #ffffff;
        outline: none;
        transition: all 0.2s ease;
        font-family: inherit;
        box-sizing: border-box;
    }

    .form-input:focus, .form-select:focus {
        border-color: var(--bps-blue);
        box-shadow: 0 0 0 3px rgba(2, 75, 134, 0.12);
    }

    .form-error {
        color: #dc2626;
        font-size: 12px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
        margin-top: 4px;
    }

    .form-hint {
        color: var(--dash-slate-400);
        font-size: 11.5px;
        margin-top: 3px;
    }

    .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 15px;
        border-top: 1px solid #f1f5f9;
        margin-top: 10px;
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 22px;
        background: linear-gradient(135deg, var(--bps-orange) 0%, var(--bps-orange-dark) 100%);
        color: #ffffff;
        font-size: 13px;
        font-weight: 700;
        border-radius: var(--dash-radius-md);
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(243, 112, 33, 0.35);
        transition: all 0.2s ease;
    }

    .btn-submit:hover {
        background: linear-gradient(135deg, var(--bps-orange-dark) 0%, #c2410c 100%);
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(243, 112, 33, 0.45);
    }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 18px;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: var(--dash-radius-md);
        color: var(--dash-slate-600);
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-cancel:hover {
        background: #f1f5f9;
        color: var(--dash-slate-800);
    }

    @media (max-width: 640px) {
        .form-grid-2 {
            grid-template-columns: 1fr;
            gap: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="form-page-wrapper">

    {{-- HEADER --}}
    <div class="form-header-box">
        <div class="form-header-title">
            <h2>
                <i class="bi bi-person-plus-fill" style="color: var(--bps-blue);"></i>
                Tambah Pengguna Baru
            </h2>
            <p>Daftarkan akun pegawai baru untuk dapat mengakses sistem penyelesaian tagihan SPJ.</p>
        </div>

        <a href="{{ route('admin.pengguna.index') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    {{-- FORM CARD --}}
    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-icon">
                <i class="bi bi-person-vcard"></i>
            </div>
            <div>
                <h3>Informasi Akun Pegawai</h3>
                <p>Isi formulir berikut dengan data akun yang valid dan sesuai peran kewenangan.</p>
            </div>
        </div>

        <form action="{{ route('admin.pengguna.store') }}" method="POST" class="form-body">
            @csrf

            <div class="form-grid-2">
                {{-- NAMA LENGKAP --}}
                <div class="form-group">
                    <label for="name" class="form-label">
                        <i class="bi bi-person"></i> Nama Lengkap / Username <span style="color: #dc2626;">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        class="form-input"
                        placeholder="Contoh: Muhammad Habil atau teknis"
                        required>
                    @error('name')
                        <span class="form-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</span>
                    @enderror
                </div>

                {{-- EMAIL --}}
                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="bi bi-envelope"></i> Alamat Email <span style="color: #dc2626;">*</span>
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-input"
                        placeholder="contoh@bps.go.id atau user@gmail.com"
                        required>
                    @error('email')
                        <span class="form-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-grid-2">
                {{-- WHATSAPP --}}
                <div class="form-group">
                    <label for="no_wa" class="form-label">
                        <i class="bi bi-whatsapp" style="color: #16a34a;"></i> Nomor WhatsApp (Opsional)
                    </label>
                    <input
                        type="text"
                        id="no_wa"
                        name="no_wa"
                        value="{{ old('no_wa') }}"
                        class="form-input"
                        placeholder="081234567890">
                    <span class="form-hint">Digunakan untuk integrasi notifikasi dan koordinasi cepat SPJ.</span>
                    @error('no_wa')
                        <span class="form-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</span>
                    @enderror
                </div>

                {{-- PASSWORD --}}
                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="bi bi-key"></i> Kata Sandi (Password) <span style="color: #dc2626;">*</span>
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input"
                        placeholder="Minimal 8 karakter"
                        required>
                    <span class="form-hint">Gunakan kombinasi huruf dan angka untuk keamanan optimal.</span>
                    @error('password')
                        <span class="form-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-grid-2">
                {{-- ROLE --}}
                <div class="form-group">
                    <label for="role" class="form-label">
                        <i class="bi bi-shield-lock"></i> Hak Akses (Role) <span style="color: #dc2626;">*</span>
                    </label>
                    <select id="role" name="role" class="form-select" required>
                        <option value="">-- Pilih Peran / Hak Akses --</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator (Kelola Sistem & Master Data)</option>
                        <option value="teknis" {{ old('role') == 'teknis' ? 'selected' : '' }}>Staf Teknis (Pengajuan SPJ & Dokumen)</option>
                        <option value="umum" {{ old('role') == 'umum' ? 'selected' : '' }}>Bagian Umum (Verifikasi Kelengkapan Awal)</option>
                        <option value="ppk" {{ old('role') == 'ppk' ? 'selected' : '' }}>PPK (Pejabat Pembuat Komitmen)</option>
                        <option value="ppspm" {{ old('role') == 'ppspm' ? 'selected' : '' }}>PPSPM (Persetujuan Akhir SPM)</option>
                    </select>
                    @error('role')
                        <span class="form-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</span>
                    @enderror
                </div>

                {{-- STATUS --}}
                <div class="form-group">
                    <label for="status" class="form-label">
                        <i class="bi bi-toggle-on"></i> Status Keaktifan <span style="color: #dc2626;">*</span>
                    </label>
                    <select id="status" name="status" class="form-select" required>
                        <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif (Dapat Login ke Sistem)</option>
                        <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif (Akses Sementara Dibatasi)</option>
                    </select>
                    @error('status')
                        <span class="form-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- FORM ACTIONS --}}
            <div class="form-actions">
                <a href="{{ route('admin.pengguna.index') }}" class="btn-cancel">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
                <button type="submit" class="btn-submit">
                    <i class="bi bi-check2-circle"></i> Simpan Pengguna
                </button>
            </div>
        </form>
    </div>

</div>
@endsection