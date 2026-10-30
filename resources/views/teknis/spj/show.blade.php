@extends('layouts.teknis')

@php
$title = 'Detail SPJ';
$subtitle = 'Informasi lengkap pengajuan SPJ Anda.';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/teknis/spj.css'])
@endpush

<div class="dashboard-heading">
    <div>
        <h2 class="dashboard-heading-title">Detail Pengajuan SPJ</h2>
        <p class="dashboard-heading-subtitle">Diajukan pada: {{ $spj->created_at->format('d F Y, H:i') }} &bull; No: <strong>{{ $spj->nomor_spj ?: '-' }}</strong></p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        @if(in_array($spj->status, ['draft', 'dikembalikan', 'revisi_umum', 'revisi_ppk', 'revisi_ppspm', 'revisi_bendahara']))
        <a href="{{ route('teknis.spj.edit', $spj->id) }}" class="btn-dash-primary" style="background-color: var(--dash-peach-text) !important;">
            <ion-icon name="create-outline"></ion-icon>
            Edit SPJ
        </a>
        @endif
        <a href="{{ route('teknis.spj.print', $spj->id) }}" target="_blank" class="spj-btn" style="background: #0284c7; color: white; border: none; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25); text-decoration: none; display: inline-flex; align-items: center; gap: 6px;" title="Cetak Lembar Checklist SPJ (Buka di Tab Baru)">
            <ion-icon name="print-outline" style="font-size: 18px;"></ion-icon>
            <span>Cetak Lembar Checklist</span>
        </a>
        <button type="button" onclick="openWaModal()" class="spj-btn" style="background: #25d366; color: white; border: none; box-shadow: 0 4px 12px rgba(37, 211, 102, 0.25); cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
            <ion-icon name="logo-whatsapp" style="font-size: 18px;"></ion-icon>
            Hubungi Bagian Umum (WA)
        </button>
        <a href="{{ route('teknis.spj.index') }}" class="spj-btn spj-btn-secondary">
            <ion-icon name="arrow-back-outline"></ion-icon>
            Kembali
        </a>
    </div>
</div>

<x-teknis.spj-stepper :spj="$spj" />

