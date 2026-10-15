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
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 4px; flex-wrap: wrap;">
                <h2 style="font-size: 24px; font-weight: 700; color: #0f172a; margin: 0;">Verifikasi SPJ: {{ $spj->nomor_spj }}</h2>
                @php
                    $headerBadgeStyle = 'background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;';
                    $headerStatusText = 'Diteruskan ke PPK';
                    if($spj->status == 'diajukan') {
                        $headerBadgeStyle = 'background: #fffbeb; color: #b45309; border: 1px solid #fde68a;';
                        $headerStatusText = 'Menunggu Diperiksa';
                    } elseif($spj->status == 'disetujui_umum') {
                        $headerBadgeStyle = 'background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;';
                        $headerStatusText = 'Diteruskan ke PPK (Menunggu TTD)';
                    } elseif($spj->status == 'disetujui_ppk') {
                        $headerBadgeStyle = 'background: #f5f3ff; color: #6d28d9; border: 1px solid #ddd6fe;';
                        $headerStatusText = 'Diteruskan ke PPSPM';
                    } elseif($spj->status == 'disetujui_ppspm') {
                        $headerBadgeStyle = 'background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;';
                        $headerStatusText = 'Di Bendahara';
                    } elseif(str_contains($spj->status, 'revisi')) {
                        $headerBadgeStyle = 'background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca;';
                        $headerStatusText = 'Dikembalikan (' . strtoupper(str_replace('revisi_', '', $spj->status)) . ')';
                    } elseif($spj->status == 'selesai') {
                        $headerBadgeStyle = 'background: #f0fdf4; color: #15803d; border: 1px solid #86efac;';
                        $headerStatusText = 'Selesai (Cair)';
                    }
                @endphp
                <span style="font-size: 11px; padding: 4px 10px; border-radius: 999px; font-weight: 600; {{ $headerBadgeStyle }}">{{ $headerStatusText }}</span>
            </div>
            <p style="color: #64748b; font-size: 14px; margin: 0;">Diajukan pada {{ $spj->created_at->format('d M Y, H:i') }} oleh <strong>{{ $spj->user->name ?? 'Teknis' }}</strong>.</p>
        </div>
        <div style="display: flex; gap: 12px; align-items: center;">
            <button type="button" onclick="openWaModal()" style="background-color: #25d366; color: white; padding: 10px 16px; border-radius: 8px; border: none; font-weight: 600; font-size: 13px; display: flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(37, 211, 102, 0.25); cursor: pointer; transition: 0.2s;" onmouseover="this.style.backgroundColor='#20bd5a'; this.style.transform='translateY(-1px)';" onmouseout="this.style.backgroundColor='#25d366'; this.style.transform='translateY(0)';">
                <ion-icon name="logo-whatsapp" style="font-size: 18px;"></ion-icon> Kirim WhatsApp
            </button>
            <a href="{{ route('umum.spj.print', $spj->id) }}" target="_blank" style="background-color: #10b981; color: white; padding: 10px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 13px; display: flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2); transition: 0.2s;" onmouseover="this.style.backgroundColor='#059669'; this.style.transform='translateY(-1px)';" onmouseout="this.style.backgroundColor='#10b981'; this.style.transform='translateY(0)';">
                <ion-icon name="print-outline" style="font-size: 16px;"></ion-icon> Cetak Lembar Checklist
            </a>
            <a href="{{ route('umum.spj.index') }}" style="background-color: #f1f5f9; color: #475569; padding: 10px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 13px; border: 1px solid #cbd5e1; transition: 0.2s;" onmouseover="this.style.backgroundColor='#e2e8f0';" onmouseout="this.style.backgroundColor='#f1f5f9';">
                Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
    <div style="background-color: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; padding: 16px 20px; border-radius: 10px; margin-bottom: 24px; display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 500; box-shadow: 0 2px 4px rgba(16,185,129,0.1);">
        <ion-icon name="checkmark-circle" style="font-size: 22px; color: #10b981; flex-shrink: 0;"></ion-icon>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('trigger_wa'))
    <div style="background: linear-gradient(135deg, #ecfdf5, #d1fae5); border: 2px solid #10b981; border-radius: 12px; padding: 18px 24px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15); flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; align-items: center; gap: 14px;">
            <div style="width: 44px; height: 44px; border-radius: 50%; background: #10b981; color: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 6px rgba(16, 185, 129, 0.4);">
                <ion-icon name="logo-whatsapp" style="font-size: 26px;"></ion-icon>
            </div>
            <div>
                <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: #065f46;">Langkah Selanjutnya: Kirim Notifikasi WhatsApp ke Pengaju</h4>
                <p style="margin: 4px 0 0 0; font-size: 13px; color: #047857;">Status SPJ berhasil diperbarui. Pastikan Anda mengirimkan notifikasi WA ke staf pengaju agar langsung mengetahui instruksi selanjutnya.</p>
            </div>
        </div>
        <button type="button" onclick="openWaModal('{{ session('trigger_wa') }}')" style="background-color: #25d366; color: white; padding: 10px 20px; border-radius: 8px; border: none; font-weight: 700; font-size: 13px; display: flex; align-items: center; gap: 8px; cursor: pointer; white-space: nowrap; box-shadow: 0 4px 8px rgba(37,211,102,0.3); transition: 0.2s;" onmouseover="this.style.backgroundColor='#20bd5a';" onmouseout="this.style.backgroundColor='#25d366';">
            <ion-icon name="logo-whatsapp" style="font-size: 18px;"></ion-icon> Buka Pesan WhatsApp
        </button>
    </div>
    @endif
    @if(session('error'))
    <div style="background-color: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 16px 20px; border-radius: 10px; margin-bottom: 24px; display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 500; box-shadow: 0 2px 4px rgba(239,68,68,0.1);">
        <ion-icon name="alert-circle" style="font-size: 22px; color: #ef4444; flex-shrink: 0;"></ion-icon>
        <span>{{ session('error') }}</span>
    </div>
    @endif

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
                <span>Kontak WhatsApp Pengaju (PIC)</span>
                @if($spj->no_wa)
                    <strong style="color: #0f766e; display: flex; align-items: center; gap: 6px;">
                        <ion-icon name="logo-whatsapp" style="color: #25d366; font-size: 18px;"></ion-icon> {{ $spj->no_wa }}
                    </strong>
                @else
                    <span style="color: #94a3b8; font-style: italic;">Tidak dicantumkan pada SPJ</span>
                @endif
            </div>
            <div class="detail-item" style="grid-column: span 2;">
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
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem;">
                @if($spj->dokumen_file)
                    @php
                        $dokumens = json_decode($spj->dokumen_file, true);
                    @endphp
                    
                    @if(is_array($dokumens) && count($dokumens) > 0)
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem;">
                            @foreach($dokumens as $doc)
                                <div style="display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.75rem; background: white; border: 1px solid {{ isset($doc['disiapkan']) && $doc['disiapkan'] ? '#86efac' : '#e2e8f0' }}; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                                    <div style="margin-top: 0.125rem;">
                                        @if(isset($doc['disiapkan']) && $doc['disiapkan'])
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <span style="font-weight: 500; font-size: 0.9rem; color: {{ isset($doc['disiapkan']) && $doc['disiapkan'] ? '#166534' : '#64748b' }};">{{ $doc['nama'] ?? 'Dokumen' }}</span>
                                        <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.25rem;">
                                            {{ isset($doc['disiapkan']) && $doc['disiapkan'] ? 'Telah disiapkan (Fisik)' : 'Tidak diwajibkan untuk Teknis' }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="background-color: #fef2f2; color: #991b1b; padding: 24px; border-radius: 8px; border: 1px dashed #f87171; text-align: center;">
                            <div style="display: flex; align-items: center; justify-content: center; gap: 8px; font-weight: 700; font-size: 16px; margin-bottom: 8px;">
                                <ion-icon name="warning-outline" style="font-size: 24px; color: #ef4444;"></ion-icon> Tidak Ada Lampiran Dokumen
                            </div>
                            <p style="margin: 0; color: #7f1d1d; font-size: 14px;">Staf teknis belum melampirkan checklist dokumen kelengkapan.</p>
                        </div>
                    @endif
                @else
                    <div style="background-color: #fef2f2; color: #991b1b; padding: 24px; border-radius: 8px; border: 1px dashed #f87171; text-align: center;">
                        <div style="display: flex; align-items: center; justify-content: center; gap: 8px; font-weight: 700; font-size: 16px; margin-bottom: 8px;">
                            <ion-icon name="warning-outline" style="font-size: 24px; color: #ef4444;"></ion-icon> Tidak Ada Lampiran Dokumen
                        </div>
                        <p style="margin: 0; color: #7f1d1d; font-size: 14px;">Staf teknis belum melampirkan checklist dokumen kelengkapan.</p>
                    </div>
                @endif
            </div>
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
                        
                        <div style="display: flex; gap: 10px; margin-top: auto;">
                            <button type="submit" name="action" value="setujui" style="flex: 1; background-color: #10b981; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; padding: 14px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: 0.2s; box-shadow: 0 4px 6px rgba(16,185,129,0.25);" onmouseover="this.style.backgroundColor='#059669'; this.style.transform='translateY(-2px)';" onmouseout="this.style.backgroundColor='#10b981'; this.style.transform='translateY(0)';">
                                <ion-icon name="send-outline" style="font-size: 18px;"></ion-icon> Teruskan ke PPK
                            </button>
                            <button type="button" onclick="openWaModal('setuju_umum')" title="Kirim Notifikasi WhatsApp ke Teknis" style="background-color: #25d366; color: white; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; padding: 0 16px; cursor: pointer; display: flex; align-items: center; gap: 6px; box-shadow: 0 4px 6px rgba(37,211,102,0.25); white-space: nowrap; transition: 0.2s;" onmouseover="this.style.backgroundColor='#20bd5a';" onmouseout="this.style.backgroundColor='#25d366';">
                                <ion-icon name="logo-whatsapp" style="font-size: 18px;"></ion-icon> Kirim WA
                            </button>
                        </div>
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
                        
                        <div style="display: flex; gap: 10px; margin-top: auto;">
                            <button type="submit" name="action" value="tolak" style="flex: 1; background-color: #ef4444; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; padding: 14px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: 0.2s; box-shadow: 0 4px 6px rgba(239,68,68,0.25);" onmouseover="this.style.backgroundColor='#dc2626'; this.style.transform='translateY(-2px)';" onmouseout="this.style.backgroundColor='#ef4444'; this.style.transform='translateY(0)';">
                                <ion-icon name="arrow-undo-outline" style="font-size: 18px;"></ion-icon> Kembalikan ke Teknis
                            </button>
                            <button type="button" onclick="openWaModalWithNote('revisi_umum')" title="Kirim Catatan Revisi via WhatsApp" style="background-color: #25d366; color: white; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; padding: 0 16px; cursor: pointer; display: flex; align-items: center; gap: 6px; box-shadow: 0 4px 6px rgba(37,211,102,0.25); white-space: nowrap; transition: 0.2s;" onmouseover="this.style.backgroundColor='#20bd5a';" onmouseout="this.style.backgroundColor='#25d366';">
                                <ion-icon name="logo-whatsapp" style="font-size: 18px;"></ion-icon> Kirim WA
                            </button>
                        </div>
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
    @elseif($spj->status == 'disetujui_umum')
    <div class="show-panel">
        <div class="show-panel-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2>Aksi Verifikasi (Keputusan PPK)</h2>
            <span style="font-size: 12px; background: #eff6ff; color: #1d4ed8; padding: 4px 10px; border-radius: 999px; font-weight: 600; border: 1px solid #bfdbfe;">
                Tahap 2: Tanda Tangan PPK
            </span>
        </div>
        <div style="padding: 24px;">
            <p style="color: #475569; margin-top: 0; margin-bottom: 20px; font-size: 14px;">
                Dokumen fisik sedang berada di meja PPK untuk diperiksa dan ditandatangani. Perbarui status berdasarkan keputusan PPK di bawah ini.
            </p>
            <form action="{{ route('umum.spj.verify', $spj->id) }}" method="POST">
                @csrf
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                    
                    <!-- OPSI SETUJUI PPK -->
                    <div style="border: 1px solid #10b981; border-radius: 12px; padding: 24px; display: flex; flex-direction: column; background: #f0fdf4; box-shadow: 0 4px 10px rgba(16,185,129,0.05);">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                            <ion-icon name="checkmark-circle" style="color: #10b981; font-size: 24px;"></ion-icon>
                            <h3 style="color: #065f46; margin: 0; font-size: 18px;">Persetujuan PPK</h3>
                        </div>
                        <p style="color: #047857; margin-bottom: 20px; font-size: 13px; line-height: 1.5;">Pilih opsi ini jika Pejabat Pembuat Komitmen (PPK) telah menyetujui dan menandatangani dokumen fisik SPJ. Status akan otomatis diteruskan ke PPSPM.</p>
                        
                        <div style="display: flex; gap: 10px; margin-top: auto;">
                            <button type="submit" name="action" value="setujui_ppk" style="flex: 1; background-color: #10b981; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; padding: 14px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: 0.2s; box-shadow: 0 4px 6px rgba(16,185,129,0.25);" onmouseover="this.style.backgroundColor='#059669'; this.style.transform='translateY(-2px)';" onmouseout="this.style.backgroundColor='#10b981'; this.style.transform='translateY(0)';">
                                <ion-icon name="checkmark-done-outline" style="font-size: 18px;"></ion-icon> PPK Menyetujui (TTD)
                            </button>
                            <button type="button" onclick="openWaModal('setuju_ppk')" title="Kirim Notifikasi WhatsApp ke Teknis" style="background-color: #25d366; color: white; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; padding: 0 16px; cursor: pointer; display: flex; align-items: center; gap: 6px; box-shadow: 0 4px 6px rgba(37,211,102,0.25); white-space: nowrap; transition: 0.2s;" onmouseover="this.style.backgroundColor='#20bd5a';" onmouseout="this.style.backgroundColor='#25d366';">
                                <ion-icon name="logo-whatsapp" style="font-size: 18px;"></ion-icon> Kirim WA
                            </button>
                        </div>
                    </div>

                    <!-- OPSI TOLAK PPK -->
                    <div style="border: 1px solid #f87171; border-radius: 12px; padding: 24px; display: flex; flex-direction: column; background: #fef2f2; box-shadow: 0 4px 10px rgba(239,68,68,0.05);">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                            <ion-icon name="close-circle" style="color: #ef4444; font-size: 24px;"></ion-icon>
                            <h3 style="color: #991b1b; margin: 0; font-size: 18px;">Kembalikan / Revisi PPK</h3>
                        </div>
                        <p style="color: #7f1d1d; margin-bottom: 20px; font-size: 13px; line-height: 1.5;">Pilih opsi ini jika PPK menolak atau meminta perbaikan berkas fisik. SPJ akan dikembalikan ke Teknis.</p>
                        
                        <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 13px; color: #991b1b;">Catatan Revisi dari PPK (Wajib jika dikembalikan)</label>
                        <textarea name="catatan_revisi" rows="3" style="width: 100%; padding: 12px; border: 1px solid #fca5a5; border-radius: 8px; outline: none; margin-bottom: 20px; background-color: #ffffff; font-family: inherit; font-size: 13px;" placeholder="Tuliskan catatan perbaikan dari PPK..."></textarea>
                        
                        <div style="display: flex; gap: 10px; margin-top: auto;">
                            <button type="submit" name="action" value="tolak_ppk" style="flex: 1; background-color: #ef4444; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; padding: 14px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: 0.2s; box-shadow: 0 4px 6px rgba(239,68,68,0.25);" onmouseover="this.style.backgroundColor='#dc2626'; this.style.transform='translateY(-2px)';" onmouseout="this.style.backgroundColor='#ef4444'; this.style.transform='translateY(0)';">
                                <ion-icon name="arrow-undo-outline" style="font-size: 18px;"></ion-icon> Kembalikan ke Teknis (Revisi PPK)
                            </button>
                            <button type="button" onclick="openWaModalWithNote('revisi_ppk')" title="Kirim Catatan Revisi via WhatsApp" style="background-color: #25d366; color: white; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; padding: 0 16px; cursor: pointer; display: flex; align-items: center; gap: 6px; box-shadow: 0 4px 6px rgba(37,211,102,0.25); white-space: nowrap; transition: 0.2s;" onmouseover="this.style.backgroundColor='#20bd5a';" onmouseout="this.style.backgroundColor='#25d366';">
                                <ion-icon name="logo-whatsapp" style="font-size: 18px;"></ion-icon> Kirim WA
                            </button>
                        </div>
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
    @elseif($spj->status == 'disetujui_ppk')
    <div class="show-panel">
        <div class="show-panel-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2>Konfirmasi dari PPSPM</h2>
            <span style="font-size: 12px; background: #f5f3ff; color: #6d28d9; padding: 4px 10px; border-radius: 999px; font-weight: 600; border: 1px solid #ddd6fe;">
                Tahap 3: Persetujuan PPSPM
            </span>
        </div>
        <div style="padding: 24px;">
            <p style="color: #475569; margin-top: 0; margin-bottom: 20px; font-size: 14px;">
                Berkas ini sedang berada di meja PPSPM. Silakan perbarui status di bawah ini berdasarkan hasil pemeriksaan PPSPM. Jika disetujui, berkas otomatis diteruskan ke Bendahara.
            </p>
            
            <form action="{{ route('umum.spj.verify', $spj->id) }}" method="POST">
                @csrf
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                    
                    <!-- OPSI SETUJUI PPSPM -->
                    <div style="border: 1px solid #8b5cf6; border-radius: 12px; padding: 24px; display: flex; flex-direction: column; background: #f5f3ff; box-shadow: 0 4px 10px rgba(139,92,246,0.05);">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                            <ion-icon name="checkmark-circle" style="color: #8b5cf6; font-size: 24px;"></ion-icon>
                            <h3 style="color: #5b21b6; margin: 0; font-size: 18px;">PPSPM Menyetujui</h3>
                        </div>
                        <p style="color: #6d28d9; margin-bottom: 20px; font-size: 13px; line-height: 1.5;">PPSPM telah menandatangani dokumen. SPJ akan diteruskan ke Bendahara untuk proses pencairan dana.</p>
                        
                        <div style="display: flex; gap: 10px; margin-top: auto;">
                            <button type="submit" name="action" value="setujui_ppspm" style="flex: 1; background-color: #8b5cf6; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; padding: 14px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: 0.2s; box-shadow: 0 4px 6px rgba(139,92,246,0.25);" onmouseover="this.style.backgroundColor='#7c3aed'; this.style.transform='translateY(-2px)';" onmouseout="this.style.backgroundColor='#8b5cf6'; this.style.transform='translateY(0)';">
                                <ion-icon name="cash-outline" style="font-size: 18px;"></ion-icon> PPSPM Setuju (Teruskan ke Bendahara)
                            </button>
                            <button type="button" onclick="openWaModal('setuju_ppspm')" title="Kirim Notifikasi WhatsApp ke Teknis" style="background-color: #25d366; color: white; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; padding: 0 16px; cursor: pointer; display: flex; align-items: center; gap: 6px; box-shadow: 0 4px 6px rgba(37,211,102,0.25); white-space: nowrap; transition: 0.2s;" onmouseover="this.style.backgroundColor='#20bd5a';" onmouseout="this.style.backgroundColor='#25d366';">
                                <ion-icon name="logo-whatsapp" style="font-size: 18px;"></ion-icon> Kirim WA
                            </button>
                        </div>
                    </div>

                    <!-- OPSI TOLAK PPSPM -->
                    <div style="border: 1px solid #f87171; border-radius: 12px; padding: 24px; display: flex; flex-direction: column; background: #fef2f2; box-shadow: 0 4px 10px rgba(239,68,68,0.05);">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                            <ion-icon name="close-circle" style="color: #ef4444; font-size: 24px;"></ion-icon>
                            <h3 style="color: #991b1b; margin: 0; font-size: 18px;">PPSPM Menolak / Revisi</h3>
                        </div>
                        <p style="color: #7f1d1d; margin-bottom: 20px; font-size: 13px; line-height: 1.5;">PPSPM meminta perbaikan. Berkas dikembalikan ke Teknis.</p>
                        
                        <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 13px; color: #991b1b;">Catatan Revisi dari PPSPM (Wajib jika dikembalikan)</label>
                        <textarea name="catatan_revisi" rows="3" style="width: 100%; padding: 12px; border: 1px solid #fca5a5; border-radius: 8px; outline: none; margin-bottom: 20px; background-color: #ffffff; font-family: inherit; font-size: 13px;" placeholder="Tuliskan alasan PPSPM menolak..."></textarea>
                        
                        <div style="display: flex; gap: 10px; margin-top: auto;">
                            <button type="submit" name="action" value="tolak_ppspm" style="flex: 1; background-color: #ef4444; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; padding: 14px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: 0.2s; box-shadow: 0 4px 6px rgba(239,68,68,0.25);" onmouseover="this.style.backgroundColor='#dc2626'; this.style.transform='translateY(-2px)';" onmouseout="this.style.backgroundColor='#ef4444'; this.style.transform='translateY(0)';">
                                <ion-icon name="arrow-undo-outline" style="font-size: 18px;"></ion-icon> Kembalikan ke Teknis (Revisi PPSPM)
                            </button>
                            <button type="button" onclick="openWaModalWithNote('revisi_ppspm')" title="Kirim Catatan Revisi via WhatsApp" style="background-color: #25d366; color: white; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; padding: 0 16px; cursor: pointer; display: flex; align-items: center; gap: 6px; box-shadow: 0 4px 6px rgba(37,211,102,0.25); white-space: nowrap; transition: 0.2s;" onmouseover="this.style.backgroundColor='#20bd5a';" onmouseout="this.style.backgroundColor='#25d366';">
                                <ion-icon name="logo-whatsapp" style="font-size: 18px;"></ion-icon> Kirim WA
                            </button>
                        </div>
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
    @else
    <div class="show-panel">
        <div style="padding: 28px 24px; text-align: center; background-color: #f8fafc; border-radius: 12px;">
            <div style="width: 56px; height: 56px; border-radius: 50%; background: #eff6ff; color: #2563eb; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                <ion-icon name="information-circle" style="font-size: 32px;"></ion-icon>
            </div>
            <h3 style="color: #1e293b; margin: 0; font-size: 17px; font-weight: 700;">
                Status SPJ: <span style="color: #2563eb;">{{ strtoupper(str_replace('_', ' ', $spj->status)) }}</span>
            </h3>
            <p style="color: #64748b; margin: 8px auto 0 auto; font-size: 14px; max-width: 500px; line-height: 1.5;">
                @if($spj->status === 'disetujui_ppspm')
                    Dokumen telah disetujui PPSPM dan kini berada di <strong>Bendahara Pengeluaran</strong> untuk proses pencairan dana.
                @elseif(str_contains($spj->status, 'revisi'))
                    SPJ ini telah dikembalikan ke staf <strong>Teknis</strong> untuk diperbaiki sesuai catatan verifikasi.
                @elseif($spj->status === 'selesai')
                    SPJ ini telah <strong>selesai dicairkan</strong> oleh Bendahara.
                @else
                    SPJ ini sudah tidak berada dalam antrean pemeriksaan Anda.
                @endif
            </p>
            <div style="margin-top: 20px; display: flex; justify-content: center; gap: 12px;">
                <button type="button" onclick="openWaModal()" style="background-color: #25d366; color: white; padding: 11px 22px; border-radius: 8px; border: none; font-weight: 600; font-size: 13px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 6px rgba(37,211,102,0.25); cursor: pointer; transition: 0.2s;" onmouseover="this.style.backgroundColor='#20bd5a';" onmouseout="this.style.backgroundColor='#25d366';">
                    <ion-icon name="logo-whatsapp" style="font-size: 18px;"></ion-icon> Hubungi Pengaju via WhatsApp
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- MODAL WHATSAPP MANUAL -->
<div id="waModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 20px; box-sizing: border-box;">
    <div style="background: #ffffff; border-radius: 16px; max-width: 600px; width: 100%; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); overflow: hidden; animation: popIn 0.2s ease-out;">
        <div style="background: linear-gradient(135deg, #25d366, #128c7e); color: white; padding: 18px 24px; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center;">
                    <ion-icon name="logo-whatsapp" style="font-size: 26px;"></ion-icon>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 17px; font-weight: 700; color: white;">Kirim Notifikasi WhatsApp (Manual)</h3>
                    <small style="opacity: 0.9; font-size: 12px;">Template sudah terisi otomatis sesuai laporan SPJ ini</small>
                </div>
            </div>
            <button type="button" onclick="closeWaModal()" style="background: rgba(255,255,255,0.15); border: none; color: white; width: 32px; height: 32px; cursor: pointer; display: flex; align-items: center; justify-content: center; border-radius: 8px; transition: 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.3)';" onmouseout="this.style.background='rgba(255,255,255,0.15)';">
                <ion-icon name="close" style="font-size: 20px;"></ion-icon>
            </button>
        </div>

        <div style="padding: 24px;">
            <!-- NOMOR HP TUJUAN -->
            @php
                $recipientWa = $spj->no_wa ?: ($spj->user->no_wa ?? '');
            @endphp
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 6px;">
                    Nomor WhatsApp Pengaju ({{ $spj->user->name ?? 'Teknis' }}):
                </label>
                <div style="position: relative;">
                    <input type="text" id="waRecipient" value="{{ $recipientWa }}" placeholder="Contoh: 081234567890 (atau biarkan kosong untuk pilih kontak di WA)" style="width: 100%; box-sizing: border-box; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; outline: none; font-family: inherit;">
                </div>
                @if(empty($recipientWa))
                <small style="color: #d97706; font-size: 11px; display: flex; align-items: center; gap: 4px; margin-top: 5px;">
                    <ion-icon name="information-circle-outline"></ion-icon> Pengaju belum mencantumkan nomor WhatsApp pada SPJ ini. Anda bisa ketik nomor HP manual di atas, atau langsung klik "Buka di WhatsApp" untuk memilih kontak.
                </small>
                @endif
            </div>

            <!-- PILIHAN TEMPLATE CEPAT -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 8px;">Pilih Template Pesan:</label>
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <button type="button" id="btnTplSetujuUmum" onclick="setWaTemplate('setuju_umum')" style="padding: 6px 14px; font-size: 12px; font-weight: 600; border-radius: 999px; border: 1px solid #10b981; background: #ecfdf5; color: #047857; cursor: pointer; transition: 0.2s;">
                        ✅ 1. Teruskan ke PPK
                    </button>
                    <button type="button" id="btnTplSetujuPpk" onclick="setWaTemplate('setuju_ppk')" style="padding: 6px 14px; font-size: 12px; font-weight: 600; border-radius: 999px; border: 1px solid #cbd5e1; background: #ffffff; color: #64748b; cursor: pointer; transition: 0.2s;">
                        ✍️ 2. Persetujuan PPK
                    </button>
                    <button type="button" id="btnTplSetujuPpspm" onclick="setWaTemplate('setuju_ppspm')" style="padding: 6px 14px; font-size: 12px; font-weight: 600; border-radius: 999px; border: 1px solid #cbd5e1; background: #ffffff; color: #64748b; cursor: pointer; transition: 0.2s;">
                        💵 3. Teruskan ke Bendahara
                    </button>
                    <button type="button" id="btnTplRevisiUmum" onclick="setWaTemplate('revisi_umum')" style="padding: 6px 14px; font-size: 12px; font-weight: 600; border-radius: 999px; border: 1px solid #cbd5e1; background: #ffffff; color: #64748b; cursor: pointer; transition: 0.2s;">
                        ⚠️ Revisi Umum
                    </button>
                    <button type="button" id="btnTplRevisiPpk" onclick="setWaTemplate('revisi_ppk')" style="padding: 6px 14px; font-size: 12px; font-weight: 600; border-radius: 999px; border: 1px solid #cbd5e1; background: #ffffff; color: #64748b; cursor: pointer; transition: 0.2s;">
                        ⚠️ Revisi PPK
                    </button>
                    <button type="button" id="btnTplRevisiPpspm" onclick="setWaTemplate('revisi_ppspm')" style="padding: 6px 14px; font-size: 12px; font-weight: 600; border-radius: 999px; border: 1px solid #cbd5e1; background: #ffffff; color: #64748b; cursor: pointer; transition: 0.2s;">
                        ⚠️ Revisi PPSPM
                    </button>
                    <button type="button" id="btnTplFisik" onclick="setWaTemplate('fisik')" style="padding: 6px 14px; font-size: 12px; font-weight: 600; border-radius: 999px; border: 1px solid #cbd5e1; background: #ffffff; color: #64748b; cursor: pointer; transition: 0.2s;">
                        📄 Berkas Fisik
                    </button>
                </div>
            </div>

            <!-- PREVIEW TEXTAREA -->
            <div style="margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <label style="font-size: 13px; font-weight: 600; color: #334155;">Isi Pesan (Bisa Diedit Sesuai Kebutuhan):</label>
                    <button type="button" onclick="copyWaText()" id="btnCopyWa" style="background: none; border: none; color: #2563eb; font-size: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 4px;">
                        <ion-icon name="copy-outline"></ion-icon> <span>Salin Teks</span>
                    </button>
                </div>
                <textarea id="waMessageText" rows="9" style="width: 100%; box-sizing: border-box; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; line-height: 1.5; font-family: inherit; outline: none; background: #f8fafc; color: #0f172a; resize: vertical;"></textarea>
            </div>

            <!-- BUTTONS ACTION -->
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" onclick="closeWaModal()" style="padding: 10px 18px; border: 1px solid #cbd5e1; background: #f1f5f9; color: #475569; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                    Tutup
                </button>
                <button type="button" onclick="sendWhatsAppNow()" style="padding: 10px 22px; border: none; background: #25d366; color: white; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 6px rgba(37, 211, 102, 0.25); transition: 0.2s;" onmouseover="this.style.backgroundColor='#20bd5a';" onmouseout="this.style.backgroundColor='#25d366';">
                    <ion-icon name="logo-whatsapp" style="font-size: 18px;"></ion-icon> Buka di WhatsApp
                </button>
            </div>
        </div>
    </div>
</div>

@php
    $spjJsData = [
        'nomor' => $spj->nomor_spj,
        'pengaju' => $spj->user->name ?? 'Staf Teknis',
        'kegiatan' => $spj->kegiatan,
        'nilai' => 'Rp ' . number_format($spj->nilai, 0, ',', '.'),
        'tanggal' => $spj->tanggal ? $spj->tanggal->format('d M Y') : '-',
        'status' => strtoupper(str_replace('_', ' ', $spj->status)),
        'catatanRevisi' => $spj->catatan_revisi ?? '',
        'linkDetail' => route('teknis.spj.show', $spj->id),
        'linkEdit' => route('teknis.spj.edit', $spj->id),
    ];
@endphp

@push('scripts')
<script>
    const spjData = @json($spjJsData);

    let currentWaTpl = 'setuju_umum';

    function openWaModal(defaultTpl = 'setuju_umum') {
        const modal = document.getElementById('waModal');
        modal.style.display = 'flex';
        setWaTemplate(defaultTpl);
    }

    function openWaModalWithNote(stageTpl = 'revisi_umum') {
        const activeTextarea = document.querySelector('textarea[name="catatan_revisi"]');
        if (activeTextarea && activeTextarea.value.trim()) {
            spjData.activeInputNote = activeTextarea.value.trim();
        } else {
            spjData.activeInputNote = '';
        }
        openWaModal(stageTpl);
    }

    function closeWaModal() {
        document.getElementById('waModal').style.display = 'none';
    }

    // Tutup jika klik overlay luar
    window.addEventListener('click', function(e) {
        const modal = document.getElementById('waModal');
        if (e.target === modal) {
            closeWaModal();
        }
    });

    function setWaTemplate(tpl) {
        if (tpl === 'setuju') tpl = 'setuju_umum';
        if (tpl === 'revisi') tpl = 'revisi_umum';

        currentWaTpl = tpl;
        const textarea = document.getElementById('waMessageText');

        const btnMap = {
            'setuju_umum': document.getElementById('btnTplSetujuUmum'),
            'setuju_ppk': document.getElementById('btnTplSetujuPpk'),
            'setuju_ppspm': document.getElementById('btnTplSetujuPpspm'),
            'revisi_umum': document.getElementById('btnTplRevisiUmum'),
            'revisi_ppk': document.getElementById('btnTplRevisiPpk'),
            'revisi_ppspm': document.getElementById('btnTplRevisiPpspm'),
            'fisik': document.getElementById('btnTplFisik'),
        };

        // Reset semua style button
        Object.values(btnMap).forEach(btn => {
            if (btn) {
                btn.style.background = '#ffffff';
                btn.style.color = '#64748b';
                btn.style.borderColor = '#cbd5e1';
            }
        });

        // Set style button aktif
        const activeBtn = btnMap[tpl];
        if (activeBtn) {
            if (tpl.startsWith('setuju')) {
                activeBtn.style.background = '#ecfdf5';
                activeBtn.style.color = '#047857';
                activeBtn.style.borderColor = '#10b981';
            } else if (tpl.startsWith('revisi')) {
                activeBtn.style.background = '#fef2f2';
                activeBtn.style.color = '#b91c1c';
                activeBtn.style.borderColor = '#f87171';
            } else {
                activeBtn.style.background = '#eff6ff';
                activeBtn.style.color = '#1d4ed8';
                activeBtn.style.borderColor = '#60a5fa';
            }
        }

        const noteText = spjData.activeInputNote || spjData.catatanRevisi || 'Mohon lengkapi dan sesuaikan berkas SPJ sesuai checklist persyaratan.';

        let message = '';

        if (tpl === 'setuju_umum') {
            message = `*PEMBERITAHUAN SPJ: DIVERIFIKASI UMUM*\n`
                    + `BPS Kabupaten Kolaka Utara\n\n`
                    + `Halo Sdr/i *${spjData.pengaju}*,\n\n`
                    + `Pengajuan SPJ Anda telah diverifikasi oleh Subbagian Umum dan dinyatakan *LENGKAP & BENAR*:\n\n`
                    + `• *No. SPJ*  : ${spjData.nomor}\n`
                    + `• *Kegiatan* : ${spjData.kegiatan}\n`
                    + `• *Nilai*    : ${spjData.nilai}\n`
                    + `• *Status*   : DISETUJUI UMUM (Menunggu TTD PPK)\n\n`
                    + `📌 *Langkah Selanjutnya:*\n`
                    + `Harap segera membawa dokumen fisik SPJ ke meja *Pejabat Pembuat Komitmen (PPK)* untuk ditandatangani.\n\n`
                    + `Pantau detail dokumen Anda di sini:\n`
                    + `${spjData.linkDetail}\n\n`
                    + `Terima kasih.\n`
                    + `_Sistem Informasi SPJ BPS Kolaka Utara_`;

        } else if (tpl === 'setuju_ppk') {
            message = `*PEMBERITAHUAN SPJ: PERSETUJUAN PPK*\n`
                    + `BPS Kabupaten Kolaka Utara\n\n`
                    + `Halo Sdr/i *${spjData.pengaju}*,\n\n`
                    + `Dokumen fisik SPJ Anda telah *DITANDATANGANI OLEH PPK*:\n\n`
                    + `• *No. SPJ*  : ${spjData.nomor}\n`
                    + `• *Kegiatan* : ${spjData.kegiatan}\n`
                    + `• *Nilai*    : ${spjData.nilai}\n`
                    + `• *Status*   : DISETUJUI PPK (Menuju Persetujuan PPSPM)\n\n`
                    + `📌 *Langkah Selanjutnya:*\n`
                    + `Harap segera membawa berkas fisik SPJ ke meja *PPSPM* untuk diverifikasi dan diterbitkan SPM.\n\n`
                    + `Pantau detail dokumen Anda di sini:\n`
                    + `${spjData.linkDetail}\n\n`
                    + `Terima kasih.\n`
                    + `_Sistem Informasi SPJ BPS Kolaka Utara_`;

        } else if (tpl === 'setuju_ppspm') {
            message = `*PEMBERITAHUAN SPJ: PERSETUJUAN PPSPM*\n`
                    + `BPS Kabupaten Kolaka Utara\n\n`
                    + `Halo Sdr/i *${spjData.pengaju}*,\n\n`
                    + `Dokumen pengajuan SPJ Anda telah *DISETUJUI OLEH PPSPM* dan kini telah diteruskan ke Bendahara Pengeluaran:\n\n`
                    + `• *No. SPJ*  : ${spjData.nomor}\n`
                    + `• *Kegiatan* : ${spjData.kegiatan}\n`
                    + `• *Nilai*    : ${spjData.nilai}\n`
                    + `• *Status*   : DISETUJUI PPSPM (Dalam Proses Pencairan Bendahara)\n\n`
                    + `📌 *Langkah Selanjutnya:*\n`
                    + `SPJ Anda kini berada di Bendahara Pengeluaran untuk proses pencairan dana. Harap pantau rekening atau status di sistem.\n\n`
                    + `Pantau detail dokumen Anda di sini:\n`
                    + `${spjData.linkDetail}\n\n`
                    + `Terima kasih.\n`
                    + `_Sistem Informasi SPJ BPS Kolaka Utara_`;

        } else if (tpl === 'revisi_umum') {
            message = `*PEMBERITAHUAN PERBAIKAN (REVISI) SPJ*\n`
                    + `BPS Kabupaten Kolaka Utara\n\n`
                    + `Halo Sdr/i *${spjData.pengaju}*,\n\n`
                    + `Dokumen SPJ Anda memerlukan *PERBAIKAN / REVISI* dari Subbagian Umum:\n\n`
                    + `• *No. SPJ*  : ${spjData.nomor}\n`
                    + `• *Kegiatan* : ${spjData.kegiatan}\n`
                    + `• *Catatan Verifikator Umum*:\n"${noteText}"\n\n`
                    + `Mohon segera perbaiki berkas dan ajukan kembali melalui tautan berikut:\n`
                    + `${spjData.linkEdit}\n\n`
                    + `Terima kasih.\n`
                    + `_Sistem Informasi SPJ BPS Kolaka Utara_`;

        } else if (tpl === 'revisi_ppk') {
            message = `*PEMBERITAHUAN REVISI DARI PPK*\n`
                    + `BPS Kabupaten Kolaka Utara\n\n`
                    + `Halo Sdr/i *${spjData.pengaju}*,\n\n`
                    + `Dokumen SPJ Anda dikembalikan dengan *CATATAN REVISI DARI PPK*:\n\n`
                    + `• *No. SPJ*  : ${spjData.nomor}\n`
                    + `• *Kegiatan* : ${spjData.kegiatan}\n`
                    + `• *Catatan Perbaikan PPK*:\n"${noteText}"\n\n`
                    + `Mohon segera lakukan penyesuaian berkas melalui tautan berikut:\n`
                    + `${spjData.linkEdit}\n\n`
                    + `Terima kasih.\n`
                    + `_Sistem Informasi SPJ BPS Kolaka Utara_`;

        } else if (tpl === 'revisi_ppspm') {
            message = `*PEMBERITAHUAN REVISI DARI PPSPM*\n`
                    + `BPS Kabupaten Kolaka Utara\n\n`
                    + `Halo Sdr/i *${spjData.pengaju}*,\n\n`
                    + `Dokumen SPJ Anda dikembalikan dengan *CATATAN REVISI DARI PPSPM*:\n\n`
                    + `• *No. SPJ*  : ${spjData.nomor}\n`
                    + `• *Kegiatan* : ${spjData.kegiatan}\n`
                    + `• *Catatan Perbaikan PPSPM*:\n"${noteText}"\n\n`
                    + `Mohon segera perbaiki berkas melalui tautan berikut:\n`
                    + `${spjData.linkEdit}\n\n`
                    + `Terima kasih.\n`
                    + `_Sistem Informasi SPJ BPS Kolaka Utara_`;

        } else if (tpl === 'fisik') {
            message = `*PENGINGAT DOKUMEN FISIK SPJ*\n`
                    + `BPS Kabupaten Kolaka Utara\n\n`
                    + `Halo Sdr/i *${spjData.pengaju}*,\n\n`
                    + `Mengingatkan untuk pengajuan SPJ Anda berikut:\n\n`
                    + `• *No. SPJ*  : ${spjData.nomor}\n`
                    + `• *Kegiatan* : ${spjData.kegiatan}\n`
                    + `• *Nilai*    : ${spjData.nilai}\n\n`
                    + `Mohon segera menyerahkan berkas fisik SPJ ke meja verifikasi Umum untuk kelengkapan administrasi.\n\n`
                    + `Terima kasih.\n`
                    + `_Sistem Informasi SPJ BPS Kolaka Utara_`;
        }

        textarea.value = message;
    }

    function copyWaText() {
        const textarea = document.getElementById('waMessageText');
        textarea.select();
        navigator.clipboard.writeText(textarea.value).then(() => {
            const btnCopy = document.getElementById('btnCopyWa');
            const span = btnCopy.querySelector('span');
            span.textContent = 'Tersalin! ✔';
            btnCopy.style.color = '#10b981';
            setTimeout(() => {
                span.textContent = 'Salin Teks';
                btnCopy.style.color = '#2563eb';
            }, 2000);
        });
    }

    function sendWhatsAppNow() {
        let phone = document.getElementById('waRecipient').value.trim();
        const msg = document.getElementById('waMessageText').value.trim();

        if (!msg) {
            alert('Isi pesan tidak boleh kosong!');
            return;
        }

        phone = phone.replace(/[^0-9]/g, '');

        if (phone.startsWith('0')) {
            phone = '62' + phone.substring(1);
        } else if (phone.startsWith('8')) {
            phone = '62' + phone;
        }

        let url = 'https://api.whatsapp.com/send?text=' + encodeURIComponent(msg);
        if (phone) {
            url = 'https://api.whatsapp.com/send?phone=' + phone + '&text=' + encodeURIComponent(msg);
        }

        window.open(url, '_blank');
    }
</script>

@if(session('trigger_wa'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            openWaModal('{{ session('trigger_wa') }}');
        }, 350);
    });
</script>
@endif
@endpush
@endsection

