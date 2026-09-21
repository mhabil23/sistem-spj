@extends('layouts.teknis')

@php
$title = 'Buat SPJ Baru';
$subtitle = 'Formulir pengajuan Surat Pertanggungjawaban (SPJ).';
@endphp

@section('content')

<div class="page-heading">
    <div>
        <h2>Pengajuan SPJ</h2>
        <p>Isi formulir berikut dengan lengkap untuk mengajukan SPJ baru.</p>
    </div>
    <a href="{{ route('teknis.spj.index') }}" class="btn-primary" style="background-color: #6b7280;">
        Kembali
    </a>
</div>

<div class="panel">
    <div class="panel-header">
        <div>
            <h2>Detail Pengajuan</h2>
        </div>
    </div>
    <div style="padding: 1.5rem;">
        <form action="{{ route('teknis.spj.store') }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Nomor SPJ</label>
                <input type="text" name="nomor_spj" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; outline: none; transition: border-color 0.2s;" placeholder="Cth: SPJ-2026-XXX" value="{{ old('nomor_spj') }}">
                @error('nomor_spj') <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Kegiatan</label>
                <input type="text" name="kegiatan" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; outline: none;" placeholder="Nama / Uraian Kegiatan" value="{{ old('kegiatan') }}">
                @error('kegiatan') <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span> @enderror
            </div>

            <div style="display: flex; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div style="flex: 1;">
                    <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Tanggal Kegiatan</label>
                    <input type="date" name="tanggal" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; outline: none;" value="{{ old('tanggal') }}">
                    @error('tanggal') <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span> @enderror
                </div>
                <div style="flex: 1;">
                    <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Nilai (Rp)</label>
                    <input type="number" name="nilai" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; outline: none;" placeholder="1500000" min="0" value="{{ old('nilai') }}">
                    @error('nilai') <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Keterangan Tambahan</label>
                <textarea name="keterangan" rows="4" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; outline: none;" placeholder="Catatan... (Opsional)">{{ old('keterangan') }}</textarea>
                @error('keterangan') <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Aksi Lanjutan</label>
                <div style="display: flex; gap: 1.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="radio" name="status" value="draft" {{ old('status', 'draft') === 'draft' ? 'checked' : '' }}>
                        Simpan sebagai Draft
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="radio" name="status" value="diajukan" {{ old('status') === 'diajukan' ? 'checked' : '' }}>
                        Langsung Ajukan ke Umum/PPSPM
                    </label>
                </div>
                @error('status') <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span> @enderror
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                <a href="{{ route('teknis.spj.index') }}" style="padding: 0.75rem 1.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; text-decoration: none; color: #374151; font-weight: 500;">Batal</a>
                <button type="submit" class="btn-primary" style="border: none; cursor: pointer; font-family: inherit; font-size: 1rem;">Simpan SPJ</button>
            </div>
        </form>
    </div>
</div>

@endsection