<div class="spj-panel">
    <div class="spj-panel-header">
        <div>
            <h2>Data Utama SPJ</h2>
            <p>Rincian lengkap dari dokumen pertanggungjawaban yang diajukan.</p>
        </div>
        <div>
            @php
                $badgeClass = 'dash-badge-draft';
                if(in_array($spj->status, ['selesai', 'disetujui_ppk', 'disetujui_umum', 'disetujui_ppspm'])) $badgeClass = 'dash-badge-mint';
                elseif($spj->status === 'diajukan') {
                    $badgeClass = $spj->pemeriksaan_dimulai_at ? 'dash-badge-amber' : 'dash-badge-cyan';
                }
                elseif($spj->status === 'menunggu_otp_kpa') $badgeClass = 'dash-badge-cyan';
                elseif($spj->status === 'proses_ls') $badgeClass = 'dash-badge-purple';
                elseif(in_array($spj->status, ['dikembalikan', 'revisi_bendahara', 'revisi_ppk', 'revisi_ppspm', 'revisi_umum'])) $badgeClass = 'dash-badge-peach';
            @endphp
            <span class="dash-badge {{ $badgeClass }}" style="font-size: 13px; padding: 6px 16px;">
                <span class="badge-dot"></span>
                Status: {{ $spj->status_label }}
            </span>
        </div>
    </div>
    
    <div class="spj-panel-body">
        
        @if($spj->status === 'diajukan')
            @if(!$spj->berkas_fisik_diterima_at)
            <div class="spj-alert" style="background: #eff6ff; border: 1.5px solid #93c5fd; color: #1e40af; align-items: flex-start; margin-bottom: 24px; padding: 16px 20px; border-radius: 12px; display: flex; gap: 14px; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.08);">
                <div style="flex-shrink: 0; font-size: 26px; color: #2563eb; margin-top: 2px;">
                    <ion-icon name="document-attach-outline"></ion-icon>
                </div>
                <div>
                    <h3 style="font-size: 15px; font-weight: 800; margin: 0 0 4px 0; color: #1e40af;">
                        Langkah Selanjutnya: Bawa Berkas Fisik ke Subbagian Umum
                    </h3>
                    <p style="font-weight: 400; margin: 0; line-height: 1.55; color: #1e3a8a; font-size: 13.5px;">
                        Pengajuan SPJ Anda telah tercatat di sistem. Agar proses verifikasi berkas administrasi dapat segera diproses oleh Tim Verifikator, <strong>harap membawa dan menyerahkan dokumen fisik SPJ beserta seluruh lampirannya ke meja Subbagian Umum</strong>.
                    </p>
                </div>
            </div>
            @elseif(!$spj->pemeriksaan_dimulai_at)
            <div class="spj-alert" style="background: #eff6ff; border: 1.5px solid #93c5fd; color: #1e40af; align-items: flex-start; margin-bottom: 24px; padding: 16px 20px; border-radius: 12px; display: flex; gap: 14px; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.08);">
                <div style="flex-shrink: 0; font-size: 26px; color: #2563eb; margin-top: 2px;">
                    <ion-icon name="file-tray-full-outline"></ion-icon>
                </div>
                <div>
                    <h3 style="font-size: 15px; font-weight: 800; margin: 0 0 4px 0; color: #1e40af;">
                        Berkas Fisik Telah Diterima Subbagian Umum (Antrean Pemeriksaan)
                    </h3>
                    <p style="font-weight: 400; margin: 0; line-height: 1.55; color: #1e3a8a; font-size: 13.5px;">
                        Dokumen fisik SPJ Anda telah dikonfirmasi diterima di meja Subbagian Umum pada <strong>{{ \Carbon\Carbon::parse($spj->berkas_fisik_diterima_at)->format('d F Y, H:i') }} WITA</strong> dan saat ini berada di dalam antrean untuk diperiksa verifikator.
                    </p>
                </div>
            </div>
            @else
            <div class="spj-alert" style="background: #fffbeb; border: 1.5px solid #fde68a; color: #b45309; align-items: flex-start; margin-bottom: 24px; padding: 16px 20px; border-radius: 12px; display: flex; gap: 14px; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.08);">
                <div style="flex-shrink: 0; font-size: 26px; color: #d97706; margin-top: 2px;">
                    <ion-icon name="time-outline"></ion-icon>
                </div>
                <div>
                    <h3 style="font-size: 15px; font-weight: 800; margin: 0 0 4px 0; color: #b45309;">
                        Sedang Diperiksa oleh Subbagian Umum
                    </h3>
                    <p style="font-weight: 400; margin: 0; line-height: 1.55; color: #92400e; font-size: 13.5px;">
                        Berkas fisik SPJ Anda <strong>sedang diperiksa</strong> oleh tim verifikator Subbagian Umum (pemeriksaan dimulai: <strong>{{ \Carbon\Carbon::parse($spj->pemeriksaan_dimulai_at)->format('d F Y, H:i') }} WITA</strong>). Silakan pantau perkembangan verifikasi berkas secara berkala.
                    </p>
                </div>
            </div>
            @endif
        @elseif($spj->status === 'disetujui_umum')
            <div class="spj-alert" style="background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; align-items: flex-start; margin-bottom: 24px; padding: 14px 18px; border-radius: 10px; display: flex; gap: 12px;">
                <div style="flex-shrink: 0; font-size: 22px; color: #2563eb;">
                    <ion-icon name="checkmark-circle-outline"></ion-icon>
                </div>
                <div>
                    <h3 style="font-size: 14px; font-weight: 700; margin-bottom: 4px; color: #1e40af;">Status: Diteruskan ke PPK</h3>
                    <p style="font-weight: 400; margin: 0; line-height: 1.5; color: #1e3a8a; font-size: 13px;">
                        Berkas fisik telah diverifikasi lengkap oleh Subbagian Umum dan diteruskan kepada Pejabat Pembuat Komitmen (PPK) untuk penandatanganan pengesahan.
                    </p>
                </div>
            </div>
        @elseif($spj->status === 'disetujui_ppk')
            <div class="spj-alert" style="background: #f5f3ff; border: 1px solid #ddd6fe; color: #5b21b6; align-items: flex-start; margin-bottom: 24px; padding: 14px 18px; border-radius: 10px; display: flex; gap: 12px;">
                <div style="flex-shrink: 0; font-size: 22px; color: #7c3aed;">
                    <ion-icon name="checkmark-done-outline"></ion-icon>
                </div>
                <div>
                    <h3 style="font-size: 14px; font-weight: 700; margin-bottom: 4px; color: #5b21b6;">Status: Diteruskan ke PPSPM</h3>
                    <p style="font-weight: 400; margin: 0; line-height: 1.5; color: #4c1d95; font-size: 13px;">
                        SPJ telah disetujui oleh PPK dan diteruskan ke Pejabat Penandatangan Surat Perintah Membayar (PPSPM) untuk pengujian dan penerbitan SPM.
                    </p>
                </div>
            </div>
        @elseif($spj->status === 'disetujui_ppspm')
            <div class="spj-alert" style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; align-items: flex-start; margin-bottom: 24px; padding: 14px 18px; border-radius: 10px; display: flex; gap: 12px;">
                <div style="flex-shrink: 0; font-size: 22px; color: #16a34a;">
                    <ion-icon name="document-text-outline"></ion-icon>
                </div>
                <div>
                    <h3 style="font-size: 14px; font-weight: 700; margin-bottom: 4px; color: #166534;">Status: Siap Diproses Bendahara (SPM Terbit)</h3>
                    <p style="font-weight: 400; margin: 0; line-height: 1.5; color: #14532d; font-size: 13px;">
                        Dokumen SPM telah ditandatangani oleh PPSPM dan berkas telah diteruskan ke Bendahara Pengeluaran untuk proses pencairan dan pembayaran dana.
                    </p>
                </div>
            </div>
        @elseif($spj->status === 'menunggu_otp_kpa')
            <div class="spj-alert" style="background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; align-items: flex-start; margin-bottom: 24px; padding: 14px 18px; border-radius: 10px; display: flex; gap: 12px;">
                <div style="flex-shrink: 0; font-size: 22px; color: #2563eb;">
                    <ion-icon name="key-outline"></ion-icon>
                </div>
                <div>
                    <h3 style="font-size: 14px; font-weight: 700; margin-bottom: 4px; color: #1e40af;">Status: Menunggu OTP QLOLA KPA</h3>
                    <p style="font-weight: 400; margin: 0; line-height: 1.5; color: #1e3a8a; font-size: 13px;">
                        Transaksi telah diinput oleh Bendahara Pengeluaran ke BRI QLOLA dan saat ini sedang menunggu otorisasi OTP dari Kuasa Pengguna Anggaran (KPA). Pencairan dana ke rekening Anda akan segera terlaksana setelah otorisasi selesai.
                    </p>
                </div>
            </div>
        @elseif($spj->status === 'proses_ls')
            <div class="spj-alert" style="background: #f5f3ff; border: 1px solid #ddd6fe; color: #5b21b6; align-items: flex-start; margin-bottom: 24px; padding: 14px 18px; border-radius: 10px; display: flex; gap: 12px;">
                <div style="flex-shrink: 0; font-size: 22px; color: #7c3aed;">
                    <ion-icon name="paper-plane-outline"></ion-icon>
                </div>
                <div>
                    <h3 style="font-size: 14px; font-weight: 700; margin-bottom: 4px; color: #5b21b6;">Status: {{ $spj->status_label }}</h3>
                    <p style="font-weight: 400; margin: 0; line-height: 1.5; color: #4c1d95; font-size: 13px;">
                        {{ $spj->keterangan_ls }}
                    </p>
                </div>
            </div>
        @elseif($spj->status === 'selesai')
            <div class="spj-alert" style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; align-items: flex-start; margin-bottom: 24px; padding: 14px 18px; border-radius: 10px; display: flex; gap: 12px;">
                <div style="flex-shrink: 0; font-size: 22px; color: #16a34a;">
                    <ion-icon name="checkmark-done-circle"></ion-icon>
                </div>
                <div>
                    <h3 style="font-size: 14px; font-weight: 700; margin-bottom: 4px; color: #166534;">Proses Pembayaran Telah Selesai</h3>
                    <p style="font-weight: 400; margin: 0; line-height: 1.5; color: #14532d; font-size: 13px;">
                        Dana telah berhasil diproses oleh Bendahara. <strong>Silahkan cek mutasi rekening Anda.</strong>
                        @if($spj->bukti_transfer)
                            <br><span style="display: inline-block; margin-top: 6px; font-size: 12px; background: #dcfce7; padding: 2px 8px; border-radius: 4px; border: 1px solid #86efac; color: #15803d;">Ref / No. Bukti: <strong>{{ $spj->bukti_transfer }}</strong></span>
                        @endif
                    </p>
                </div>
            </div>
        @endif

        @if(in_array($spj->status, ['dikembalikan', 'revisi_umum', 'revisi_ppk', 'revisi_ppspm', 'revisi_bendahara']) && $spj->catatan_revisi)
            <div class="spj-alert spj-alert-error" style="align-items: flex-start; margin-bottom: 24px;">
                <div style="flex-shrink: 0; font-size: 22px; color: var(--dash-peach-text);">
                    <ion-icon name="alert-circle"></ion-icon>
                </div>
                <div>
                    <h3 style="font-size: 14px; font-weight: 700; margin-bottom: 4px; color: var(--dash-peach-text);">Catatan Revisi dari {{ strtoupper(explode('_', $spj->status)[1] ?? 'Pemeriksa') }}</h3>
                    <p style="font-weight: 400; margin: 0; white-space: pre-line; line-height: 1.5; color: var(--dash-text-dark);">{{ $spj->catatan_revisi }}</p>
                </div>
            </div>
        @endif

        <div class="spj-detail-grid">
            <div class="spj-detail-item">
                <div class="spj-detail-label">Nomor SPJ</div>
                <div class="spj-detail-value" style="font-weight: 700; color: var(--dash-text-dark);">{{ $spj->nomor_spj ?: '-' }}</div>
            </div>

            <div class="spj-detail-item">
                <div class="spj-detail-label">Tanggal Kegiatan</div>
                <div class="spj-detail-value">{{ $spj->tanggal->format('d F Y') }}</div>
            </div>

            <div class="spj-detail-item">
                <div class="spj-detail-label">Nilai</div>
                <div class="spj-detail-value" style="color: var(--dash-mint-text);">Rp {{ number_format($spj->nilai, 0, ',', '.') }}</div>
            </div>

            <div class="spj-detail-item">
                <div class="spj-detail-label">WhatsApp PIC / Pengaju</div>
                <div class="spj-detail-value" style="color: var(--dash-cyan-text);">{{ $spj->no_wa ?: '-' }}</div>
            </div>
        </div>

        <div style="margin-top: 16px;" class="spj-detail-item">
            <div class="spj-detail-label">Uraian Kegiatan</div>
            <div class="spj-detail-value" style="font-size: 15px; font-weight: 600; line-height: 1.6;">{{ $spj->kegiatan }}</div>
        </div>

        @if($spj->keterangan)
        <div style="margin-top: 16px;" class="spj-detail-item">
            <div class="spj-detail-label">Keterangan / Catatan Teknis</div>
            <div class="spj-detail-value" style="font-size: 14px; line-height: 1.6; color: var(--dash-text-muted);">{{ $spj->keterangan }}</div>
        </div>
        @endif
        
        {{-- ====================================================================
             1. BERKAS DIGITAL / LAMPIRAN FILE SCAN / FOTO YANG DIUNGGAH
             ==================================================================== --}}
        <div style="margin-top: 28px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px solid var(--dash-border); padding-bottom: 8px; flex-wrap: wrap; gap: 8px;">
                <h3 style="font-size: 15px; font-weight: 700; color: var(--dash-text-dark); margin: 0; display: flex; align-items: center; gap: 8px;">
                    <ion-icon name="attach-outline" style="font-size: 18px; color: #0284c7;"></ion-icon>
                    Lampiran Berkas Digital (Scan / Foto)
                </h3>
                @if($spj->file_lampiran_url)
                    <span style="font-size: 12px; font-weight: 600; background: #ecfdf5; color: #059669; padding: 4px 10px; border-radius: 999px; border: 1px solid #a7f3d0; display: inline-flex; align-items: center; gap: 4px;">
                        <ion-icon name="checkmark-circle"></ion-icon> File Terunggah
                    </span>
                @else
                    <span style="font-size: 12px; font-weight: 600; background: #fef2f2; color: #dc2626; padding: 4px 10px; border-radius: 999px; border: 1px solid #fecaca; display: inline-flex; align-items: center; gap: 4px;">
                        <ion-icon name="alert-circle"></ion-icon> Belum Diunggah
                    </span>
                @endif
            </div>

            @if($spj->file_lampiran_url)
                @php
                    $filePath = $spj->file_lampiran ?: $spj->dokumen_file;
                    $fileName = basename($filePath);
                    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $isImage = in_array($fileExt, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                @endphp
                <div style="background: var(--dash-card-bg); border: 1px solid var(--dash-border); border-radius: 12px; padding: 18px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); display: flex; flex-direction: column; gap: 16px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <div style="width: 46px; height: 46px; border-radius: 10px; background: {{ $isImage ? '#eff6ff' : '#fef2f2' }}; color: {{ $isImage ? '#2563eb' : '#dc2626' }}; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                                @if($isImage)
                                    <ion-icon name="image-outline"></ion-icon>
                                @elseif($fileExt === 'pdf')
                                    <ion-icon name="document-text-outline"></ion-icon>
                                @else
                                    <ion-icon name="document-attach-outline"></ion-icon>
                                @endif
                            </div>
                            <div>
                                <div style="font-weight: 700; font-size: 14px; color: var(--dash-text-dark); word-break: break-all;">
                                    {{ $fileName }}
                                </div>
                                <div style="font-size: 12px; color: var(--dash-text-muted); margin-top: 2px;">
                                    Format: <strong>{{ strtoupper($fileExt) }}</strong> &bull; Berkas digital pendukung pengajuan SPJ
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                            <a href="{{ $spj->file_lampiran_url }}" target="_blank" class="btn-dash-primary" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; padding: 8px 16px; font-size: 13px;">
                                <ion-icon name="eye-outline" style="font-size: 17px;"></ion-icon>
                                <span>Lihat File</span>
                            </a>
                            <a href="{{ $spj->file_lampiran_url }}" download class="btn-dash-primary" style="background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; padding: 8px 14px; font-size: 13px;">
                                <ion-icon name="download-outline" style="font-size: 17px;"></ion-icon>
                                <span>Unduh</span>
                            </a>
                            <a href="{{ route('teknis.dokumen.index') }}" class="btn-dash-primary" style="background: transparent; color: #0284c7; border: 1px solid #bae6fd; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; padding: 8px 12px; font-size: 12.5px;">
                                <ion-icon name="sync-outline" style="font-size: 15px;"></ion-icon>
                                <span>Ganti File</span>
                            </a>
                        </div>
                    </div>

                    @if($isImage)
                        <div style="border-top: 1px dashed var(--dash-border); padding-top: 14px;">
                            <div style="font-size: 12px; font-weight: 600; color: var(--dash-text-muted); margin-bottom: 8px;">Pratinjau Berkas:</div>
                            <div style="max-width: 340px; max-height: 240px; overflow: hidden; border-radius: 8px; border: 1px solid var(--dash-border); background: #f8fafc;">
                                <a href="{{ $spj->file_lampiran_url }}" target="_blank" title="Klik untuk memperbesar">
                                    <img src="{{ $spj->file_lampiran_url }}" alt="Preview Berkas" style="width: 100%; height: auto; display: block; object-fit: contain;">
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div style="background: var(--dash-input-bg); border: 1.5px dashed var(--dash-border); border-radius: 12px; padding: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <ion-icon name="cloud-upload-outline" style="font-size: 32px; color: var(--dash-text-muted);"></ion-icon>
                        <div>
                            <div style="font-size: 13.5px; font-weight: 600; color: var(--dash-text-dark);">Belum ada berkas scan/foto yang diunggah</div>
                            <div style="font-size: 12px; color: var(--dash-text-muted); margin-top: 2px;">Anda dapat melampirkan berkas scan kuitansi/dokumen digital untuk mempermudah verifikasi.</div>
                        </div>
                    </div>
                    <a href="{{ route('teknis.dokumen.index') }}" class="btn-dash-primary" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; padding: 8px 16px; font-size: 13px;">
                        <ion-icon name="cloud-upload-outline" style="font-size: 17px;"></ion-icon>
                        <span>Upload Berkas Sekarang</span>
                    </a>
                </div>
            @endif
        </div>

        {{-- ====================================================================
             2. CHECKLIST KELENGKAPAN DOKUMEN FISIK
             ==================================================================== --}}
        <div style="margin-top: 28px;">
            @php
                $dokumens = $spj->dokumen_list;
                if (empty($dokumens) && !empty($spj->dokumen_file) && str_starts_with(trim($spj->dokumen_file), '[')) {
                    $dokumens = json_decode($spj->dokumen_file, true) ?: [];
                }
                $totalDocs = count($dokumens);
                $readyDocs = count(array_filter($dokumens, fn($d) => !empty($d['disiapkan'])));
            @endphp
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px solid var(--dash-border); padding-bottom: 8px; flex-wrap: wrap; gap: 8px;">
                <h3 style="font-size: 15px; font-weight: 700; color: var(--dash-text-dark); margin: 0; display: flex; align-items: center; gap: 8px;">
                    <ion-icon name="checkbox-outline" style="font-size: 18px; color: var(--dash-mint-text);"></ion-icon>
                    Checklist Kelengkapan Dokumen Fisik (SOP)
                </h3>
                @if($totalDocs > 0)
                    <span style="font-size: 12px; font-weight: 600; color: var(--dash-text-muted); background: var(--dash-input-bg); padding: 4px 10px; border-radius: 999px; border: 1px solid var(--dash-border);">
                        <strong>{{ $readyDocs }}</strong> dari <strong>{{ $totalDocs }}</strong> berkas fisik disiapkan
                    </span>
                @endif
            </div>
            
            <div style="background: var(--dash-input-bg); border: 1px solid var(--dash-border); border-radius: 12px; padding: 20px;">
                @if($totalDocs > 0)
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 14px;">
                        @foreach($dokumens as $doc)
                            @php
                                $isDisiapkan = !empty($doc['disiapkan']);
                                $isBendahara = \App\Models\Spj::isBendaharaDoc($doc);
                            @endphp
                            <div style="display: flex; align-items: flex-start; gap: 12px; padding: 12px 14px; background: var(--dash-card-bg); border: 1px solid {{ $isDisiapkan ? 'var(--dash-mint-text)' : 'var(--dash-border)' }}; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                                <div style="margin-top: 2px;">
                                    @if($isDisiapkan)
                                        <ion-icon name="checkmark-circle" style="color: var(--dash-mint-text); font-size: 20px;"></ion-icon>
                                    @else
                                        <ion-icon name="close-circle" style="color: var(--dash-text-muted); font-size: 20px;"></ion-icon>
                                    @endif
                                </div>
                                <div style="flex: 1;">
                                    <span style="font-weight: 600; font-size: 13px; color: {{ $isDisiapkan ? 'var(--dash-mint-text)' : 'var(--dash-text-muted)' }}; line-height: 1.4; display: block;">
                                        {{ $doc['nama'] }}
                                    </span>
                                    <div style="font-size: 11px; color: var(--dash-text-muted); margin-top: 3px;">
                                        @if($isDisiapkan)
                                            Telah disiapkan (Fisik)
                                        @elseif($isBendahara)
                                            Kewenangan / Disiapkan Bendahara
                                        @else
                                            Tidak diwajibkan untuk Teknis
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 16px; color: var(--dash-text-muted);">
                        <ion-icon name="document-text-outline" style="font-size: 28px; opacity: 0.6; margin-bottom: 6px;"></ion-icon>
                        <p style="margin: 0; font-size: 13px;">Belum ada checklist berkas spesifik. Pastikan kelengkapan berkas fisik sesuai SOP pengajuan SPJ.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- MODAL WHATSAPP MANUAL TEKNIS -->
<div id="waModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 20px; box-sizing: border-box;">
    <div style="background: var(--dash-card-bg); border-radius: 16px; max-width: 600px; width: 100%; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); overflow: hidden; border: 1px solid var(--dash-border);">
        <div style="background: linear-gradient(135deg, #25d366, #128c7e); color: white; padding: 18px 24px; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    <ion-icon name="logo-whatsapp"></ion-icon>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: white;">Hubungi Bagian Umum via WhatsApp</h3>
                    <small style="opacity: 0.9; font-size: 12px;">Pesan otomatis terisi sesuai laporan SPJ ini</small>
                </div>
            </div>
            <button type="button" onclick="closeWaModal()" style="background: rgba(255,255,255,0.15); border: none; color: white; width: 32px; height: 32px; cursor: pointer; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                <ion-icon name="close"></ion-icon>
            </button>
        </div>

        <div style="padding: 24px;">
            @php
                $umumWa = \App\Models\User::where('role', 'umum')->whereNotNull('no_wa')->value('no_wa');
            @endphp
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: var(--dash-text-dark); margin-bottom: 6px;">
                    Nomor WhatsApp Tujuan (Petugas Bagian Umum):
                </label>
                <input type="text" id="waRecipient" value="{{ $umumWa ?? '' }}" placeholder="Contoh: 081234567890 (atau biarkan kosong untuk pilih kontak di WA)" style="width: 100%; box-sizing: border-box; padding: 10px 14px; border: 1px solid var(--dash-input-border); border-radius: 8px; font-size: 13px; outline: none; background: var(--dash-input-bg); color: var(--dash-text-dark); font-family: inherit;">
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: var(--dash-text-dark); margin-bottom: 8px;">Pilih Template Pesan:</label>
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <button type="button" id="btnTplKonfirm" onclick="setWaTemplate('konfirm')" style="padding: 6px 14px; font-size: 12px; font-weight: 600; border-radius: 20px; border: 1px solid #10b981; background: #ecfdf5; color: #047857; cursor: pointer;">
                        📤 Konfirmasi Pengajuan
                    </button>
                    <button type="button" id="btnTplTanya" onclick="setWaTemplate('tanya')" style="padding: 6px 14px; font-size: 12px; font-weight: 600; border-radius: 20px; border: 1px solid var(--dash-border); background: var(--dash-card-bg); color: var(--dash-text-muted); cursor: pointer;">
                        ❓ Tanya Kelengkapan
                    </button>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <label style="font-size: 13px; font-weight: 600; color: var(--dash-text-dark);">Isi Pesan (Bisa Diedit):</label>
                    <button type="button" onclick="copyWaText()" id="btnCopyWa" style="background: none; border: none; color: var(--dash-cyan-text); font-size: 12px; font-weight: 600; cursor: pointer;">
                        <span>Salin Teks</span>
                    </button>
                </div>
                <textarea id="waMessageText" rows="8" style="width: 100%; box-sizing: border-box; padding: 12px; border: 1px solid var(--dash-input-border); border-radius: 8px; font-size: 13px; line-height: 1.5; font-family: inherit; outline: none; background: var(--dash-input-bg); color: var(--dash-text-dark); resize: vertical;"></textarea>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="closeWaModal()" class="spj-btn spj-btn-secondary">
                    Tutup
                </button>
                <button type="button" onclick="sendWhatsAppNow()" style="padding: 10px 20px; border: none; background: #25d366; color: white; border-radius: 10px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(37, 211, 102, 0.25);">
                    <ion-icon name="logo-whatsapp" style="font-size: 18px;"></ion-icon>
                    Buka di WhatsApp
                </button>
            </div>
        </div>
    </div>
</div>

@php
    $spjJsData = [
        'pengaju' => $spj->user->name ?? 'Staf Teknis',
        'kegiatan' => $spj->kegiatan,
        'nilai' => 'Rp ' . number_format($spj->nilai, 0, ',', '.'),
        'status' => strtoupper(str_replace('_', ' ', $spj->status)),
        'linkDetail' => url('/umum/spj/' . $spj->id),
    ];
@endphp

@push('scripts')
<script>
    const spjData = @json($spjJsData);

    function openWaModal(defaultTpl = 'konfirm') {
        document.getElementById('waModal').style.display = 'flex';
        setWaTemplate(defaultTpl);
    }

    function closeWaModal() {
        document.getElementById('waModal').style.display = 'none';
    }

    window.addEventListener('click', function(e) {
        const modal = document.getElementById('waModal');
        if (e.target === modal) closeWaModal();
    });

    function setWaTemplate(tpl) {
        const btnKonfirm = document.getElementById('btnTplKonfirm');
        const btnTanya = document.getElementById('btnTplTanya');
        const txt = document.getElementById('waMessageText');

        if (tpl === 'konfirm') {
            btnKonfirm.style.background = '#ecfdf5';
            btnKonfirm.style.color = '#047857';
            btnKonfirm.style.borderColor = '#10b981';

            btnTanya.style.background = 'var(--dash-card-bg)';
            btnTanya.style.color = 'var(--dash-text-muted)';
            btnTanya.style.borderColor = 'var(--dash-border)';

            txt.value = `Halo Bagian Umum BPS,\n\nSaya ingin mengonfirmasi bahwa pengajuan SPJ berikut telah saya submit melalui Sistem SPJ:\n\n• Pengaju: ${spjData.pengaju}\n• Kegiatan: ${spjData.kegiatan}\n• Nilai: ${spjData.nilai}\n• Status Saat Ini: ${spjData.status}\n\nMohon bantuannya untuk dilakukan pemeriksaan dan verifikasi kelengkapan berkas fisik. Terima kasih banyak!\n\nLink Sistem:\n${spjData.linkDetail}`;
        } else {
            btnTanya.style.background = '#eff6ff';
            btnTanya.style.color = '#1d4ed8';
            btnTanya.style.borderColor = '#3b82f6';

            btnKonfirm.style.background = 'var(--dash-card-bg)';
            btnKonfirm.style.color = 'var(--dash-text-muted)';
            btnKonfirm.style.borderColor = 'var(--dash-border)';

            txt.value = `Halo Petugas Verifikator Bagian Umum,\n\nMohon izin bertanya terkait kelengkapan berkas untuk SPJ kegiatan:\n"${spjData.kegiatan}" (${spjData.nilai}).\n\nApakah ada dokumen pendukung tambahan yang masih kurang atau perlu saya revisi agar proses verifikasi bisa segera berlanjut? Terima kasih atas arahannya!`;
        }
    }

    function sendWhatsAppNow() {
        let phone = document.getElementById('waRecipient').value.trim();
        const text = encodeURIComponent(document.getElementById('waMessageText').value);

        phone = phone.replace(/[^0-9]/g, '');
        if (phone.startsWith('0')) {
            phone = '62' + phone.substring(1);
        }

        let url = phone ? `https://api.whatsapp.com/send?phone=${phone}&text=${text}` : `https://api.whatsapp.com/send?text=${text}`;
        window.open(url, '_blank');
    }

    function copyWaText() {
        const text = document.getElementById('waMessageText').value;
        navigator.clipboard.writeText(text).then(() => {
            const btn = document.getElementById('btnCopyWa');
            btn.innerHTML = '<span style="color: #10b981;">✓ Tersalin!</span>';
            setTimeout(() => {
                btn.innerHTML = '<span>Salin Teks</span>';
            }, 2000);
        });
    }
</script>
@endpush

@endsection
