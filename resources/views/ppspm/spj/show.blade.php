@extends('layouts.ppspm')

@php
$title = 'Detail Verifikasi SPJ - PPSPM';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/ppspm/spj.css'])
@endpush

<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 1.5rem; color: #111827; margin: 0 0 0.5rem 0;">Verifikasi Akhir SPJ: {{ $spj->nomor_spj }}</h1>
        <p>Diajukan pada {{ $spj->created_at->format('d M Y, H:i') }} oleh <strong>{{ $spj->user->name ?? 'Teknis' }}</strong>.</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('ppspm.spj.index') }}" class="btn-primary" style="background-color: #6b7280; text-decoration: none;">
            Kembali
        </a>
    </div>
</div>

@if($spj->status == 'disetujui_ppk')
<div class="panel" style="margin-bottom: 2rem; background: #fffbeb; border: 1px solid #fde68a;">
    <div style="padding: 1rem 1.5rem; border-bottom: 1px solid #fde68a;">
        <strong style="color: #b45309; display: flex; align-items: center; gap: 5px; font-size: 1.1rem;">
            <ion-icon name="document-text-outline"></ion-icon> Instruksi / Disposisi dari PPK
        </strong>
    </div>
    <div style="padding: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <div>
            <span style="display: block; color: #92400e; font-size: 0.875rem; margin-bottom: 0.25rem;">Keputusan PPK</span>
            <strong style="font-size: 1.125rem; color: #b45309;">{{ $spj->disposisi ?? 'Disetujui' }}</strong>
        </div>
        <div>
            <span style="display: block; color: #92400e; font-size: 0.875rem; margin-bottom: 0.25rem;">Waktu Persetujuan PPK</span>
            <strong style="font-size: 1.125rem; color: #b45309;">{{ $spj->disetujui_ppk_at ? \Carbon\Carbon::parse($spj->disetujui_ppk_at)->format('d M Y, H:i') : '-' }}</strong>
        </div>
        @if($spj->catatan_ppk)
        <div style="grid-column: span 2;">
            <span style="display: block; color: #92400e; font-size: 0.875rem; margin-bottom: 0.25rem;">Catatan Khusus PPK</span>
            <p style="color: #78350f; margin: 0; white-space: pre-line; padding: 1rem; background: #fef3c7; border-radius: 0.5rem;">{{ $spj->catatan_ppk }}</p>
        </div>
        @endif
    </div>
</div>
@endif

<div class="panel" style="margin-bottom: 2rem;">
    <div class="panel-header">
        <h2>Detail Kegiatan & Biaya</h2>
    </div>
    <div style="padding: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <div>
            <span style="display: block; color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">Nomor SPJ</span>
            <strong style="font-size: 1.125rem; color: #111827;">{{ $spj->nomor_spj }}</strong>
        </div>
        <div>
            <span style="display: block; color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">Tanggal Kegiatan</span>
            <strong style="font-size: 1.125rem; color: #111827;">{{ $spj->tanggal->format('d F Y') }}</strong>
        </div>
        <div style="grid-column: span 2;">
            <span style="display: block; color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">Nama Kegiatan</span>
            <strong style="font-size: 1.125rem; color: #111827;">{{ $spj->kegiatan }}</strong>
        </div>
        <div>
            <span style="display: block; color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">Nilai Pengajuan</span>
            <strong style="font-size: 1.5rem; color: #059669;">Rp {{ number_format($spj->nilai, 0, ',', '.') }}</strong>
        </div>
        <div>
            <span style="display: block; color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">Keterangan / Catatan Teknis</span>
            <p style="color: #374151; margin: 0; white-space: pre-line;">{{ $spj->keterangan ?: '-' }}</p>
        </div>
    </div>
</div>

