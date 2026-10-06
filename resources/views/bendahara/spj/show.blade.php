@extends('layouts.bendahara')

@php
$title = 'Detail Pencairan SPJ - Bendahara';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/bendahara/spj.css'])
@endpush

<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 1.5rem; color: #111827; margin: 0 0 0.5rem 0;">Pencairan SPJ: {{ $spj->nomor_spj }}</h1>
        <p>Diajukan pada {{ $spj->created_at->format('d M Y, H:i') }} oleh <strong>{{ $spj->user->name ?? 'Teknis' }}</strong>.</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('bendahara.spj.index') }}" class="btn-primary" style="background-color: #6b7280; text-decoration: none;">
            Kembali
        </a>
    </div>
</div>

@if($spj->status == 'disetujui_ppspm')
<div class="panel" style="margin-bottom: 2rem; background: #f0fdfa; border: 1px solid #5eead4;">
    <div style="padding: 1rem 1.5rem; border-bottom: 1px solid #5eead4;">
        <strong style="color: #0f766e; display: flex; align-items: center; gap: 5px; font-size: 1.1rem;">
            <ion-icon name="document-text-outline"></ion-icon> Surat Perintah Membayar (SPM)
        </strong>
    </div>
    <div style="padding: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <div>
            <span style="display: block; color: #134e4a; font-size: 0.875rem; margin-bottom: 0.25rem;">Nomor SPM</span>
            <strong style="font-size: 1.5rem; color: #0d9488;">{{ $spj->nomor_spm ?? 'Belum ada nomor SPM' }}</strong>
        </div>
        <div>
            <span style="display: block; color: #134e4a; font-size: 0.875rem; margin-bottom: 0.25rem;">Waktu Penerbitan SPM</span>
            <strong style="font-size: 1.125rem; color: #0d9488;">{{ $spj->disetujui_ppspm_at ? \Carbon\Carbon::parse($spj->disetujui_ppspm_at)->format('d M Y, H:i') : '-' }}</strong>
        </div>
        @if($spj->catatan_ppspm)
        <div style="grid-column: span 2;">
            <span style="display: block; color: #134e4a; font-size: 0.875rem; margin-bottom: 0.25rem;">Catatan PPSPM</span>
            <p style="color: #134e4a; margin: 0; white-space: pre-line; padding: 1rem; background: #ccfbf1; border-radius: 0.5rem;">{{ $spj->catatan_ppspm }}</p>
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
            <span style="display: block; color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">Nilai Pencairan (Rupiah)</span>
            <strong style="font-size: 1.75rem; color: #059669;">Rp {{ number_format($spj->nilai, 0, ',', '.') }}</strong>
        </div>
        <div>
            <span style="display: block; color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">Keterangan / Catatan Teknis</span>
            <p style="color: #374151; margin: 0; white-space: pre-line;">{{ $spj->keterangan ?: '-' }}</p>
        </div>
    </div>
</div>

<div class="panel" style="margin-bottom: 2rem;">
    <div class="panel-header">
        <h2>Berkas Dokumen</h2>
    </div>
    <div style="padding: 1.5rem;">
        @if($spj->dokumen_file)
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: #f9fafb;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="font-size: 2rem; color: #2563eb; display: flex; align-items: center;">
                        <ion-icon name="document-text-outline"></ion-icon>
                    </div>
                    <div>
                        <strong>Dokumen SPJ Lengkap</strong>
                        <div style="color: #6b7280; font-size: 0.875rem;">Lampiran dari pihak pengaju</div>
                    </div>
                </div>
                <a href="{{ asset('storage/' . $spj->dokumen_file) }}" target="_blank" class="btn-primary" style="text-decoration: none;">Lihat Dokumen Asli</a>
            </div>
        @else
            <div style="background-color: #fef2f2; color: #991b1b; padding: 1rem; border-radius: 0.375rem; border: 1px solid #f87171; text-align: center;">
                <strong style="display: flex; align-items: center; justify-content: center; gap: 5px;">
                    <ion-icon name="warning-outline" style="font-size: 1.25rem;"></ion-icon> Tidak Ada Dokumen
                </strong>
            </div>
        @endif
    </div>
</div>

@if($spj->status == 'disetujui_ppspm')
<div class="panel">
    <div class="panel-header" style="background-color: #f3f4f6;">
        <h2>Eksekusi Pencairan Dana</h2>
    </div>
    <div style="padding: 1.5rem;">
        <form action="{{ route('bendahara.spj.verify', $spj->id) }}" method="POST">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                
                <!-- OPSI SETUJUI & CAIRKAN -->
                <div style="border: 2px solid #0d9488; border-radius: 0.5rem; padding: 1.5rem; display: flex; flex-direction: column;">
                    <h3 style="color: #0f766e; margin-top: 0; margin-bottom: 1rem;">Opsi 1: Cairkan Dana & Selesaikan</h3>
                    <p style="color: #134e4a; margin-bottom: 1rem; font-size: 0.875rem;">Dana telah siap ditransfer atau diserahkan sesuai dengan SPM. Proses SPJ ini akan dianggap <strong>Selesai Sepenuhnya</strong>.</p>
                    
                    <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #0f766e;">Bukti Transfer / No. Referensi (Wajib)</label>
                    <input type="text" name="bukti_transfer" style="width: 100%; padding: 0.75rem; border: 1px solid #5eead4; border-radius: 0.375rem; outline: none; margin-bottom: 1rem; background-color: #f0fdfa;" placeholder="Contoh: REF-BCA-92837492 atau Kas Tunai #12" required>
                    
                    <button type="submit" name="action" value="setujui" class="btn-primary" style="background-color: #0d9488; width: 100%; font-size: 1rem; padding: 1rem; cursor: pointer; margin-top: auto; display: flex; align-items: center; justify-content: center; gap: 8px; border: none; color: white;">
                        <ion-icon name="wallet-outline" style="font-size: 1.25rem;"></ion-icon> Cairkan & Tutup SPJ
                    </button>
                </div>

                <!-- OPSI TOLAK -->
                <div style="border: 2px solid #ef4444; border-radius: 0.5rem; padding: 1.5rem;">
                    <h3 style="color: #991b1b; margin-top: 0; margin-bottom: 0.5rem;">Opsi 2: Kembalikan (Revisi)</h3>
                    <p style="color: #7f1d1d; margin-bottom: 1rem; font-size: 0.875rem;">Ada ketidaksesuaian SPM atau masalah teknis bank. Kembalikan ke PPSPM / Teknis.</p>
                    
                    <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #991b1b;">Alasan Penolakan (Wajib jika dikembalikan)</label>
                    <textarea name="catatan_revisi" rows="3" style="width: 100%; padding: 0.75rem; border: 1px solid #fca5a5; border-radius: 0.375rem; outline: none; margin-bottom: 1rem; background-color: #fef2f2;" placeholder="Tuliskan kendala pencairan dana..."></textarea>
                    
                    <button type="submit" name="action" value="tolak" class="btn-primary" style="background-color: #ef4444; width: 100%; font-size: 1rem; padding: 1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; border: none; color: white;">
                        <ion-icon name="close-circle-outline" style="font-size: 1.25rem;"></ion-icon> Kembalikan SPJ
                    </button>
                </div>
                
            </div>
        </form>
    </div>
</div>
@endif

@endsection
