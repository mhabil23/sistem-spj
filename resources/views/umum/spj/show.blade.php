@extends('layouts.umum')

@php
$title = 'Pemeriksaan SPJ';
$subtitle = 'Review dokumen pengajuan SPJ.';

$waktuMulai = $spj->pemeriksaan_dimulai_at ?? ($spj->berkas_fisik_diterima_at ?? ($spj->diajukan_at ?? $spj->created_at));
$endTime = in_array($spj->status, ['diajukan', 'disetujui_umum', 'disetujui_ppk']) ? now() : $spj->updated_at;
$diff = $waktuMulai ? \Carbon\Carbon::parse($waktuMulai)->diff($endTime) : null;
$timeString = '';
if($diff) {
    if($diff->d > 0) $timeString .= $diff->d . ' Hari ';
    if($diff->h > 0) $timeString .= $diff->h . ' Jam ';
    $timeString .= $diff->i . ' Menit';
}

// Decode checklist dokumen & file lampiran digital
$dokumens = $spj->dokumen_list;
if (empty($dokumens) && !empty($spj->dokumen_file) && str_starts_with(trim($spj->dokumen_file), '[')) {
    $dokumens = json_decode($spj->dokumen_file, true) ?: [];
}
$attachmentFilePath = $spj->file_lampiran ?: (!str_starts_with((string)$spj->dokumen_file, '[') ? $spj->dokumen_file : null);
$isAttachmentFile = !empty($attachmentFilePath);

// Hitung persentase dokumen yang telah disiapkan staf teknis
$totalDocs = count($dokumens);
$readyDocs = 0;
if ($totalDocs > 0) {
    foreach($dokumens as $d) {
        if (!empty($d['disiapkan'])) {
            $readyDocs++;
        }
    }
    $docPercentage = round(($readyDocs / $totalDocs) * 100);
} else {
    $docPercentage = 0;
}

// Tentukan status tahapan untuk Stepper
$stepStages = [
    'diajukan' => 1,
    'disetujui_umum' => 2,
    'disetujui_ppk' => 3,
    'disetujui_ppspm' => 4,
    'selesai' => 5,
];

$isRevision = str_contains($spj->status, 'revisi');
$currentStep = $stepStages[$spj->status] ?? 1;
if ($isRevision) {
    if ($spj->status === 'revisi_umum') $currentStep = 2;
    elseif ($spj->status === 'revisi_ppk') $currentStep = 3;
    elseif ($spj->status === 'revisi_ppspm') $currentStep = 4;
    else $currentStep = 1;
}
@endphp

@push('styles')
    @vite(['resources/css/umum/spj.css'])
@endpush

