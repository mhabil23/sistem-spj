@extends('layouts.teknis')

@php
$title = 'Edit SPJ';
$subtitle = 'Perbaiki atau perbarui pengajuan SPJ Anda.';
@endphp

@section('content')

<div class="page-heading">
    <div>
        <h2>Edit Pengajuan SPJ</h2>
        <p>Anda dapat mengedit SPJ ini karena statusnya <strong>{{ str_replace('_', ' ', strtoupper($spj->status)) }}</strong>.</p>
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
        
        @if(in_array($spj->status, ['revisi_umum', 'revisi_ppk', 'revisi_ppspm', 'revisi_bendahara']) && $spj->catatan_revisi)
            <div style="background-color: #fef2f2; border: 1px solid #f87171; border-left: 4px solid #dc2626; padding: 1rem; border-radius: 0.25rem; margin-bottom: 1.5rem;">
                <h3 style="color: #991b1b; font-size: 1rem; margin-top: 0; margin-bottom: 0.5rem;">Catatan Revisi dari {{ strtoupper(explode('_', $spj->status)[1] ?? 'Pemeriksa') }}</h3>
                <p style="color: #7f1d1d; margin: 0; white-space: pre-line;">{{ $spj->catatan_revisi }}</p>
            </div>
        @endif

        <form action="{{ route('teknis.spj.update', $spj->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Nomor SPJ</label>
                <input type="text" name="nomor_spj" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; outline: none; transition: border-color 0.2s;" value="{{ old('nomor_spj', $spj->nomor_spj) }}">
                @error('nomor_spj') <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Kegiatan</label>
                <input type="text" name="kegiatan" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; outline: none;" value="{{ old('kegiatan', $spj->kegiatan) }}">
                @error('kegiatan') <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span> @enderror
            </div>

            <div style="display: flex; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div style="flex: 1;">
                    <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Tanggal Kegiatan</label>
                    <input type="date" name="tanggal" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; outline: none;" value="{{ old('tanggal', $spj->tanggal->format('Y-m-d')) }}">
                    @error('tanggal') <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span> @enderror
                </div>
                <div style="flex: 1;">
                    <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Nilai (Rp)</label>
                    <input type="number" name="nilai" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; outline: none;" min="0" value="{{ old('nilai', intval($spj->nilai)) }}">
                    @error('nilai') <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Keterangan Tambahan</label>
                <textarea name="keterangan" rows="4" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; outline: none;">{{ old('keterangan', $spj->keterangan) }}</textarea>
                @error('keterangan') <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Aksi Lanjutan</label>
                <div style="display: flex; gap: 1.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="radio" name="status" value="draft" {{ old('status', $spj->status) === 'draft' ? 'checked' : '' }}>
                        Simpan sebagai Draft
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="radio" name="status" value="diajukan" {{ old('status', $spj->status) !== 'draft' ? 'checked' : '' }}>
                        Ajukan ke Umum/PPSPM
                    </label>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                <a href="{{ route('teknis.spj.index') }}" style="padding: 0.75rem 1.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; text-decoration: none; color: #374151; font-weight: 500;">Batal</a>
                <button type="submit" class="btn-primary" style="border: none; cursor: pointer; font-family: inherit; font-size: 1rem;">Perbarui SPJ</button>
            </div>
        </form>
    </div>
</div>

@endsection
