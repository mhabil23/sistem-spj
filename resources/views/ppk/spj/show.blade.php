@extends('layouts.ppk')

@php
$title = 'Detail Verifikasi SPJ - PPK';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/ppk/spj.css'])
@endpush


<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 1.5rem; color: #111827; margin: 0 0 0.5rem 0;">Verifikasi SPJ: {{ $spj->nomor_spj }}</h1>
        <p>Diajukan pada {{ $spj->created_at->format('d M Y, H:i') }} oleh <strong>{{ $spj->user->name ?? 'Teknis' }}</strong>.</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('ppk.spj.index') }}" class="btn-primary" style="background-color: #6b7280; text-decoration: none;">
            Kembali
        </a>
    </div>
</div>

@if($spj->status == 'disetujui_umum')
    @php
        $waktuMulai = $spj->disetujui_umum_at ?: $spj->updated_at;
        $diff = \Carbon\Carbon::parse($waktuMulai)->diff(\Carbon\Carbon::now());
        $timeStr = '';
        if($diff->d > 0) $timeStr .= $diff->d . ' hari ';
        if($diff->h > 0) $timeStr .= $diff->h . ' jam ';
        $timeStr .= $diff->i . ' menit';
    @endphp
<div class="panel" style="margin-bottom: 2rem; background: #f5f3ff; border: 1px solid #c4b5fd;">
    <div style="padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <strong style="color: #4c1d95; display: flex; align-items: center; gap: 5px; margin-bottom: 0.25rem;">
                <ion-icon name="hourglass-outline"></ion-icon> Waktu SLA (Proses Verifikasi PPK)
            </strong>
            <span style="color: #5b21b6; font-size: 0.875rem;">
                SPJ diteruskan ke PPK pada: {{ \Carbon\Carbon::parse($waktuMulai)->format('d M Y, H:i') }}
            </span>
        </div>
        <div style="text-align: right;">
            <strong style="font-size: 1.25rem; color: #4c1d95; display: block;">{{ $timeStr }}</strong>
            <span style="color: #5b21b6; font-size: 0.75rem;">Waktu berjalan sejak diteruskan</span>
        </div>
    </div>
    
    @if($spj->catatan_internal)
    <div style="padding: 1rem 1.5rem; border-top: 1px solid #c4b5fd;">
        <strong style="color: #4c1d95; display: block; font-size: 0.875rem; margin-bottom: 0.25rem;">Catatan Internal dari Umum:</strong>
        <p style="margin: 0; color: #5b21b6; font-size: 0.875rem; white-space: pre-line;">{{ $spj->catatan_internal }}</p>
    </div>
    @endif
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

@if($spj->status == 'disetujui_umum')
<div class="panel">
    <div class="panel-header" style="background-color: #f3f4f6;">
        <h2>Aksi Verifikasi</h2>
    </div>
    <div style="padding: 1.5rem;">
        <form action="{{ route('ppk.spj.verify', $spj->id) }}" method="POST">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                
                <!-- OPSI SETUJUI -->
                <div style="border: 2px solid #8b5cf6; border-radius: 0.5rem; padding: 1.5rem; display: flex; flex-direction: column;">
                    <h3 style="color: #5b21b6; margin-top: 0; margin-bottom: 1rem;">Opsi 1: Setujui SPJ</h3>
                    <p style="color: #4c1d95; margin-bottom: 1rem; font-size: 0.875rem;">Dokumen lengkap dan benar. Teruskan pengajuan ini ke tahap persetujuan PPSPM.</p>
                    
                    <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #5b21b6;">Disposisi (Pilih Instruksi)</label>
                    <select name="disposisi" style="width: 100%; padding: 0.75rem; border: 1px solid #c4b5fd; border-radius: 0.375rem; outline: none; margin-bottom: 1rem; background-color: #f5f3ff;">
                        <option value="Telah Disetujui PPK, Segera Cairkan">Telah Disetujui PPK, Segera Cairkan</option>
                        <option value="Proses Sesuai Anggaran">Proses Sesuai Anggaran</option>
                        <option value="Tunda Sampai Bulan Depan">Tunda Sampai Bulan Depan</option>
                        <option value="Periksa Ulang Kelengkapan Fisik">Periksa Ulang Kelengkapan Fisik</option>
                        <option value="Lainnya">Lainnya (Tulis di Catatan Internal)</option>
                    </select>

                    <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #5b21b6;">Catatan Internal untuk PPSPM (Opsional)</label>
                    <textarea name="catatan_ppk" rows="2" style="width: 100%; padding: 0.75rem; border: 1px solid #c4b5fd; border-radius: 0.375rem; outline: none; margin-bottom: 1rem; background-color: #f5f3ff;" placeholder="Cth: Dokumen fisik sudah saya tandatangani..."></textarea>
                    
                    <button type="submit" name="action" value="setujui" class="btn-primary" style="background-color: #8b5cf6; width: 100%; font-size: 1rem; padding: 1rem; cursor: pointer; margin-top: auto; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <ion-icon name="checkmark-circle-outline" style="font-size: 1.25rem;"></ion-icon> Setujui & Teruskan ke PPSPM
                    </button>
                </div>

                <!-- OPSI TOLAK -->
                <div style="border: 2px solid #ef4444; border-radius: 0.5rem; padding: 1.5rem;">
                    <h3 style="color: #991b1b; margin-top: 0; margin-bottom: 0.5rem;">Opsi 2: Kembalikan (Revisi)</h3>
                    <p style="color: #7f1d1d; margin-bottom: 1rem; font-size: 0.875rem;">Ada kesalahan atau dokumen kurang. SPJ akan dikembalikan ke staf Teknis untuk diperbaiki.</p>
                    
                    <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #991b1b;">Catatan Kesalahan (Wajib jika menolak)</label>
                    <textarea name="catatan_revisi" rows="3" style="width: 100%; padding: 0.75rem; border: 1px solid #fca5a5; border-radius: 0.375rem; outline: none; margin-bottom: 1rem; background-color: #fef2f2;" placeholder="Cth: Dokumen kuitansi belum ditandatangani..."></textarea>
                    
                    <button type="submit" name="action" value="tolak" class="btn-primary" style="background-color: #ef4444; width: 100%; font-size: 1rem; padding: 1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <ion-icon name="close-circle-outline" style="font-size: 1.25rem;"></ion-icon> Kembalikan ke Teknis
                    </button>
                </div>
                
            </div>
        </form>
    </div>
</div>
@endif

@endsection