@section('content')
<div class="verify-container">

    <!-- ====================================================================
         1. PAGE HEADER CARD
         ==================================================================== -->
    <div class="verify-header-card">
        <div class="verify-header-left">
            <div class="verify-breadcrumbs">
                <a href="{{ route('umum.dashboard') }}">Dashboard</a>
                <ion-icon name="chevron-forward-outline" style="font-size: 10px;"></ion-icon>
                <a href="{{ route('umum.spj.index') }}">Kotak Masuk SPJ</a>
                <ion-icon name="chevron-forward-outline" style="font-size: 10px;"></ion-icon>
                <span>Pemeriksaan SPJ #{{ $spj->id }}</span>
            </div>

            <div class="verify-title-row">
                <h1 class="verify-page-title">Pemeriksaan SPJ: {{ $spj->kegiatan }}</h1>

                @php
                    $statusBadgeClass = 'dash-badge-diajukan';
                    $statusText = 'Menunggu';

                    if ($spj->status == 'diajukan') {
                        if (!$spj->berkas_fisik_diterima_at) {
                            $statusBadgeClass = 'dash-badge-revisi';
                            $statusText = '1. Menunggu Penyerahan Berkas Fisik';
                        } elseif (!$spj->pemeriksaan_dimulai_at) {
                            $statusBadgeClass = 'dash-badge-disetujui_ppk';
                            $statusText = '2. Fisik Diterima (Siap Cek)';
                        } else {
                            $statusBadgeClass = 'dash-badge-diajukan';
                            $statusText = '3. Sedang Diperiksa Umum';
                        }
                    } elseif ($spj->status == 'disetujui_umum') {
                        $statusBadgeClass = 'dash-badge-disetujui_umum';
                        $statusText = 'Diteruskan ke PPK (Menunggu TTD)';
                    } elseif ($spj->status == 'disetujui_ppk') {
                        $statusBadgeClass = 'dash-badge-disetujui_ppk';
                        $statusText = 'Diteruskan ke PPSPM';
                    } elseif ($spj->status == 'disetujui_ppspm') {
                        $statusBadgeClass = 'dash-badge-disetujui_ppspm';
                        $statusText = 'Siap Diproses Bendahara (SPM Terbit)';
                    } elseif ($isRevision) {
                        $statusBadgeClass = 'dash-badge-revisi';
                        $statusText = 'Perlu Revisi (' . strtoupper(str_replace('revisi_', '', $spj->status)) . ')';
                    } elseif ($spj->status == 'selesai') {
                        $statusBadgeClass = 'dash-badge-selesai';
                        $statusText = 'Selesai (Cair)';
                    }
                @endphp
                <span class="dash-badge {{ $statusBadgeClass }}">
                    <span class="badge-dot"></span>
                    {{ $statusText }}
                </span>
            </div>

            <p class="verify-header-meta">
                <span>Diajukan pada: <strong>{{ $spj->created_at->format('d M Y, H:i') }}</strong></span>
                <span>&bull;</span>
                <span>Pengaju: <strong>{{ $spj->user->name ?? 'Staf Teknis' }}</strong> ({{ $spj->user->seksi ?? 'Seksi Teknis' }})</span>
                @if($spj->nomor_spj)
                    <span>&bull;</span>
                    <span>No. SPJ: <strong>{{ $spj->nomor_spj }}</strong></span>
                @endif
            </p>
        </div>

        <div class="verify-header-actions">
            <button type="button" onclick="openWaModal()" class="btn-wa-header" title="Kirim Pesan WhatsApp ke Pengaju">
                <ion-icon name="logo-whatsapp" style="font-size: 18px;"></ion-icon>
                <span>WhatsApp Pengaju</span>
            </button>

            <a href="{{ route('umum.spj.print', $spj->id) }}" target="_blank" class="btn-print-header" title="Cetak Lembar Kendali / Checklist SOP">
                <ion-icon name="print-outline" style="font-size: 18px;"></ion-icon>
                <span>Cetak Lembar Checklist</span>
            </a>

            <a href="{{ route('umum.spj.index') }}" class="btn-back-header" title="Kembali ke Kotak Masuk SPJ">
                <ion-icon name="arrow-back-outline"></ion-icon>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- ====================================================================
         2. WA TRIGGER BANNER (Jika Baru Saja Melakukan Verifikasi)
         ==================================================================== -->
    @if(session('trigger_wa'))
    <div class="wa-trigger-banner">
        <div class="wa-trigger-content">
            <div class="wa-trigger-icon">
                <ion-icon name="logo-whatsapp"></ion-icon>
            </div>
            <div class="wa-trigger-texts">
                <h4>Status SPJ Berhasil Diperbarui! Kirim Notifikasi WhatsApp ke Pengaju</h4>
                <p>Kirimkan template pesan resmi agar staf pengaju langsung mengetahui instruksi lanjutan dokumen fisiknya.</p>
            </div>
        </div>
        <button type="button" onclick="openWaModal('{{ session('trigger_wa') }}')" class="btn-decision-wa-quick" style="padding: 10px 20px; font-size: 13.5px;">
            <ion-icon name="logo-whatsapp" style="font-size: 18px;"></ion-icon>
            <span>Kirim Pesan WhatsApp Sekarang</span>
        </button>
    </div>
    @endif

    <!-- ====================================================================
         3. WORKFLOW STEPPER ALUR BIROKRASI SPJ (5 TAHAPAN)
         ==================================================================== -->
    <div class="verify-stepper-card">
        <div class="stepper-header-title">
            <span>Alur Penyelesaian Tagihan (SOP 5 Pintu BPS)</span>
            <span>
                @if($isRevision)
                    <strong style="color: #ef4444;">Status: Dikembalikan ke Teknis</strong>
                @elseif($spj->status === 'selesai')
                    <strong style="color: #10b981;">Status: Selesai Dicairkan</strong>
                @else
                    Tahap Aktif: <strong>Tahap {{ $currentStep }} dari 5</strong>
                @endif
            </span>
        </div>

        <div class="stepper-track">
            <!-- TAHAP 1: TEKNIS -->
            <div class="stepper-item {{ $currentStep > 1 || $spj->status === 'selesai' ? 'completed' : ($currentStep === 1 && !$isRevision ? 'active' : '') }}">
                <div class="stepper-connector"></div>
                <div class="stepper-node">
                    @if($currentStep > 1 || $spj->status === 'selesai')
                        <ion-icon name="checkmark"></ion-icon>
                    @else
                        1
                    @endif
                </div>
                <div class="stepper-content">
                    <span class="stepper-step-title">Pengajuan Teknis</span>
                    <span class="stepper-step-sub">{{ $spj->user->name ?? 'Staf Teknis' }}</span>
                    @if($spj->diajukan_at || $spj->created_at)
                        <span class="stepper-step-time">{{ ($spj->diajukan_at ? \Carbon\Carbon::parse($spj->diajukan_at) : $spj->created_at)->format('d/m/Y H:i') }}</span>
                    @endif
                </div>
            </div>

            <!-- TAHAP 2: SUBBAG UMUM -->
            @php
                $step2Class = '';
                if ($spj->disetujui_umum_at || $currentStep > 2 || $spj->status === 'selesai') {
                    $step2Class = 'completed';
                } elseif ($currentStep === 2 && $isRevision) {
                    $step2Class = 'revision';
                } elseif ($currentStep === 2) {
                    $step2Class = 'active';
                }
            @endphp
            <div class="stepper-item {{ $step2Class }}">
                <div class="stepper-connector"></div>
                <div class="stepper-node">
                    @if($step2Class === 'completed')
                        <ion-icon name="checkmark"></ion-icon>
                    @elseif($step2Class === 'revision')
                        <ion-icon name="alert"></ion-icon>
                    @else
                        2
                    @endif
                </div>
                <div class="stepper-content">
                    <span class="stepper-step-title">Verifikasi Umum</span>
                    <span class="stepper-step-sub">Subbagian Umum</span>
                    @if($spj->disetujui_umum_at)
                        <span class="stepper-step-time">{{ \Carbon\Carbon::parse($spj->disetujui_umum_at)->format('d/m/Y H:i') }}</span>
                    @elseif($spj->status === 'diajukan')
                        @if(!$spj->berkas_fisik_diterima_at)
                            <span class="stepper-step-time" style="background: rgba(245, 158, 11, 0.2); color: #d97706;">Menunggu Fisik</span>
                        @elseif(!$spj->pemeriksaan_dimulai_at)
                            <span class="stepper-step-time" style="background: rgba(2, 132, 199, 0.2); color: #0284c7;">Fisik Diterima</span>
                        @else
                            <span class="stepper-step-time" style="background: rgba(16, 185, 129, 0.2); color: #059669;">Sedang Dicek</span>
                        @endif
                    @endif
                </div>
            </div>

            <!-- TAHAP 3: PPK -->
            @php
                $step3Class = '';
                if ($spj->disetujui_ppk_at || $currentStep > 3 || $spj->status === 'selesai') $step3Class = 'completed';
                elseif ($currentStep === 3 && $isRevision) $step3Class = 'revision';
                elseif ($currentStep === 3) $step3Class = 'active';
            @endphp
            <div class="stepper-item {{ $step3Class }}">
                <div class="stepper-connector"></div>
                <div class="stepper-node">
                    @if($step3Class === 'completed')
                        <ion-icon name="checkmark"></ion-icon>
                    @elseif($step3Class === 'revision')
                        <ion-icon name="alert"></ion-icon>
                    @else
                        3
                    @endif
                </div>
                <div class="stepper-content">
                    <span class="stepper-step-title">Tanda Tangan PPK</span>
                    <span class="stepper-step-sub">Pejabat Pembuat Komitmen</span>
                    @if($spj->disetujui_ppk_at)
                        <span class="stepper-step-time">{{ \Carbon\Carbon::parse($spj->disetujui_ppk_at)->format('d/m/Y H:i') }}</span>
                    @elseif($currentStep === 3 && !$isRevision)
                        <span class="stepper-step-time" style="background: rgba(2, 132, 199, 0.2); color: #0284c7;">Menunggu TTD</span>
                    @endif
                </div>
            </div>

            <!-- TAHAP 4: PPSPM -->
            @php
                $step4Class = '';
                if ($spj->disetujui_ppspm_at || $currentStep > 4 || $spj->status === 'selesai') $step4Class = 'completed';
                elseif ($currentStep === 4 && $isRevision) $step4Class = 'revision';
                elseif ($currentStep === 4) $step4Class = 'active';
            @endphp
            <div class="stepper-item {{ $step4Class }}">
                <div class="stepper-connector"></div>
                <div class="stepper-node">
                    @if($step4Class === 'completed')
                        <ion-icon name="checkmark"></ion-icon>
                    @elseif($step4Class === 'revision')
                        <ion-icon name="alert"></ion-icon>
                    @else
                        4
                    @endif
                </div>
                <div class="stepper-content">
                    <span class="stepper-step-title">Persetujuan PPSPM</span>
                    <span class="stepper-step-sub">Penerbitan SPM</span>
                    @if($spj->disetujui_ppspm_at)
                        <span class="stepper-step-time">{{ \Carbon\Carbon::parse($spj->disetujui_ppspm_at)->format('d/m/Y H:i') }}</span>
                    @elseif($currentStep === 4 && !$isRevision)
                        <span class="stepper-step-time" style="background: rgba(2, 132, 199, 0.2); color: #0284c7;">Pemeriksaan SPM</span>
                    @endif
                </div>
            </div>

            <!-- TAHAP 5: BENDAHARA -->
            <div class="stepper-item {{ $spj->status === 'selesai' ? 'completed' : ($currentStep === 5 ? 'active' : '') }}">
                <div class="stepper-connector"></div>
                <div class="stepper-node">
                    @if($spj->status === 'selesai')
                        <ion-icon name="checkmark"></ion-icon>
                    @else
                        5
                    @endif
                </div>
                <div class="stepper-content">
                    <span class="stepper-step-title">Pencairan Dana</span>
                    <span class="stepper-step-sub">Bendahara Pengeluaran</span>
                    @if($spj->diselesaikan_at)
                        <span class="stepper-step-time" style="background: rgba(16, 185, 129, 0.2); color: #059669;">{{ \Carbon\Carbon::parse($spj->diselesaikan_at)->format('d/m/Y') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ====================================================================
         4. SLA & DURASI PROSES VERIFIKASI
         ==================================================================== -->
    @if($waktuMulai)
    <div class="verify-sla-banner {{ $spj->status === 'selesai' ? 'sla-banner-done' : 'sla-banner-normal' }}">
        <div class="sla-info-group">
            <div class="sla-icon-box">
                <ion-icon name="timer-outline"></ion-icon>
            </div>
            <div class="sla-texts">
                <h4>Target Waktu & Standar Layanan (SLA SOP BPS)</h4>
                <p>
                    @if(!$spj->berkas_fisik_diterima_at)
                        SPJ diajukan secara sistem pada <strong>{{ \Carbon\Carbon::parse($spj->created_at)->format('d F Y, H:i') }} WITA</strong>. <em>Menunggu penyerahan fisik ke meja Umum.</em>
                    @else
                        Berkas fisik disetor pada <strong>{{ \Carbon\Carbon::parse($spj->berkas_fisik_diterima_at)->format('d F Y, H:i') }} WITA</strong>. Batas verifikasi: <strong>Maksimal 2 Hari Kerja</strong>.
                    @endif
                </p>
            </div>
        </div>
        <div class="sla-timer-box">
            <div class="sla-timer-val">
                {{ in_array($spj->status, ['diajukan', 'disetujui_umum', 'disetujui_ppk']) ? 'Waktu Berjalan: ' : 'Total Durasi: ' }}
                {{ $timeString ?: 'Kurang dari 1 menit' }}
            </div>
            <div class="sla-timer-label">
                @if(!$spj->berkas_fisik_diterima_at)
                    <span style="color: #d97706;">⏳ Menunggu berkas fisik disetor</span>
                @elseif($diff && $diff->d >= 2)
                    <span style="color: #ea580c;">⚠️ Melewati target 2 hari kerja</span>
                @else
                    <span style="color: #10b981;">✔ Masih dalam batas waktu normal</span>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- ====================================================================
         5. DETAIL INFORMASI KEGIATAN & ANGGARAN
         ==================================================================== -->
    <div class="verify-content-grid">
        <div class="verify-card">
            <div class="verify-card-header">
                <h3 class="verify-card-title">
                    <ion-icon name="reader-outline" style="color: #0284c7; font-size: 20px;"></ion-icon>
                    Detail Kegiatan & Rincian Anggaran
                </h3>
                @if($spj->catatan_internal)
                    <span style="font-size: 11.5px; font-weight: 700; background: #ecfdf5; color: #047857; padding: 4px 10px; border-radius: 999px; border: 1px solid #a7f3d0;">
                        Ada Catatan Internal PPK
                    </span>
                @endif
            </div>
            <div class="verify-card-body">
                <div class="detail-info-grid">
                    <!-- Nilai Pengajuan -->
                    <div class="detail-info-item">
                        <span class="detail-info-label">Total Nilai Pengajuan</span>
                        <div class="detail-info-value amount">
                            Rp {{ number_format($spj->nilai, 0, ',', '.') }}
                        </div>
                    </div>

                    <!-- Tanggal Kegiatan -->
                    <div class="detail-info-item">
                        <span class="detail-info-label">Tanggal Pelaksanaan Kegiatan</span>
                        <div class="detail-info-value">
                            {{ $spj->tanggal ? $spj->tanggal->format('d F Y') : '-' }}
                        </div>
                    </div>

                    <!-- PIC / Kontak WhatsApp -->
                    <div class="detail-info-item">
                        <span class="detail-info-label">Staf PIC & Kontak WhatsApp</span>
                        <div class="detail-info-value">
                            @if($spj->no_wa || ($spj->user && $spj->user->no_wa))
                                @php
                                    $waNumber = $spj->no_wa ?: $spj->user->no_wa;
                                    $cleanPhone = preg_replace('/[^0-9]/', '', $waNumber);
                                    if (str_starts_with($cleanPhone, '0')) $cleanPhone = '62' . substr($cleanPhone, 1);
                                    elseif (str_starts_with($cleanPhone, '8')) $cleanPhone = '62' . $cleanPhone;
                                @endphp
                                <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="pic-badge-wa" title="Buka WhatsApp">
                                    <ion-icon name="logo-whatsapp" style="font-size: 18px; color: #25d366;"></ion-icon>
                                    <span>{{ $waNumber }} ({{ $spj->user->name ?? 'Pengaju' }})</span>
                                </a>
                            @else
                                <span style="color: var(--dash-text-muted); font-weight: 500; font-size: 13.5px;">- Tidak dicantumkan -</span>
                            @endif
                        </div>
                    </div>

                    <!-- Seksi / Fungsi -->
                    <div class="detail-info-item">
                        <span class="detail-info-label">Fungsi / Seksi Teknis</span>
                        <div class="detail-info-value">
                            {{ $spj->user->seksi ?? 'Seksi / Tim Teknis BPS' }}
                        </div>
                    </div>
                </div>

                <!-- Uraian Nama Kegiatan -->
                <div style="margin-top: 22px; padding-top: 20px; border-top: 1px solid var(--dash-border);">
                    <span class="detail-info-label">Nama Kegiatan</span>
                    <div style="font-size: 16px; font-weight: 700; color: var(--dash-text-dark); line-height: 1.5; margin-top: 4px;">
                        {{ $spj->kegiatan }}
                    </div>
                </div>

                <!-- Keterangan Teknis -->
                @if($spj->keterangan)
                <div style="margin-top: 16px; padding: 14px 18px; background: var(--dash-input-bg); border-radius: 12px; border: 1px solid var(--dash-border);">
                    <span class="detail-info-label">Keterangan / Catatan dari Pengaju:</span>
                    <p style="margin: 4px 0 0 0; font-size: 13.5px; color: var(--dash-text-dark); line-height: 1.5; white-space: pre-line;">
                        {{ $spj->keterangan }}
                    </p>
                </div>
                @endif

                <!-- Catatan Internal untuk PPK jika sudah ada -->
                @if($spj->catatan_internal)
                <div style="margin-top: 16px; padding: 14px 18px; background: rgba(16, 185, 129, 0.08); border-radius: 12px; border: 1px solid rgba(16, 185, 129, 0.35);">
                    <span class="detail-info-label" style="color: #047857;">Catatan Internal untuk PPK:</span>
                    <p style="margin: 4px 0 0 0; font-size: 13.5px; color: #065f46; line-height: 1.5; white-space: pre-line;">
                        {{ $spj->catatan_internal }}
                    </p>
                </div>
                @endif
            </div>
        </div>

        <!-- ====================================================================
             6. CHECKLIST KELENGKAPAN DOKUMEN FISIK / SOP
             ==================================================================== -->
        <div class="verify-card">
            <div class="verify-card-header">
                <h3 class="verify-card-title">
                    <ion-icon name="checkbox-outline" style="color: #10b981; font-size: 20px;"></ion-icon>
                    Checklist Kelengkapan Dokumen Fisik (SOP)
                </h3>
                <span style="font-size: 12px; font-weight: 700; color: var(--dash-text-muted);">
                    Standar Operasional Prosedur BPS Kolaka Utara
                </span>
            </div>
            <div class="verify-card-body">

                @if($isAttachmentFile)
                    <!-- Berkas Digital Terlampir -->
                    <div style="background: var(--dash-table-header-bg); border: 1px solid var(--dash-border); border-radius: 12px; padding: 18px 24px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(2, 132, 199, 0.12); color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                                <ion-icon name="document-attach-outline"></ion-icon>
                            </div>
                            <div>
                                <h4 style="margin: 0 0 2px 0; font-size: 14.5px; font-weight: 700; color: var(--dash-text-dark);">Berkas Digital Terlampir (Scan / Foto)</h4>
                                <span style="font-size: 12.5px; color: var(--dash-text-muted);">{{ basename($attachmentFilePath) }} &bull; Bukti berkas digital yang diunggah staf pengaju</span>
                            </div>
                        </div>
                        <a href="{{ Storage::url($attachmentFilePath) }}" target="_blank" class="btn-dash-primary" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; padding: 8px 18px; font-size: 13px;">
                            <ion-icon name="eye-outline" style="font-size: 17px;"></ion-icon> Buka &amp; Periksa File Terlampir
                        </a>
                    </div>
                @endif

                @if($totalDocs > 0)
                    <!-- Tracker Progress Bar -->
                    <div class="doc-tracker-bar">
                        <div class="doc-tracker-info">
                            <ion-icon name="documents-outline" style="font-size: 28px; color: #0284c7;"></ion-icon>
                            <div>
                                <div class="doc-tracker-title">{{ $readyDocs }} dari {{ $totalDocs }} Berkas Telah Disiapkan</div>
                                <div class="doc-tracker-sub">Staf teknis menyatakan dokumen telah dicetak dan siap diverifikasi di meja Umum.</div>
                            </div>
                        </div>
                        <div class="doc-progress-wrap">
                            <div class="doc-progress-bar-bg">
                                <div class="doc-progress-fill" style="width: {{ $docPercentage }}%;"></div>
                            </div>
                            <div class="doc-progress-text">{{ $docPercentage }}% Terpenuhi</div>
                        </div>
                    </div>

                    <!-- Dokumen Cards Grid -->
                    <div class="doc-cards-grid">
                        @foreach($dokumens as $doc)
                            @php
                                $isReady = !empty($doc['disiapkan']);
                            @endphp
                            <div class="doc-item-card {{ $isReady ? 'is-ready' : '' }}">
                                <div class="doc-item-icon {{ $isReady ? 'ready' : 'pending' }}">
                                    @if($isReady)
                                        <ion-icon name="checkmark-circle"></ion-icon>
                                    @else
                                        <ion-icon name="remove-circle-outline"></ion-icon>
                                    @endif
                                </div>
                                <div style="flex: 1;">
                                    <div class="doc-item-name">{{ $doc['nama'] ?? 'Dokumen Pendukung' }}</div>
                                    <div class="doc-item-status-tag">
                                        @if($isReady)
                                            <span>✔ Telah Disiapkan (Fisik)</span>
                                        @else
                                            <span>Tidak diwajibkan untuk Teknis / Disiapkan Bendahara/PPK</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Default Checklist Panduan jika belum ada checklist spesifik -->
                    <div style="background: var(--dash-table-header-bg); border: 1.5px dashed var(--dash-border); border-radius: 12px; padding: 24px;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                            <ion-icon name="information-circle-outline" style="font-size: 22px; color: #0284c7;"></ion-icon>
                            <h4 style="margin: 0; font-size: 14.5px; font-weight: 700; color: var(--dash-text-dark);">Daftar Kelengkapan Standar SPJ</h4>
                        </div>
                        <p style="margin: 0 0 16px 0; font-size: 13px; color: var(--dash-text-muted); line-height: 1.5;">
                            Belum ada checklist berkas kustom yang dikirimkan. Harap periksa kelengkapan berkas fisik berikut sesuai SOP:
                        </p>
                        <div class="doc-cards-grid">
                            <div class="doc-item-card"><div class="doc-item-icon pending"><ion-icon name="document-text-outline"></ion-icon></div><div class="doc-item-name">Kuitansi Pembayaran Bermaterai (jika > Rp5.000.000)</div></div>
                            <div class="doc-item-card"><div class="doc-item-icon pending"><ion-icon name="document-text-outline"></ion-icon></div><div class="doc-item-name">Nota / Faktur / Invoice Asli Toko</div></div>
                            <div class="doc-item-card"><div class="doc-item-icon pending"><ion-icon name="document-text-outline"></ion-icon></div><div class="doc-item-name">Berita Acara Serah Terima (BAST) Pekerjaan</div></div>
                            <div class="doc-item-card"><div class="doc-item-icon pending"><ion-icon name="document-text-outline"></ion-icon></div><div class="doc-item-name">Surat Tugas & Daftar Hadir Peserta/Petugas</div></div>
                            <div class="doc-item-card"><div class="doc-item-icon pending"><ion-icon name="document-text-outline"></ion-icon></div><div class="doc-item-name">Laporan Hasil Kegiatan disertai Foto Dokumentasi</div></div>
                            <div class="doc-item-card"><div class="doc-item-icon pending"><ion-icon name="document-text-outline"></ion-icon></div><div class="doc-item-name">Kesesuaian Nominal Kuitansi dengan Pagu Anggaran</div></div>
                        </div>
                    </div>
                @endif

            </div>
        </div>

        <!-- ====================================================================
             7. ALUR BERTINGKAT: PENERIMAAN FISIK -> TERIMA SPJ & CEK -> KEPUTUSAN VERIFIKASI
             ==================================================================== -->
        
        <!-- ==================== TAHAP 1: STATUS DIAJUKAN (MEJA UMUM) ==================== -->
        @if($spj->status === 'diajukan')
        
            <!-- SUB-KONDISI A: BERKAS FISIK BELUM DISETOR / DITERIMA -->
            @if(!$spj->berkas_fisik_diterima_at)
            <div class="verify-stage-flow-card verify-stage-waiting-physical">
                <div class="stage-flow-top">
                    <div class="stage-flow-icon">
                        <ion-icon name="file-tray-outline"></ion-icon>
                    </div>
                    <div>
                        <span class="stage-flow-badge-step">Tahap 1: Penyerahan Berkas Fisik SPJ</span>
                        <h3 class="stage-flow-title">Menunggu Berkas Fisik Disetor ke Meja Subbagian Umum</h3>
                    </div>
                </div>

                <p class="stage-flow-desc">
                    Staf pengaju <strong>{{ $spj->user->name ?? 'Staf Teknis' }}</strong> telah mengajukan SPJ ini secara sistem pada 
                    <strong>{{ $spj->created_at->format('d F Y, H:i') }} WITA</strong>. 
                    Namun berkas dokumen fisik (kuitansi asli, nota/faktur toko, BAST, dll.) <strong>belum disetor</strong> ke meja Subbagian Umum. 
                    SPJ ini <strong>belum masuk antrean pemeriksaan aktif</strong> sampai Anda mengonfirmasi penerimaan berkas fisiknya.
                </p>

                <div class="stage-flow-actions">
                    <form action="{{ route('umum.spj.verify', $spj->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="action" value="terima_fisik">
                        <button type="submit" class="btn-decision-submit approve" style="font-size: 14px; padding: 14px 22px;">
                            <ion-icon name="checkbox-outline" style="font-size: 20px;"></ion-icon>
                            <span>Konfirmasi Berkas Fisik Telah Disetor & Diterima</span>
                        </button>
                    </form>

                    <button type="button" onclick="openWaModal('fisik')" class="btn-wa-header" style="height: 46px;">
                        <ion-icon name="logo-whatsapp" style="font-size: 18px;"></ion-icon>
                        <span>Ingatkan Pengaju via WA (Bawa Berkas Fisik)</span>
                    </button>
                </div>

                <div class="stage-notice-pill">
                    <ion-icon name="information-circle-outline" style="font-size: 16px; color: #d97706;"></ion-icon>
                    <span>Tombol penerimaan SPJ dan formulir keputusan (Teruskan ke PPK / Revisi) baru akan terbuka setelah fisik berkas diterima.</span>
                </div>
            </div>

            <!-- SUB-KONDISI B: BERKAS FISIK SUDAH DITERIMA, TAPI BELUM MASUK CEK -->
            @elseif(!$spj->pemeriksaan_dimulai_at)
            <div class="verify-stage-flow-card verify-stage-physical-received">
                <div class="stage-flow-top">
                    <div class="stage-flow-icon">
                        <ion-icon name="file-tray-full-outline"></ion-icon>
                    </div>
                    <div>
                        <span class="stage-flow-badge-step">Tahap 2: Berkas Fisik Diterima</span>
                        <h3 class="stage-flow-title">Berkas Fisik Telah Diterima — Siap Masuk Antrean Cek</h3>
                    </div>
                </div>

                <p class="stage-flow-desc">
                    Berkas fisik dokumen SPJ telah dikonfirmasi diterima di meja Subbagian Umum pada 
                    <strong>{{ $spj->berkas_fisik_diterima_at->format('d F Y, H:i') }} WITA</strong>. 
                    Silakan klik tombol di bawah untuk menerima SPJ secara resmi dan memasukkannya ke dalam antrean pemeriksaan aktif meja Anda.
                </p>

                <div class="stage-flow-actions">
                    <form action="{{ route('umum.spj.verify', $spj->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="action" value="mulai_periksa">
                        <button type="submit" class="btn-decision-submit approve" style="font-size: 15px; padding: 15px 26px; background: #0284c7; box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35);">
                            <ion-icon name="search-outline" style="font-size: 20px;"></ion-icon>
                            <span>Terima SPJ & Masuk untuk Dicek</span>
                        </button>
                    </form>

                    <form action="{{ route('umum.spj.verify', $spj->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan status penerimaan berkas fisik ini?')">
                        @csrf
                        <input type="hidden" name="action" value="batal_fisik">
                        <button type="submit" class="btn-back-header" style="color: #ef4444; border-color: rgba(239, 68, 68, 0.3); background: rgba(239, 68, 68, 0.05); font-size: 12px; padding: 10px 14px;" title="Batalkan status penerimaan fisik jika salah klik">
                            <ion-icon name="close-circle-outline"></ion-icon>
                            <span>Batal Terima Fisik</span>
                        </button>
                    </form>
                </div>

                <div class="stage-notice-pill">
                    <ion-icon name="information-circle-outline" style="font-size: 16px; color: #0284c7;"></ion-icon>
                    <span>Setelah menekan tombol "Terima SPJ & Masuk untuk Dicek", formulir keputusan verifikasi (Teruskan ke PPK atau Revisi) akan aktif.</span>
                </div>
            </div>

            <!-- SUB-KONDISI C: BERKAS FISIK DITERIMA & PEMERIKSAAN AKTIF DIMULAI -->
            @else
            <div class="verify-stage-flow-card verify-stage-inspecting-active" style="margin-bottom: 22px;">
                <div class="stage-flow-top" style="margin-bottom: 8px;">
                    <div class="stage-flow-icon">
                        <ion-icon name="clipboard-outline"></ion-icon>
                    </div>
                    <div>
                        <span class="stage-flow-badge-step">Tahap 3: Pengecekan Dokumen Aktif</span>
                        <h3 class="stage-flow-title">SPJ Sedang Dalam Pemeriksaan Meja Umum</h3>
                    </div>
                </div>
                <p class="stage-flow-desc" style="margin-bottom: 0;">
                    Pengecekan berkas aktif dimulai sejak <strong>{{ $spj->pemeriksaan_dimulai_at->format('d F Y, H:i') }} WITA</strong>. 
                    Silakan telaah kelengkapan bukti fisik di atas, lalu tentukan keputusan verifikasi di bawah ini:
                </p>
            </div>

            <div class="verify-card">
                <div class="verify-card-header">
                    <h3 class="verify-card-title">
                        <ion-icon name="shield-checkmark-outline" style="color: #0284c7; font-size: 20px;"></ion-icon>
                        Keputusan Verifikasi Subbagian Umum
                    </h3>
                    <span style="font-size: 12px; font-weight: 700; color: #0284c7; background: var(--dash-cyan-badge); padding: 4px 10px; border-radius: 999px;">
                        Pilih Tindak Lanjut Berkas
                    </span>
                </div>
                <div class="verify-card-body">
                    <form action="{{ route('umum.spj.verify', $spj->id) }}" method="POST" id="formVerifikasiUmum">
                        @csrf

                        <div class="decision-grid-two">
                            
                            <!-- OPSI 1: SETUJUI & TERUSKAN KE PPK -->
                            <div class="decision-box decision-box-approve">
                                <h4 class="decision-box-title">
                                    <ion-icon name="checkmark-circle" style="color: #10b981; font-size: 22px;"></ion-icon>
                                    <span>Setujui Berkas SPJ</span>
                                </h4>
                                <p class="decision-box-desc">
                                    Berkas fisik dan rincian administrasi telah lengkap, benar, dan sesuai SOP. Berkas akan diteruskan ke meja PPK untuk ditandatangani.
                                </p>

                                <label class="decision-label" for="catatan_internal">
                                    Catatan Internal untuk PPK (Opsional)
                                </label>
                                <textarea name="catatan_internal" id="catatan_internal" class="decision-textarea" rows="3" placeholder="Contoh: Kuitansi asli sudah dicocokkan dengan nota dan dokumen BAST fisik lengkap..."></textarea>

                                <div class="decision-actions-row">
                                    <button type="submit" name="action" value="setujui" class="btn-decision-submit approve" title="Setujui SPJ dan teruskan ke PPK">
                                        <ion-icon name="send-outline" style="font-size: 18px;"></ion-icon>
                                        <span>Setujui & Teruskan ke PPK</span>
                                    </button>
                                    <button type="button" onclick="openWaModal('setuju_umum')" class="btn-decision-wa-quick" title="Kirim Pesan WhatsApp">
                                        <ion-icon name="logo-whatsapp" style="font-size: 18px;"></ion-icon>
                                        <span>Kirim WA</span>
                                    </button>
                                </div>
                            </div>

                            <!-- OPSI 2: TOLAK / KEMBALIKAN KE TEKNIS -->
                            <div class="decision-box decision-box-reject">
                                <h4 class="decision-box-title">
                                    <ion-icon name="close-circle" style="color: #ef4444; font-size: 22px;"></ion-icon>
                                    <span>Kembalikan untuk Revisi</span>
                                </h4>
                                <p class="decision-box-desc">
                                    Berkas belum lengkap atau ada ketidaksesuaian administrasi. SPJ akan dikembalikan ke staf Teknis untuk diperbaiki.
                                </p>

                                <!-- Quick Template Chips -->
                                <div class="quick-chips-label">
                                    <ion-icon name="flash-outline"></ion-icon>
                                    <span>Klik chip untuk isi catatan cepat:</span>
                                </div>
                                <div class="quick-chips-wrap">
                                    <button type="button" class="quick-chip-btn" onclick="addRevisionChip('Kuitansi asli belum dibubuhi materai yang sesuai.')">Materai belum ada</button>
                                    <button type="button" class="quick-chip-btn" onclick="addRevisionChip('Nota/faktur belum memiliki tanda tangan basah dan cap.')">Tanda tangan/cap toko</button>
                                    <button type="button" class="quick-chip-btn" onclick="addRevisionChip('Daftar hadir dan foto dokumentasi kegiatan belum dilampirkan.')">Daftar hadir/foto</button>
                                    <button type="button" class="quick-chip-btn" onclick="addRevisionChip('Jumlah nominal kuitansi tidak sesuai dengan rincian biaya.')">Nominal tidak cocok</button>
                                    <button type="button" class="quick-chip-btn" onclick="addRevisionChip('Berita Acara Serah Terima (BAST) belum ditandatangani.')">BAST belum TTD</button>
                                </div>

                                <label class="decision-label" for="catatan_revisi">
                                    Rincian Kesalahan / Catatan Revisi (Wajib jika dikembalikan)
                                </label>
                                <textarea name="catatan_revisi" id="catatan_revisi" class="decision-textarea" rows="3" placeholder="Tuliskan poin-poin yang harus diperbaiki oleh staf teknis..."></textarea>

                                <div class="decision-actions-row">
                                    <button type="submit" name="action" value="tolak" class="btn-decision-submit reject" title="Kembalikan SPJ ke Staf Teknis">
                                        <ion-icon name="arrow-undo-outline" style="font-size: 18px;"></ion-icon>
                                        <span>Kembalikan ke Teknis</span>
                                    </button>
                                    <button type="button" onclick="openWaModalWithNote('revisi_umum')" class="btn-decision-wa-quick" title="Kirim Catatan via WhatsApp">
                                        <ion-icon name="logo-whatsapp" style="font-size: 18px;"></ion-icon>
                                        <span>Kirim WA</span>
                                    </button>
                                </div>
                            </div>

                        </div>

                        @error('catatan_revisi')
                        <div style="color: #dc2626; font-size: 13px; margin-top: 16px; text-align: center; font-weight: 600; background: #fee2e2; padding: 10px; border-radius: 8px;">
                            <ion-icon name="warning" style="vertical-align: middle;"></ion-icon> {{ $message }}
                        </div>
                        @enderror
                    </form>
                </div>
            </div>
            @endif

        <!-- ==================== TAHAP 2: STATUS DISETUJUI_UMUM (MEJA PPK) ==================== -->
        @elseif($spj->status === 'disetujui_umum')
        <div class="verify-card">
            <div class="verify-card-header">
                <h3 class="verify-card-title">
                    <ion-icon name="create-outline" style="color: #0284c7; font-size: 20px;"></ion-icon>
                    Input Keputusan Pejabat Pembuat Komitmen (PPK)
                </h3>
                <span style="font-size: 12px; font-weight: 700; color: #1d4ed8; background: #eff6ff; padding: 4px 10px; border-radius: 999px; border: 1px solid #bfdbfe;">
                    Tahap 2: Tanda Tangan PPK
                </span>
            </div>
            <div class="verify-card-body">
                <p style="color: var(--dash-text-muted); margin-top: 0; margin-bottom: 22px; font-size: 14px; line-height: 1.5;">
                    Dokumen fisik SPJ saat ini berada di meja <strong>Pejabat Pembuat Komitmen (PPK)</strong> untuk diperiksa dan ditandatangani. Perbarui status berdasarkan hasil tindak lanjut PPK di bawah ini:
                </p>

                <form action="{{ route('umum.spj.verify', $spj->id) }}" method="POST">
                    @csrf

                    <div class="decision-grid-two">
                        
                        <!-- SETUJUI PPK -->
                        <div class="decision-box decision-box-approve">
                            <h4 class="decision-box-title">
                                <ion-icon name="checkmark-circle" style="color: #10b981; font-size: 22px;"></ion-icon>
                                <span>PPK Telah Menyetujui (TTD)</span>
                            </h4>
                            <p class="decision-box-desc">
                                PPK telah menandatangani berkas fisik SPJ. Status otomatis berlanjut ke meja PPSPM untuk proses penerbitan Surat Perintah Membayar (SPM).
                            </p>

                            <div class="decision-actions-row">
                                <button type="submit" name="action" value="setujui_ppk" class="btn-decision-submit approve">
                                    <ion-icon name="checkmark-done-outline" style="font-size: 18px;"></ion-icon>
                                    <span>PPK Setuju & TTD (Lanjut PPSPM)</span>
                                </button>
                                <button type="button" onclick="openWaModal('setuju_ppk')" class="btn-decision-wa-quick">
                                    <ion-icon name="logo-whatsapp" style="font-size: 18px;"></ion-icon>
                                    <span>Kirim WA</span>
                                </button>
                            </div>
                        </div>

                        <!-- TOLAK / REVISI PPK -->
                        <div class="decision-box decision-box-reject">
                            <h4 class="decision-box-title">
                                <ion-icon name="close-circle" style="color: #ef4444; font-size: 22px;"></ion-icon>
                                <span>PPK Meminta Revisi</span>
                            </h4>
                            <p class="decision-box-desc">
                                PPK menemukan catatan atau penyesuaian yang harus diperbaiki oleh staf pengaju sebelum ditandatangani.
                            </p>

                            <label class="decision-label" for="catatan_revisi_ppk">
                                Catatan Perbaikan dari PPK (Wajib jika dikembalikan)
                            </label>
                            <textarea name="catatan_revisi" id="catatan_revisi_ppk" class="decision-textarea" rows="3" placeholder="Tuliskan catatan perbaikan langsung dari PPK..."></textarea>

                            <div class="decision-actions-row">
                                <button type="submit" name="action" value="tolak_ppk" class="btn-decision-submit reject">
                                    <ion-icon name="arrow-undo-outline" style="font-size: 18px;"></ion-icon>
                                    <span>Kembalikan ke Teknis (Revisi PPK)</span>
                                </button>
                                <button type="button" onclick="openWaModalWithNote('revisi_ppk')" class="btn-decision-wa-quick">
                                    <ion-icon name="logo-whatsapp" style="font-size: 18px;"></ion-icon>
                                    <span>Kirim WA</span>
                                </button>
                            </div>
                        </div>

                    </div>

                    @error('catatan_revisi')
                    <div style="color: #dc2626; font-size: 13px; margin-top: 16px; text-align: center; font-weight: 600; background: #fee2e2; padding: 10px; border-radius: 8px;">
                        <ion-icon name="warning" style="vertical-align: middle;"></ion-icon> {{ $message }}
                    </div>
                    @enderror
                </form>
            </div>
        </div>

        <!-- ==================== TAHAP 3: STATUS DISETUJUI_PPK (MEJA PPSPM) ==================== -->
        @elseif($spj->status === 'disetujui_ppk')
        <div class="verify-card">
            <div class="verify-card-header">
                <h3 class="verify-card-title">
                    <ion-icon name="wallet-outline" style="color: #8b5cf6; font-size: 20px;"></ion-icon>
                    Input Konfirmasi Persetujuan PPSPM
                </h3>
                <span style="font-size: 12px; font-weight: 700; color: #6d28d9; background: #f5f3ff; padding: 4px 10px; border-radius: 999px; border: 1px solid #ddd6fe;">
                    Tahap 3: Persetujuan PPSPM
                </span>
            </div>
            <div class="verify-card-body">
                <p style="color: var(--dash-text-muted); margin-top: 0; margin-bottom: 22px; font-size: 14px; line-height: 1.5;">
                    Berkas SPJ sedang diperiksa oleh <strong>Pejabat Penandatangan Surat Perintah Membayar (PPSPM)</strong>. Silakan perbarui status berdasarkan hasil pengujian PPSPM:
                </p>

                <form action="{{ route('umum.spj.verify', $spj->id) }}" method="POST">
                    @csrf

                    <div class="decision-grid-two">
                        
                        <!-- SETUJUI PPSPM -->
                        <div class="decision-box" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.08), rgba(2, 132, 199, 0.04)); border: 1.5px solid rgba(139, 92, 246, 0.4);">
                            <h4 class="decision-box-title" style="color: #5b21b6;">
                                <ion-icon name="checkmark-circle" style="color: #8b5cf6; font-size: 22px;"></ion-icon>
                                <span>PPSPM Menyetujui (Lanjut ke Bendahara)</span>
                            </h4>
                            <p class="decision-box-desc" style="color: #6d28d9;">
                                PPSPM telah menguji dan menerbitkan SPM. Berkas resmi diteruskan ke Bendahara Pengeluaran untuk proses pencairan dana.
                            </p>

                            <div class="decision-actions-row">
                                <button type="submit" name="action" value="setujui_ppspm" class="btn-decision-submit purple">
                                    <ion-icon name="cash-outline" style="font-size: 18px;"></ion-icon>
                                    <span>PPSPM Setuju (Teruskan Bendahara)</span>
                                </button>
                                <button type="button" onclick="openWaModal('setuju_ppspm')" class="btn-decision-wa-quick">
                                    <ion-icon name="logo-whatsapp" style="font-size: 18px;"></ion-icon>
                                    <span>Kirim WA</span>
                                </button>
                            </div>
                        </div>

                        <!-- TOLAK / REVISI PPSPM -->
                        <div class="decision-box decision-box-reject">
                            <h4 class="decision-box-title">
                                <ion-icon name="close-circle" style="color: #ef4444; font-size: 22px;"></ion-icon>
                                <span>PPSPM Menolak / Revisi</span>
                            </h4>
                            <p class="decision-box-desc">
                                PPSPM menemukan ketidaksesuaian SPM atau dokumen pendukung. Berkas dikembalikan ke staf Teknis.
                            </p>

                            <label class="decision-label" for="catatan_revisi_ppspm">
                                Catatan Revisi dari PPSPM (Wajib jika dikembalikan)
                            </label>
                            <textarea name="catatan_revisi" id="catatan_revisi_ppspm" class="decision-textarea" rows="3" placeholder="Tuliskan alasan penolakan/perbaikan dari PPSPM..."></textarea>

                            <div class="decision-actions-row">
                                <button type="submit" name="action" value="tolak_ppspm" class="btn-decision-submit reject">
                                    <ion-icon name="arrow-undo-outline" style="font-size: 18px;"></ion-icon>
                                    <span>Kembalikan ke Teknis (Revisi PPSPM)</span>
                                </button>
                                <button type="button" onclick="openWaModalWithNote('revisi_ppspm')" class="btn-decision-wa-quick">
                                    <ion-icon name="logo-whatsapp" style="font-size: 18px;"></ion-icon>
                                    <span>Kirim WA</span>
                                </button>
                            </div>
                        </div>

                    </div>

                    @error('catatan_revisi')
                    <div style="color: #dc2626; font-size: 13px; margin-top: 16px; text-align: center; font-weight: 600; background: #fee2e2; padding: 10px; border-radius: 8px;">
                        <ion-icon name="warning" style="vertical-align: middle;"></ion-icon> {{ $message }}
                    </div>
                    @enderror
                </form>
            </div>
        </div>

        <!-- ==================== TAHAP 4 & LAINNYA (DI BENDAHARA / SELESAI / REVISI) ==================== -->
        @else
        <div class="verify-card">
            <div class="verify-card-body">
                <div class="verify-status-banner">
                    <div class="verify-status-icon-circle">
                        @if($spj->status === 'disetujui_ppspm')
                            <ion-icon name="wallet-outline"></ion-icon>
                        @elseif($spj->status === 'selesai')
                            <ion-icon name="checkmark-done-circle-outline" style="color: #10b981;"></ion-icon>
                        @elseif($isRevision)
                            <ion-icon name="alert-circle-outline" style="color: #ef4444;"></ion-icon>
                        @else
                            <ion-icon name="information-circle-outline"></ion-icon>
                        @endif
                    </div>

                    <h3 class="verify-status-title">
                        Status Terkini: 
                        <span style="color: #0284c7;">{{ strtoupper(str_replace('_', ' ', $spj->status)) }}</span>
                    </h3>

                    <p class="verify-status-sub">
                        @if($spj->status === 'disetujui_ppspm')
                            Dokumen telah disetujui oleh PPSPM dan kini berada di <strong>Bendahara Pengeluaran</strong> untuk proses pencairan dana dan transfer ke penerima.
                        @elseif($spj->status === 'selesai')
                            SPJ ini telah <strong>selesai dicairkan</strong> oleh Bendahara Pengeluaran dan masuk ke dalam arsip resmi.
                        @elseif($isRevision)
                            SPJ ini telah dikembalikan ke staf <strong>Teknis</strong> untuk diperbaiki sesuai catatan perbaikan. Anda dapat menghubungi pengaju untuk konfirmasi perbaikan.
                        @else
                            SPJ ini sudah tidak berada dalam antrean pemeriksaan Subbagian Umum.
                        @endif
                    </p>

                    @if($spj->catatan_revisi)
                    <div style="margin: 20px auto 0 auto; max-width: 500px; padding: 14px 18px; background: rgba(239, 68, 68, 0.08); border-radius: 12px; border: 1px solid rgba(239, 68, 68, 0.3); text-align: left;">
                        <span style="font-size: 11px; font-weight: 700; color: #ef4444; text-transform: uppercase;">Catatan Revisi Terakhir:</span>
                        <p style="margin: 4px 0 0 0; font-size: 13.5px; color: var(--dash-text-dark); line-height: 1.5; white-space: pre-line;">{{ $spj->catatan_revisi }}</p>
                    </div>
                    @endif

                    <div style="margin-top: 24px; display: flex; justify-content: center; gap: 12px; flex-wrap: wrap;">
                        <button type="button" onclick="openWaModal()" class="btn-wa-header">
                            <ion-icon name="logo-whatsapp" style="font-size: 18px;"></ion-icon>
                            <span>Hubungi Pengaju via WhatsApp</span>
                        </button>
                        <a href="{{ route('umum.spj.index') }}" class="btn-back-header">
                            <span>Kembali ke Kotak Masuk</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>