<div class="panel" style="margin-bottom: 2rem;">
    <div class="panel-header">
        <h2>Berkas Lampiran</h2>
    </div>
    <div style="padding: 1.5rem;">
        @if($spj->dokumen_file)
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: #f9fafb;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="font-size: 2rem; color: #2563eb; display: flex; align-items: center;">
                        <ion-icon name="document-text-outline"></ion-icon>
                    </div>
                    <div>
                        <strong>Dokumen SPJ</strong>
                        <div style="color: #6b7280; font-size: 0.875rem;">Disertakan saat pengajuan</div>
                    </div>
                </div>
                <a href="{{ asset('storage/' . $spj->dokumen_file) }}" target="_blank" class="btn-primary" style="text-decoration: none;">Lihat / Unduh Dokumen</a>
            </div>
        @else
            <div style="background-color: #fef2f2; color: #991b1b; padding: 1rem; border-radius: 0.375rem; border: 1px solid #f87171; text-align: center;">
                <strong style="display: flex; align-items: center; justify-content: center; gap: 5px;">
                    <ion-icon name="warning-outline" style="font-size: 1.25rem;"></ion-icon> Tidak Ada Dokumen
                </strong>
                <p style="margin: 0.5rem 0 0 0; color: #7f1d1d;">Staf teknis belum melampirkan berkas dokumen digital untuk SPJ ini.</p>
            </div>
        @endif
    </div>
</div>

@if($spj->status == 'disetujui_ppk')
<div class="panel">
    <div class="panel-header" style="background-color: #f3f4f6;">
        <h2>Aksi Verifikasi Akhir & Penerbitan SPM</h2>
    </div>
    <div style="padding: 1.5rem;">
        <form action="{{ route('ppspm.spj.verify', $spj->id) }}" method="POST">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                
                <!-- OPSI SETUJUI & TERBITKAN SPM -->
                <div style="border: 2px solid #d97706; border-radius: 0.5rem; padding: 1.5rem; display: flex; flex-direction: column;">
                    <h3 style="color: #b45309; margin-top: 0; margin-bottom: 1rem;">Opsi 1: Terbitkan SPM</h3>
                    <p style="color: #92400e; margin-bottom: 1rem; font-size: 0.875rem;">Setujui pengajuan ini dan terbitkan Nomor SPM agar bisa dicairkan oleh Bendahara.</p>
                    
                    <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #b45309;">Nomor SPM (Wajib)</label>
                    <input type="text" name="nomor_spm" style="width: 100%; padding: 0.75rem; border: 1px solid #fcd34d; border-radius: 0.375rem; outline: none; margin-bottom: 1rem; background-color: #fffbeb;" placeholder="Contoh: SPM/2026/001" required>

                    <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #b45309;">Catatan PPSPM (Opsional)</label>
                    <textarea name="catatan_ppspm" rows="2" style="width: 100%; padding: 0.75rem; border: 1px solid #fcd34d; border-radius: 0.375rem; outline: none; margin-bottom: 1rem; background-color: #fffbeb;" placeholder="Catatan tambahan..."></textarea>
                    
                    <button type="submit" name="action" value="setujui" class="btn-primary" style="background-color: #d97706; width: 100%; font-size: 1rem; padding: 1rem; cursor: pointer; margin-top: auto; display: flex; align-items: center; justify-content: center; gap: 8px; border: none; color: white;">
                        <ion-icon name="checkmark-done-circle-outline" style="font-size: 1.25rem;"></ion-icon> Terbitkan SPM
                    </button>
                </div>

                <!-- OPSI TOLAK -->
                <div style="border: 2px solid #ef4444; border-radius: 0.5rem; padding: 1.5rem;">
                    <h3 style="color: #991b1b; margin-top: 0; margin-bottom: 0.5rem;">Opsi 2: Kembalikan (Revisi)</h3>
                    <p style="color: #7f1d1d; margin-bottom: 1rem; font-size: 0.875rem;">Terdapat masalah, kembalikan dokumen ini ke pembuat SPJ (Teknis).</p>
                    
                    <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #991b1b;">Alasan Penolakan (Wajib jika menolak)</label>
                    <textarea name="catatan_revisi" rows="3" style="width: 100%; padding: 0.75rem; border: 1px solid #fca5a5; border-radius: 0.375rem; outline: none; margin-bottom: 1rem; background-color: #fef2f2;" placeholder="Alasan mengapa SPM tidak bisa diterbitkan..."></textarea>
                    
                    <button type="submit" name="action" value="tolak" class="btn-primary" style="background-color: #ef4444; width: 100%; font-size: 1rem; padding: 1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; border: none; color: white;">
                        <ion-icon name="close-circle-outline" style="font-size: 1.25rem;"></ion-icon> Tolak / Revisi
                    </button>
                </div>
                
            </div>
        </form>
    </div>
</div>
@endif

@endsection
