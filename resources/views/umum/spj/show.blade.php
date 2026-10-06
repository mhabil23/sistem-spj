@extends('layouts.umum')

@php
$title = 'Pemeriksaan SPJ';
$subtitle = 'Review dokumen pengajuan SPJ.';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/umum/spj.css'])
    <style>
        .show-panel {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #e2e8f0;
            margin-bottom: 24px;
            overflow: hidden;
        }

        .show-panel-header {
            padding: 20px 24px;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
        }
        
        .show-panel-header h2 {
            font-size: 16px;
            color: #0f172a;
            margin: 0;
            font-weight: 700;
        }

        .detail-grid {
            padding: 24px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }
        
        .detail-item span {
            display: block;
            color: #64748b;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 6px;
        }
        
        .detail-item strong {
            font-size: 16px;
            color: #0f172a;
            display: block;
        }
    </style>
@endpush

<div style="padding: 32px 40px; box-sizing: border-box; max-width: 100%;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px;">
        <div>
            <h2 style="font-size: 24px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">Verifikasi SPJ: {{ $spj->nomor_spj }}</h2>
            <p style="color: #64748b; font-size: 14px; margin: 0;">Diajukan pada {{ $spj->created_at->format('d M Y, H:i') }} oleh <strong>{{ $spj->user->name ?? 'Teknis' }}</strong>.</p>
        </div>
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('umum.spj.print', $spj->id) }}" target="_blank" style="background-color: #10b981; color: white; padding: 10px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 13px; display: flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2); transition: 0.2s;" onmouseover="this.style.backgroundColor='#059669'; this.style.transform='translateY(-1px)';" onmouseout="this.style.backgroundColor='#10b981'; this.style.transform='translateY(0)';">
                <ion-icon name="print-outline" style="font-size: 16px;"></ion-icon> Cetak Lembar Checklist
            </a>
            <a href="{{ route('umum.spj.index') }}" style="background-color: #f1f5f9; color: #475569; padding: 10px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 13px; border: 1px solid #cbd5e1; transition: 0.2s;" onmouseover="this.style.backgroundColor='#e2e8f0';" onmouseout="this.style.backgroundColor='#f1f5f9';">
                Kembali
            </a>
        </div>
    </div>

    @php
    $waktuMulai = $spj->diajukan_at ?? $spj->created_at;
    @endphp
    @if($waktuMulai)
    <div class="show-panel" style="background: #f0fdf4; border: 1px solid #bbf7d0;">
        <div style="padding: 20px 24px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <strong style="color: #166534; display: flex; align-items: center; gap: 6px; margin-bottom: 4px; font-size: 15px;">
                    <ion-icon name="hourglass-outline"></ion-icon> Waktu SLA (Proses Verifikasi)
                </strong>
                <span style="color: #15803d; font-size: 13px;">
                    SPJ diajukan pada: {{ \Carbon\Carbon::parse($waktuMulai)->format('d M Y, H:i') }}
                </span>
            </div>
            <div>
                <strong style="color: #166534; font-size: 18px;">
                    @php
                        $endTime = $spj->status == 'diajukan' ? now() : $spj->updated_at;
                        $diff = \Carbon\Carbon::parse($waktuMulai)->diff($endTime);
                        $timeString = '';
                        if($diff->d > 0) $timeString .= $diff->d . ' Hari ';
                        if($diff->h > 0) $timeString .= $diff->h . ' Jam ';
                        $timeString .= $diff->i . ' Menit';
                    @endphp
                    {{ $spj->status == 'diajukan' ? 'Berjalan: ' : 'Selesai dalam: ' }} {{ $timeString }}
                </strong>
            </div>
        </div>
        
        @if($spj->status !== 'diajukan' && $spj->catatan_internal)
        <div style="padding: 16px 24px; border-top: 1px solid #bbf7d0; background: #dcfce7;">
            <strong style="color: #166534; display: block; font-size: 13px; margin-bottom: 4px;">Catatan Internal untuk PPK:</strong>
            <p style="margin: 0; color: #15803d; font-size: 13px; white-space: pre-line;">{{ $spj->catatan_internal }}</p>
        </div>
        @endif
    </div>
    @endif

    <div class="show-panel">
        <div class="show-panel-header">
            <h2>Detail Kegiatan & Biaya</h2>
        </div>
        <div class="detail-grid">
            <div class="detail-item">
                <span>Nomor SPJ</span>
                <strong>{{ $spj->nomor_spj }}</strong>
            </div>
            <div class="detail-item">
                <span>Tanggal Kegiatan</span>
                <strong>{{ $spj->tanggal->format('d F Y') }}</strong>
            </div>
            <div class="detail-item" style="grid-column: span 2;">
                <span>Nama Kegiatan</span>
                <strong style="line-height: 1.5;">{{ $spj->kegiatan }}</strong>
            </div>
            <div class="detail-item">
                <span>Nilai Pengajuan</span>
                <strong style="font-size: 24px; color: #10b981;">Rp {{ number_format($spj->nilai, 0, ',', '.') }}</strong>
            </div>
            <div class="detail-item">
                <span>Keterangan / Catatan Teknis</span>
                <p style="color: #475569; margin: 0; font-size: 14px; line-height: 1.5; white-space: pre-line;">{{ $spj->keterangan ?: '-' }}</p>
            </div>
        </div>
    </div>

    <div class="show-panel">
        <div class="show-panel-header">
            <h2>Dokumen Pendukung</h2>
        </div>
        <div style="padding: 24px;">
            @if($spj->dokumen_file)
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px; background-color: #f8fafc;">
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div style="font-size: 36px; color: #3b82f6; display: flex; align-items: center;">
                            <ion-icon name="document-text-outline"></ion-icon>
                        </div>
                        <div>
                            <strong style="font-size: 15px; color: #0f172a; display: block; margin-bottom: 2px;">Dokumen SPJ</strong>
                            <span style="color: #64748b; font-size: 13px;">Disertakan saat pengajuan oleh Staf Teknis</span>
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . $spj->dokumen_file) }}" target="_blank" style="background-color: #3b82f6; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 13px; display: flex; align-items: center; gap: 8px; box-shadow: 0 2px 4px rgba(59,130,246,0.25); transition: 0.2s;" onmouseover="this.style.backgroundColor='#2563eb';" onmouseout="this.style.backgroundColor='#3b82f6';">
                        <ion-icon name="download-outline" style="font-size: 16px;"></ion-icon> Lihat / Unduh
                    </a>
                </div>
            @else
                <div style="background-color: #fef2f2; color: #991b1b; padding: 24px; border-radius: 8px; border: 1px dashed #f87171; text-align: center;">
                    <div style="display: flex; align-items: center; justify-content: center; gap: 8px; font-weight: 700; font-size: 16px; margin-bottom: 8px;">
                        <ion-icon name="warning-outline" style="font-size: 24px; color: #ef4444;"></ion-icon> Tidak Ada Lampiran Dokumen
                    </div>
                    <p style="margin: 0; color: #7f1d1d; font-size: 14px;">Staf teknis belum melampirkan berkas dokumen digital untuk pengajuan SPJ ini.</p>
                </div>
            @endif
        </div>
    </div>

    @if($spj->status == 'diajukan')
    <div class="show-panel">
        <div class="show-panel-header">
            <h2>Aksi Verifikasi (Keputusan)</h2>
        </div>
        <div style="padding: 24px;">
            <form action="{{ route('umum.spj.verify', $spj->id) }}" method="POST">
                @csrf
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                    
                    <!-- OPSI SETUJUI -->
                    <div style="border: 1px solid #10b981; border-radius: 12px; padding: 24px; display: flex; flex-direction: column; background: #f0fdf4; box-shadow: 0 4px 10px rgba(16,185,129,0.05);">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                            <ion-icon name="checkmark-circle" style="color: #10b981; font-size: 24px;"></ion-icon>
                            <h3 style="color: #065f46; margin: 0; font-size: 18px;">Setujui SPJ</h3>
                        </div>
                        <p style="color: #047857; margin-bottom: 20px; font-size: 13px; line-height: 1.5;">Pilih opsi ini jika dokumen telah lengkap dan benar. Pengajuan akan diteruskan ke PPK.</p>
                        
                        <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 13px; color: #065f46;">Catatan Internal (Dilihat oleh PPK - Opsional)</label>
                        <textarea name="catatan_internal" rows="2" style="width: 100%; padding: 12px; border: 1px solid #6ee7b7; border-radius: 8px; outline: none; margin-bottom: 20px; background-color: #ffffff; font-family: inherit; font-size: 13px;" placeholder="Cth: Kuitansi asli sudah dicek dan sesuai..."></textarea>
                        
                        <button type="submit" name="action" value="setujui" style="background-color: #10b981; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; padding: 14px; cursor: pointer; margin-top: auto; display: flex; align-items: center; justify-content: center; gap: 8px; transition: 0.2s; box-shadow: 0 4px 6px rgba(16,185,129,0.25);" onmouseover="this.style.backgroundColor='#059669'; this.style.transform='translateY(-2px)';" onmouseout="this.style.backgroundColor='#10b981'; this.style.transform='translateY(0)';">
                            <ion-icon name="send-outline" style="font-size: 18px;"></ion-icon> Teruskan ke PPK
                        </button>
                    </div>

                    <!-- OPSI TOLAK -->
                    <div style="border: 1px solid #f87171; border-radius: 12px; padding: 24px; display: flex; flex-direction: column; background: #fef2f2; box-shadow: 0 4px 10px rgba(239,68,68,0.05);">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                            <ion-icon name="close-circle" style="color: #ef4444; font-size: 24px;"></ion-icon>
                            <h3 style="color: #991b1b; margin: 0; font-size: 18px;">Kembalikan (Revisi)</h3>
                        </div>
                        <p style="color: #7f1d1d; margin-bottom: 20px; font-size: 13px; line-height: 1.5;">Pilih opsi ini jika ada kesalahan atau dokumen kurang. SPJ akan dikembalikan ke Teknis.</p>
                        
                        <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 13px; color: #991b1b;">Catatan Kesalahan (Wajib diisi jika dikembalikan)</label>
                        <textarea name="catatan_revisi" rows="3" style="width: 100%; padding: 12px; border: 1px solid #fca5a5; border-radius: 8px; outline: none; margin-bottom: 20px; background-color: #ffffff; font-family: inherit; font-size: 13px;" placeholder="Cth: Dokumen invoice bulan lalu belum dilampirkan..."></textarea>
                        
                        <button type="submit" name="action" value="tolak" style="background-color: #ef4444; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; padding: 14px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: 0.2s; box-shadow: 0 4px 6px rgba(239,68,68,0.25);" onmouseover="this.style.backgroundColor='#dc2626'; this.style.transform='translateY(-2px)';" onmouseout="this.style.backgroundColor='#ef4444'; this.style.transform='translateY(0)';">
                            <ion-icon name="arrow-undo-outline" style="font-size: 18px;"></ion-icon> Kembalikan ke Teknis
                        </button>
                    </div>
                    
                </div>
                
                @error('catatan_revisi')
                <div style="color: #dc2626; font-size: 13px; margin-top: 16px; text-align: center; font-weight: 500; background: #fee2e2; padding: 8px; border-radius: 6px;">
                    <ion-icon name="warning" style="vertical-align: middle;"></ion-icon> {{ $message }}
                </div>
                @enderror

            </form>
        </div>
    </div>