</div>

<!-- ====================================================================
     8. MODAL WHATSAPP INTERAKTIF & LENGKAP DENGAN 7 TEMPLATE RESMI
     ==================================================================== -->
<div id="waModal" class="wa-modal-overlay">
    <div class="wa-modal-box">
        <!-- Header Modal -->
        <div class="wa-modal-header">
            <div class="wa-modal-header-left">
                <div class="wa-modal-icon-badge">
                    <ion-icon name="logo-whatsapp"></ion-icon>
                </div>
                <div>
                    <h3>Kirim Notifikasi WhatsApp Resmi</h3>
                    <small>Template pesan otomatis terisi sesuai data SPJ dan siap dikirim</small>
                </div>
            </div>
            <button type="button" onclick="closeWaModal()" class="wa-modal-btn-close" title="Tutup Modal">
                <ion-icon name="close" style="font-size: 20px;"></ion-icon>
            </button>
        </div>

        <div class="wa-modal-body">
            <!-- NOMOR HP TUJUAN -->
            @php
                $recipientWa = $spj->no_wa ?: ($spj->user->no_wa ?? '');
            @endphp
            <div class="wa-modal-field-group">
                <label class="wa-modal-label" for="waRecipient">
                    Nomor WhatsApp Pengaju ({{ $spj->user->name ?? 'Staf Teknis' }}):
                </label>
                <input type="text" id="waRecipient" class="wa-modal-input" value="{{ $recipientWa }}" placeholder="Contoh: 081234567890 (atau nomor PIC)">
                @if(empty($recipientWa))
                <small style="color: #d97706; font-size: 11px; display: flex; align-items: center; gap: 4px; margin-top: 5px;">
                    <ion-icon name="information-circle-outline"></ion-icon>
                    Nomor belum terisi otomatis. Anda dapat mengetikkan nomor HP secara manual di atas.
                </small>
                @endif
            </div>

            <!-- PILIHAN TEMPLATE PESAN -->
            <div class="wa-modal-field-group">
                <label class="wa-modal-label">Pilih Template Pesan:</label>
                <div class="wa-template-chips">
                    <button type="button" id="btnTplSetujuUmum" class="wa-tpl-chip" onclick="setWaTemplate('setuju_umum')">
                        ✅ 1. Teruskan ke PPK
                    </button>
                    <button type="button" id="btnTplSetujuPpk" class="wa-tpl-chip" onclick="setWaTemplate('setuju_ppk')">
                        ✍️ 2. Persetujuan PPK
                    </button>
                    <button type="button" id="btnTplSetujuPpspm" class="wa-tpl-chip" onclick="setWaTemplate('setuju_ppspm')">
                        💵 3. Teruskan ke Bendahara
                    </button>
                    <button type="button" id="btnTplRevisiUmum" class="wa-tpl-chip" onclick="setWaTemplate('revisi_umum')">
                        ⚠️ Revisi Umum
                    </button>
                    <button type="button" id="btnTplRevisiPpk" class="wa-tpl-chip" onclick="setWaTemplate('revisi_ppk')">
                        ⚠️ Revisi PPK
                    </button>
                    <button type="button" id="btnTplRevisiPpspm" class="wa-tpl-chip" onclick="setWaTemplate('revisi_ppspm')">
                        ⚠️ Revisi PPSPM
                    </button>
                    <button type="button" id="btnTplFisik" class="wa-tpl-chip" onclick="setWaTemplate('fisik')">
                        📄 Berkas Fisik
                    </button>
                </div>
            </div>

            <!-- CHAT BUBBLE PREVIEW TEXTAREA -->
            <div class="wa-modal-field-group">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 7px;">
                    <label class="wa-modal-label" style="margin-bottom: 0;">Isi Pesan (Bisa Diedit):</label>
                    <button type="button" onclick="copyWaText()" id="btnCopyWa" style="background: none; border: none; color: #0284c7; font-size: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 4px;">
                        <ion-icon name="copy-outline"></ion-icon>
                        <span>Salin Teks</span>
                    </button>
                </div>
                <div class="wa-chat-preview-wrap">
                    <div class="wa-chat-bubble">
                        <textarea id="waMessageText" class="wa-chat-textarea" rows="8"></textarea>
                    </div>
                </div>
            </div>

            <!-- FOOTER ACTIONS -->
            <div class="wa-modal-footer">
                <button type="button" class="btn-wa-dismiss" onclick="closeWaModal()">
                    Tutup
                </button>
                <button type="button" onclick="sendWhatsAppNow()" class="btn-wa-submit-launch">
                    <ion-icon name="logo-whatsapp" style="font-size: 18px;"></ion-icon>
                    <span>Buka di WhatsApp Web / App</span>
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

    // Tambahkan teks dari chip ke textarea revisi
    function addRevisionChip(text) {
        const textarea = document.querySelector('textarea[name="catatan_revisi"]');
        if (!textarea) return;
        
        let val = textarea.value.trim();
        if (val) {
            val += "\n• " + text;
        } else {
            val = "• " + text;
        }
        textarea.value = val;
        textarea.focus();
    }

    // Buka Modal WhatsApp
    function openWaModal(defaultTpl = 'setuju_umum') {
        const modal = document.getElementById('waModal');
        if (modal) {
            modal.style.display = 'flex';
            setWaTemplate(defaultTpl);
        }
    }

    // Buka Modal WhatsApp dengan isi catatan revisi yang sedang diketik
    function openWaModalWithNote(stageTpl = 'revisi_umum') {
        const activeTextarea = document.querySelector('textarea[name="catatan_revisi"]');
        if (activeTextarea && activeTextarea.value.trim()) {
            spjData.activeInputNote = activeTextarea.value.trim();
        } else {
            spjData.activeInputNote = '';
        }
        openWaModal(stageTpl);
    }

    // Tutup Modal WhatsApp
    function closeWaModal() {
        const modal = document.getElementById('waModal');
        if (modal) modal.style.display = 'none';
    }

    // Tutup jika klik overlay luar atau tekan ESC
    window.addEventListener('click', function(e) {
        const modal = document.getElementById('waModal');
        if (e.target === modal) {
            closeWaModal();
        }
    });

    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeWaModal();
        }
    });

    // Pilih Template WhatsApp
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

        const isDark = document.documentElement.classList.contains('dark-theme');

        // Reset semua style button
        Object.values(btnMap).forEach(btn => {
            if (btn) {
                btn.style.background = isDark ? 'var(--dash-input-bg)' : '#ffffff';
                btn.style.color = 'var(--dash-text-muted)';
                btn.style.borderColor = 'var(--dash-border)';
            }
        });

        // Set style button aktif
        const activeBtn = btnMap[tpl];
        if (activeBtn) {
            if (tpl.startsWith('setuju')) {
                activeBtn.style.background = isDark ? 'rgba(16, 185, 129, 0.25)' : '#ecfdf5';
                activeBtn.style.color = isDark ? '#34d399' : '#047857';
                activeBtn.style.borderColor = '#10b981';
            } else if (tpl.startsWith('revisi')) {
                activeBtn.style.background = isDark ? 'rgba(239, 68, 68, 0.25)' : '#fef2f2';
                activeBtn.style.color = isDark ? '#f87171' : '#b91c1c';
                activeBtn.style.borderColor = '#f87171';
            } else {
                activeBtn.style.background = isDark ? 'rgba(2, 132, 199, 0.25)' : '#eff6ff';
                activeBtn.style.color = isDark ? '#38bdf8' : '#1d4ed8';
                activeBtn.style.borderColor = '#60a5fa';
            }
        }

        const noteText = spjData.activeInputNote || spjData.catatanRevisi || 'Mohon lengkapi dan sesuaikan berkas fisik SPJ sesuai dengan checklist persyaratan SOP.';

        let message = '';

        if (tpl === 'setuju_umum') {
            message = `*PEMBERITAHUAN SPJ: DIVERIFIKASI UMUM*\n`
                    + `BPS Kabupaten Kolaka Utara\n\n`
                    + `Halo Sdr/i *${spjData.pengaju}*,\n\n`
                    + `Pengajuan SPJ Anda telah diverifikasi oleh Subbagian Umum dan dinyatakan *LENGKAP & BENAR*:\n\n`
                    + `• *Kegiatan* : ${spjData.kegiatan}\n`
                    + `• *Nilai*    : ${spjData.nilai}\n`
                    + `• *Status*   : DISETUJUI UMUM (Menunggu TTD PPK)\n\n`
                    + `📌 *Langkah Selanjutnya:*\n`
                    + `Harap segera membawa dokumen fisik SPJ ke meja *Pejabat Pembuat Komitmen (PPK)* untuk ditandatangani.\n\n`
                    + `Pantau detail dokumen Anda di tautan berikut:\n`
                    + `${spjData.linkDetail}\n\n`
                    + `Terima kasih.\n`
                    + `_Sistem Informasi Penyelesaian Tagihan SPJ - BPS Kolaka Utara_`;

        } else if (tpl === 'setuju_ppk') {
            message = `*PEMBERITAHUAN SPJ: PERSETUJUAN PPK*\n`
                    + `BPS Kabupaten Kolaka Utara\n\n`
                    + `Halo Sdr/i *${spjData.pengaju}*,\n\n`
                    + `Dokumen fisik SPJ Anda telah *DITANDATANGANI OLEH PPK*:\n\n`
                    + `• *Kegiatan* : ${spjData.kegiatan}\n`
                    + `• *Nilai*    : ${spjData.nilai}\n`
                    + `• *Status*   : DISETUJUI PPK (Menuju Persetujuan PPSPM)\n\n`
                    + `📌 *Langkah Selanjutnya:*\n`
                    + `Harap segera membawa berkas fisik SPJ ke meja *PPSPM* untuk diverifikasi dan diterbitkan SPM.\n\n`
                    + `Pantau detail dokumen Anda di tautan berikut:\n`
                    + `${spjData.linkDetail}\n\n`
                    + `Terima kasih.\n`
                    + `_Sistem Informasi Penyelesaian Tagihan SPJ - BPS Kolaka Utara_`;

        } else if (tpl === 'setuju_ppspm') {
            message = `*PEMBERITAHUAN SPJ: PERSETUJUAN PPSPM*\n`
                    + `BPS Kabupaten Kolaka Utara\n\n`
                    + `Halo Sdr/i *${spjData.pengaju}*,\n\n`
                    + `Dokumen pengajuan SPJ Anda telah *DISETUJUI OLEH PPSPM* dan kini telah diteruskan ke Bendahara Pengeluaran:\n\n`
                    + `• *Kegiatan* : ${spjData.kegiatan}\n`
                    + `• *Nilai*    : ${spjData.nilai}\n`
                    + `• *Status*   : DISETUJUI PPSPM (Dalam Proses Pencairan Bendahara)\n\n`
                    + `📌 *Langkah Selanjutnya:*\n`
                    + `SPJ Anda kini berada di Bendahara Pengeluaran untuk proses pencairan dana. Harap pantau rekening atau status di sistem.\n\n`
                    + `Pantau detail dokumen Anda di tautan berikut:\n`
                    + `${spjData.linkDetail}\n\n`
                    + `Terima kasih.\n`
                    + `_Sistem Informasi Penyelesaian Tagihan SPJ - BPS Kolaka Utara_`;

        } else if (tpl === 'revisi_umum') {
            message = `*PEMBERITAHUAN PERBAIKAN (REVISI) SPJ*\n`
                    + `BPS Kabupaten Kolaka Utara\n\n`
                    + `Halo Sdr/i *${spjData.pengaju}*,\n\n`
                    + `Dokumen SPJ Anda memerlukan *PERBAIKAN / REVISI* dari Subbagian Umum:\n\n`
                    + `• *Kegiatan* : ${spjData.kegiatan}\n`
                    + `• *Catatan Verifikator Umum*:\n"${noteText}"\n\n`
                    + `Mohon segera perbaiki berkas dan ajukan kembali melalui tautan berikut:\n`
                    + `${spjData.linkEdit}\n\n`
                    + `Terima kasih.\n`
                    + `_Sistem Informasi Penyelesaian Tagihan SPJ - BPS Kolaka Utara_`;

        } else if (tpl === 'revisi_ppk') {
            message = `*PEMBERITAHUAN REVISI DARI PPK*\n`
                    + `BPS Kabupaten Kolaka Utara\n\n`
                    + `Halo Sdr/i *${spjData.pengaju}*,\n\n`
                    + `Dokumen SPJ Anda dikembalikan dengan *CATATAN REVISI DARI PPK*:\n\n`
                    + `• *Kegiatan* : ${spjData.kegiatan}\n`
                    + `• *Catatan Perbaikan PPK*:\n"${noteText}"\n\n`
                    + `Mohon segera lakukan penyesuaian berkas melalui tautan berikut:\n`
                    + `${spjData.linkEdit}\n\n`
                    + `Terima kasih.\n`
                    + `_Sistem Informasi Penyelesaian Tagihan SPJ - BPS Kolaka Utara_`;

        } else if (tpl === 'revisi_ppspm') {
            message = `*PEMBERITAHUAN REVISI DARI PPSPM*\n`
                    + `BPS Kabupaten Kolaka Utara\n\n`
                    + `Halo Sdr/i *${spjData.pengaju}*,\n\n`
                    + `Dokumen SPJ Anda dikembalikan dengan *CATATAN REVISI DARI PPSPM*:\n\n`
                    + `• *Kegiatan* : ${spjData.kegiatan}\n`
                    + `• *Catatan Perbaikan PPSPM*:\n"${noteText}"\n\n`
                    + `Mohon segera perbaiki berkas melalui tautan berikut:\n`
                    + `${spjData.linkEdit}\n\n`
                    + `Terima kasih.\n`
                    + `_Sistem Informasi Penyelesaian Tagihan SPJ - BPS Kolaka Utara_`;

        } else if (tpl === 'fisik') {
            message = `*PENGINGAT PENYERAHAN DOKUMEN FISIK SPJ*\n`
                    + `BPS Kabupaten Kolaka Utara\n\n`
                    + `Halo Sdr/i *${spjData.pengaju}*,\n\n`
                    + `Mengingatkan untuk pengajuan SPJ Anda berikut:\n\n`
                    + `• *Kegiatan* : ${spjData.kegiatan}\n`
                    + `• *Nilai*    : ${spjData.nilai}\n\n`
                    + `Mohon segera menyerahkan berkas fisik SPJ (kuitansi asli, nota/faktur toko, BAST, dll.) ke meja Subbagian Umum agar dapat segera diperiksa dan diproses.\n\n`
                    + `Terima kasih.\n`
                    + `_Sistem Informasi Penyelesaian Tagihan SPJ - BPS Kolaka Utara_`;
        }

        if (textarea) textarea.value = message;
    }

    // Salin Teks WhatsApp
    function copyWaText() {
        const textarea = document.getElementById('waMessageText');
        if (!textarea) return;

        textarea.select();
        navigator.clipboard.writeText(textarea.value).then(() => {
            const btnCopy = document.getElementById('btnCopyWa');
            const span = btnCopy.querySelector('span');
            span.textContent = 'Tersalin! ✔';
            btnCopy.style.color = '#10b981';
            setTimeout(() => {
                span.textContent = 'Salin Teks';
                btnCopy.style.color = '#0284c7';
            }, 2000);
        });
    }

    // Buka WhatsApp Web / App
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