</div>
@elseif($spj->status == 'disetujui_ppk')
<div class="panel" style="border: 2px solid #8b5cf6;">
    <div class="panel-header" style="background-color: #f5f3ff; border-bottom: 1px solid #ddd6fe;">
        <h2 style="color: #4c1d95; display: flex; align-items: center; gap: 8px;">
            <ion-icon name="walk-outline"></ion-icon> Konfirmasi dari PPSPM
        </h2>
    </div>
    <div style="padding: 1.5rem;">
        <p style="color: #5b21b6; margin-top: 0; margin-bottom: 1.5rem;">
            Berkas ini seharusnya sedang berada di meja PPSPM. Silakan perbarui status di bawah ini berdasarkan hasil dari PPSPM.
        </p>
        
        <form action="{{ route('umum.spj.verify', $spj->id) }}" method="POST">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                
                <!-- OPSI SETUJUI PPSPM -->
                <div style="border: 1px solid #c4b5fd; border-radius: 0.5rem; padding: 1.5rem; display: flex; flex-direction: column; background: #fff;">
                    <h3 style="color: #6d28d9; margin-top: 0; margin-bottom: 1rem;">PPSPM Menyetujui</h3>
                    <p style="color: #7c3aed; margin-bottom: 1rem; font-size: 0.875rem;">PPSPM telah menandatangani dokumen. SPJ akan diteruskan ke Bendahara untuk pencairan.</p>
                    
                    <button type="submit" name="action" value="setujui_ppspm" class="btn-primary" style="background-color: #7c3aed; width: 100%; font-size: 1rem; padding: 1rem; cursor: pointer; margin-top: auto; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <ion-icon name="cash-outline" style="font-size: 1.25rem;"></ion-icon> PPSPM Setuju (Teruskan ke Bendahara)
                    </button>
                </div>

                <!-- OPSI TOLAK PPSPM -->
                <div style="border: 1px solid #fca5a5; border-radius: 0.5rem; padding: 1.5rem; background: #fff;">
                    <h3 style="color: #b91c1c; margin-top: 0; margin-bottom: 0.5rem;">PPSPM Menolak / Revisi</h3>
                    <p style="color: #dc2626; margin-bottom: 1rem; font-size: 0.875rem;">PPSPM meminta perbaikan. Berkas dikembalikan ke Teknis.</p>
                    
                    <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #b91c1c;">Catatan Revisi dari PPSPM (Wajib)</label>
                    <textarea name="catatan_revisi" rows="3" style="width: 100%; padding: 0.75rem; border: 1px solid #fca5a5; border-radius: 0.375rem; outline: none; margin-bottom: 1rem; background-color: #fef2f2;" placeholder="Tuliskan alasan PPSPM menolak..."></textarea>
                    
                    <button type="submit" name="action" value="tolak_ppspm" class="btn-primary" style="background-color: #ef4444; width: 100%; font-size: 1rem; padding: 1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <ion-icon name="alert-circle-outline" style="font-size: 1.25rem;"></ion-icon> Kembalikan ke Teknis (Revisi PPSPM)
                    </button>
                </div>
                
            </div>
            
            @error('catatan_revisi')
            <div style="color: #dc2626; font-size: 0.875rem; margin-top: 1rem; text-align: center;">
                {{ $message }}
            </div>
            @enderror

        </form>
    </div>
</div>
@else
<div class="panel">
    <div style="padding: 1.5rem; text-align: center; background-color: #f9fafb;">
        <h3 style="color: #374151; margin: 0;">Status SPJ Saat Ini: <span style="color: #2563eb;">{{ strtoupper(str_replace('_', ' ', $spj->status)) }}</span></h3>
        <p style="color: #6b7280; margin: 0.5rem 0 0 0;">SPJ ini sudah tidak berada dalam antrean verifikasi Anda atau sedang diproses di tahap selanjutnya.</p>
    </div>
    @endif
</div>
@endsection

