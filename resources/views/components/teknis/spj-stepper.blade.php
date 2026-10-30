@props(['spj', 'title' => null, 'showHeader' => true])

@php
    $isRevision = str_contains($spj->status, 'revisi') || $spj->status === 'dikembalikan';
    
    // Tentukan tahap aktif (1-5) sesuai SOP 5 Pintu BPS
    $currentStep = 1;
    if ($spj->status === 'diajukan') {
        $currentStep = 2; // Berkas di Subbagian Umum
    } elseif ($spj->status === 'disetujui_umum') {
        $currentStep = 3; // Berkas di PPK
    } elseif ($spj->status === 'disetujui_ppk') {
        $currentStep = 4; // Berkas di PPSPM
    } elseif (in_array($spj->status, ['disetujui_ppspm', 'menunggu_otp_kpa', 'proses_ls'])) {
        $currentStep = 5; // Berkas di Bendahara
    } elseif ($spj->status === 'selesai') {
        $currentStep = 5; // Selesai pencairan
    }

    if ($isRevision) {
        if ($spj->status === 'revisi_umum') $currentStep = 2;
        elseif ($spj->status === 'revisi_ppk') $currentStep = 3;
        elseif ($spj->status === 'revisi_ppspm') $currentStep = 4;
        elseif ($spj->status === 'revisi_bendahara') $currentStep = 5;
        else $currentStep = 2;
    }

    // Step 2 (Umum) State
    $step2Class = 'waiting';
    if ($spj->disetujui_umum_at || $currentStep > 2 || $spj->status === 'selesai') {
        $step2Class = 'completed';
    } elseif ($spj->status === 'revisi_umum') {
        $step2Class = 'revision';
    } elseif ($currentStep === 2) {
        $step2Class = 'active';
    }

    // Step 3 (PPK) State
    $step3Class = 'waiting';
    if ($spj->disetujui_ppk_at || $currentStep > 3 || $spj->status === 'selesai') {
        $step3Class = 'completed';
    } elseif ($spj->status === 'revisi_ppk') {
        $step3Class = 'revision';
    } elseif ($currentStep === 3) {
        $step3Class = 'active';
    }

    // Step 4 (PPSPM) State
    $step4Class = 'waiting';
    if ($spj->disetujui_ppspm_at || $currentStep > 4 || $spj->status === 'selesai') {
        $step4Class = 'completed';
    } elseif ($spj->status === 'revisi_ppspm') {
        $step4Class = 'revision';
    } elseif ($currentStep === 4) {
        $step4Class = 'active';
    }

    // Step 5 (Bendahara) State
    $step5Class = 'waiting';
    if ($spj->status === 'selesai') {
        $step5Class = 'completed';
    } elseif ($spj->status === 'revisi_bendahara') {
        $step5Class = 'revision';
    } elseif ($currentStep === 5) {
        $step5Class = 'active';
    }
@endphp

<div class="teknis-stepper-card">
    @if($showHeader)
    <div class="teknis-stepper-header">
        <div class="teknis-stepper-header-left">
            <span class="teknis-stepper-pill">SOP 5 Pintu BPS</span>
            <span class="teknis-stepper-title">{{ $title ?? 'Alur Birokrasi & Penyelesaian Tagihan SPJ' }}</span>
        </div>
        <div class="teknis-stepper-header-right">
            @if($isRevision)
                <span class="teknis-stepper-status-badge badge-revision">
                    <ion-icon name="alert-circle"></ion-icon>
                    Perlu Revisi ({{ strtoupper(str_replace('revisi_', '', $spj->status)) }})
                </span>
            @elseif($spj->status === 'selesai')
                <span class="teknis-stepper-status-badge badge-success">
                    <ion-icon name="checkmark-done-circle"></ion-icon>
                    Pembayaran Selesai Dicairkan
                </span>
            @else
                <span class="teknis-stepper-status-badge badge-active">
                    <span class="stepper-live-dot"></span>
                    Tahap Aktif: <strong>Tahap {{ $currentStep }} dari 5</strong>
                </span>
            @endif
        </div>
    </div>
    @endif

    <div class="teknis-stepper-track">
        <!-- TAHAP 1: TEKNIS -->
        <div class="teknis-stepper-item completed">
            <div class="teknis-stepper-connector"></div>
            <div class="teknis-stepper-node">
                <ion-icon name="checkmark"></ion-icon>
            </div>
            <div class="teknis-stepper-content">
                <span class="teknis-step-title">1. Pengajuan Teknis</span>
                <span class="teknis-step-sub">{{ $spj->user->name ?? 'Staf Teknis' }}</span>
                @if($spj->diajukan_at || $spj->created_at)
                    <span class="teknis-step-time">{{ ($spj->diajukan_at ? \Carbon\Carbon::parse($spj->diajukan_at) : $spj->created_at)->format('d/m/Y H:i') }}</span>
                @else
                    <span class="teknis-step-time">Diajukan</span>
                @endif
            </div>
        </div>

        <!-- TAHAP 2: SUBBAG UMUM -->
        <div class="teknis-stepper-item {{ $step2Class }}">
            <div class="teknis-stepper-connector"></div>
            <div class="teknis-stepper-node">
                @if($step2Class === 'completed')
                    <ion-icon name="checkmark"></ion-icon>
                @elseif($step2Class === 'revision')
                    <ion-icon name="alert"></ion-icon>
                @else
                    2
                @endif
            </div>
            <div class="teknis-stepper-content">
                <span class="teknis-step-title">2. Verifikasi Umum</span>
                <span class="teknis-step-sub">Subbagian Umum</span>
                @if($step2Class === 'completed' && $spj->disetujui_umum_at)
                    <span class="teknis-step-time">{{ \Carbon\Carbon::parse($spj->disetujui_umum_at)->format('d/m/Y H:i') }}</span>
                @elseif($spj->status === 'revisi_umum')
                    <span class="teknis-step-time badge-time-revision">Perlu Revisi</span>
                @elseif($spj->status === 'diajukan')
                    @if(!$spj->berkas_fisik_diterima_at)
                        <span class="teknis-step-time badge-time-warning">Menunggu Fisik</span>
                    @elseif(!$spj->pemeriksaan_dimulai_at)
                        <span class="teknis-step-time badge-time-info">Fisik Diterima</span>
                    @else
                        <span class="teknis-step-time badge-time-active">Sedang Diperiksa</span>
                    @endif
                @endif
            </div>
        </div>

        <!-- TAHAP 3: PPK -->
        <div class="teknis-stepper-item {{ $step3Class }}">
            <div class="teknis-stepper-connector"></div>
            <div class="teknis-stepper-node">
                @if($step3Class === 'completed')
                    <ion-icon name="checkmark"></ion-icon>
                @elseif($step3Class === 'revision')
                    <ion-icon name="alert"></ion-icon>
                @else
                    3
                @endif
            </div>
            <div class="teknis-stepper-content">
                <span class="teknis-step-title">3. Tanda Tangan PPK</span>
                <span class="teknis-step-sub">Pejabat Pembuat Komitmen</span>
                @if($step3Class === 'completed' && $spj->disetujui_ppk_at)
                    <span class="teknis-step-time">{{ \Carbon\Carbon::parse($spj->disetujui_ppk_at)->format('d/m/Y H:i') }}</span>
                @elseif($spj->status === 'revisi_ppk')
                    <span class="teknis-step-time badge-time-revision">Revisi PPK</span>
                @elseif($currentStep === 3 && !$isRevision)
                    <span class="teknis-step-time badge-time-info">Menunggu TTD</span>
                @endif
            </div>
        </div>

        <!-- TAHAP 4: PPSPM -->
        <div class="teknis-stepper-item {{ $step4Class }}">
            <div class="teknis-stepper-connector"></div>
            <div class="teknis-stepper-node">
                @if($step4Class === 'completed')
                    <ion-icon name="checkmark"></ion-icon>
                @elseif($step4Class === 'revision')
                    <ion-icon name="alert"></ion-icon>
                @else
                    4
                @endif
            </div>
            <div class="teknis-stepper-content">
                <span class="teknis-step-title">4. Persetujuan PPSPM</span>
                <span class="teknis-step-sub">Penerbitan Dokumen SPM</span>
                @if($step4Class === 'completed' && $spj->disetujui_ppspm_at)
                    <span class="teknis-step-time">{{ \Carbon\Carbon::parse($spj->disetujui_ppspm_at)->format('d/m/Y H:i') }}</span>
                @elseif($spj->status === 'revisi_ppspm')
                    <span class="teknis-step-time badge-time-revision">Revisi PPSPM</span>
                @elseif($currentStep === 4 && !$isRevision)
                    <span class="teknis-step-time badge-time-info">Pengujian SPM</span>
                @endif
            </div>
        </div>

        <!-- TAHAP 5: BENDAHARA -->
        <div class="teknis-stepper-item {{ $step5Class }}">
            <div class="teknis-stepper-connector"></div>
            <div class="teknis-stepper-node">
                @if($step5Class === 'completed')
                    <ion-icon name="checkmark"></ion-icon>
                @elseif($step5Class === 'revision')
                    <ion-icon name="alert"></ion-icon>
                @else
                    5
                @endif
            </div>
            <div class="teknis-stepper-content">
                <span class="teknis-step-title">5. Pencairan Dana</span>
                <span class="teknis-step-sub">Bendahara Pengeluaran</span>
                @if($spj->status === 'selesai')
                    <span class="teknis-step-time badge-time-success">{{ $spj->diselesaikan_at ? \Carbon\Carbon::parse($spj->diselesaikan_at)->format('d/m/Y') : 'Cair' }}</span>
                @elseif($spj->status === 'revisi_bendahara')
                    <span class="teknis-step-time badge-time-revision">Revisi Bendahara</span>
                @elseif($spj->status === 'menunggu_otp_kpa')
                    <span class="teknis-step-time badge-time-purple">OTP QLOLA KPA</span>
                @elseif($spj->status === 'proses_ls')
                    <span class="teknis-step-time badge-time-purple">Kliring Bank LS</span>
                @elseif($spj->status === 'disetujui_ppspm')
                    <span class="teknis-step-time badge-time-info">Siap Dicairkan</span>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.teknis-stepper-card {
    background: var(--dash-card-bg, #ffffff);
    border: 1px solid var(--dash-border, #e2e8f0);
    border-radius: 16px;
    padding: 22px 24px 24px;
    margin-bottom: 24px;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
    box-sizing: border-box;
}

html.dark-theme .teknis-stepper-card {
    background: #1e293b;
    border-color: #334155;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
}

.teknis-stepper-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 22px;
    padding-bottom: 16px;
    border-bottom: 1px dashed var(--dash-border, #e2e8f0);
    flex-wrap: wrap;
}

html.dark-theme .teknis-stepper-header {
    border-bottom-color: #334155;
}

.teknis-stepper-header-left {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.teknis-stepper-pill {
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    padding: 4px 10px;
    border-radius: 6px;
    background: rgba(2, 132, 199, 0.12);
    color: #0284c7;
    border: 1px solid rgba(2, 132, 199, 0.25);
}

html.dark-theme .teknis-stepper-pill {
    background: rgba(56, 189, 248, 0.15);
    color: #38bdf8;
    border-color: rgba(56, 189, 248, 0.3);
}

.teknis-stepper-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--dash-text-dark, #0f172a);
}

html.dark-theme .teknis-stepper-title {
    color: #f8fafc;
}

.teknis-stepper-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 600;
    padding: 5px 12px;
    border-radius: 999px;
}

.teknis-stepper-status-badge.badge-active {
    background: #f0f9ff;
    color: #0369a1;
    border: 1px solid #bae6fd;
}

html.dark-theme .teknis-stepper-status-badge.badge-active {
    background: rgba(2, 132, 199, 0.18);
    color: #38bdf8;
    border-color: rgba(2, 132, 199, 0.4);
}

.teknis-stepper-status-badge.badge-success {
    background: #ecfdf5;
    color: #047857;
    border: 1px solid #a7f3d0;
}

html.dark-theme .teknis-stepper-status-badge.badge-success {
    background: rgba(16, 185, 129, 0.18);
    color: #34d399;
    border-color: rgba(16, 185, 129, 0.4);
}

.teknis-stepper-status-badge.badge-revision {
    background: #fef2f2;
    color: #b91c1c;
    border: 1px solid #fecaca;
}

html.dark-theme .teknis-stepper-status-badge.badge-revision {
    background: rgba(239, 68, 68, 0.18);
    color: #f87171;
    border-color: rgba(239, 68, 68, 0.4);
}

.stepper-live-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #0284c7;
    display: inline-block;
    box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.25);
    animation: stepperDotPulse 1.8s infinite ease-in-out;
}

@keyframes stepperDotPulse {
    0%, 100% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.3);
        opacity: 0.6;
    }
}

.teknis-stepper-track {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    position: relative;
    gap: 8px;
    overflow-x: auto;
    padding-bottom: 6px;
    -webkit-overflow-scrolling: touch;
}

.teknis-stepper-item {
    flex: 1;
    min-width: 140px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    position: relative;
    z-index: 2;
}

.teknis-stepper-connector {
    position: absolute;
    top: 20px;
    left: calc(-50% + 20px);
    right: calc(50% + 20px);
    height: 3px;
    background: var(--dash-border, #e2e8f0);
    z-index: 1;
    transition: background-color 0.3s ease;
}

html.dark-theme .teknis-stepper-connector {
    background: #334155;
}

.teknis-stepper-item:first-child .teknis-stepper-connector {
    display: none;
}

.teknis-stepper-item.completed .teknis-stepper-connector {
    background: #10b981;
}

.teknis-stepper-node {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--dash-card-bg, #ffffff);
    border: 2px solid var(--dash-border, #cbd5e1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 800;
    color: var(--dash-text-muted, #64748b);
    margin-bottom: 10px;
    position: relative;
    z-index: 2;
    transition: all 0.3s ease;
}

html.dark-theme .teknis-stepper-node {
    background: #1e293b;
    border-color: #475569;
    color: #94a3b8;
}

.teknis-stepper-item.completed .teknis-stepper-node {
    background: #10b981;
    border-color: #10b981;
    color: #ffffff;
    box-shadow: 0 3px 10px rgba(16, 185, 129, 0.35);
}

.teknis-stepper-item.active .teknis-stepper-node {
    background: #0284c7;
    border-color: #0284c7;
    color: #ffffff;
    box-shadow: 0 0 0 5px rgba(2, 132, 199, 0.22);
    animation: teknisPulse 2s infinite ease-in-out;
}

.teknis-stepper-item.revision .teknis-stepper-node {
    background: #ef4444;
    border-color: #ef4444;
    color: #ffffff;
    box-shadow: 0 0 0 5px rgba(239, 68, 68, 0.22);
}

@keyframes teknisPulse {
    0%, 100% {
        box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.25);
    }
    50% {
        box-shadow: 0 0 0 8px rgba(2, 132, 199, 0.08);
    }
}

.teknis-stepper-content {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.teknis-step-title {
    font-size: 13px;
    font-weight: 700;
    color: var(--dash-text-dark, #0f172a);
    margin-bottom: 3px;
    line-height: 1.3;
}

html.dark-theme .teknis-step-title {
    color: #f1f5f9;
}

.teknis-stepper-item.completed .teknis-step-title {
    color: #059669;
}

html.dark-theme .teknis-stepper-item.completed .teknis-step-title {
    color: #34d399;
}

.teknis-stepper-item.active .teknis-step-title {
    color: #0284c7;
}

html.dark-theme .teknis-stepper-item.active .teknis-step-title {
    color: #38bdf8;
}

.teknis-stepper-item.revision .teknis-step-title {
    color: #dc2626;
}

html.dark-theme .teknis-stepper-item.revision .teknis-step-title {
    color: #f87171;
}

.teknis-step-sub {
    font-size: 11px;
    color: var(--dash-text-muted, #64748b);
    line-height: 1.3;
}

html.dark-theme .teknis-step-sub {
    color: #94a3b8;
}

.teknis-step-time {
    margin-top: 5px;
    font-size: 10.5px;
    font-weight: 600;
    color: #0284c7;
    background: #f0f9ff;
    border: 1px solid #bae6fd;
    padding: 2px 8px;
    border-radius: 999px;
    display: inline-block;
    white-space: nowrap;
}

html.dark-theme .teknis-step-time {
    background: rgba(2, 132, 199, 0.18);
    color: #38bdf8;
    border-color: rgba(2, 132, 199, 0.35);
}

.badge-time-warning {
    background: #fffbeb !important;
    color: #b45309 !important;
    border-color: #fde68a !important;
}

html.dark-theme .badge-time-warning {
    background: rgba(245, 158, 11, 0.2) !important;
    color: #fbbf24 !important;
    border-color: rgba(245, 158, 11, 0.4) !important;
}

.badge-time-info {
    background: #f0f9ff !important;
    color: #0284c7 !important;
    border-color: #bae6fd !important;
}

html.dark-theme .badge-time-info {
    background: rgba(2, 132, 199, 0.2) !important;
    color: #38bdf8 !important;
    border-color: rgba(2, 132, 199, 0.4) !important;
}

.badge-time-active {
    background: #fef3c7 !important;
    color: #b45309 !important;
    border-color: #fde68a !important;
    font-weight: 700 !important;
}

html.dark-theme .badge-time-active {
    background: rgba(245, 158, 11, 0.25) !important;
    color: #fbbf24 !important;
    border-color: rgba(245, 158, 11, 0.45) !important;
}

.badge-time-success {
    background: #ecfdf5 !important;
    color: #059669 !important;
    border-color: #a7f3d0 !important;
}

html.dark-theme .badge-time-success {
    background: rgba(16, 185, 129, 0.2) !important;
    color: #34d399 !important;
    border-color: rgba(16, 185, 129, 0.4) !important;
}

.badge-time-revision {
    background: #fef2f2 !important;
    color: #dc2626 !important;
    border-color: #fecaca !important;
}

html.dark-theme .badge-time-revision {
    background: rgba(239, 68, 68, 0.2) !important;
    color: #f87171 !important;
    border-color: rgba(239, 68, 68, 0.4) !important;
}

.badge-time-purple {
    background: #f5f3ff !important;
    color: #7c3aed !important;
    border-color: #ddd6fe !important;
}

html.dark-theme .badge-time-purple {
    background: rgba(124, 58, 237, 0.2) !important;
    color: #a78bfa !important;
    border-color: rgba(124, 58, 237, 0.4) !important;
}

@media (max-width: 640px) {
    .teknis-stepper-card {
        padding: 14px 14px 16px;
        margin-bottom: 18px;
        border-radius: 12px;
    }

    .teknis-stepper-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 14px;
        padding-bottom: 12px;
    }

    .teknis-stepper-header-left {
        width: 100%;
        gap: 8px;
    }

    .teknis-stepper-header-right {
        width: 100%;
    }

    .teknis-stepper-status-badge {
        width: 100%;
        box-sizing: border-box;
        justify-content: center;
    }

    .teknis-stepper-title {
        font-size: 13px;
    }

    .teknis-stepper-track {
        gap: 4px;
    }

    .teknis-stepper-item {
        min-width: 120px;
    }

    .teknis-stepper-node {
        width: 34px;
        height: 34px;
        font-size: 12px;
        margin-bottom: 8px;
    }

    .teknis-step-title {
        font-size: 11.5px;
    }

    .teknis-step-sub {
        font-size: 10px;
    }

    .teknis-step-time {
        font-size: 9.5px;
        padding: 2px 6px;
    }
}
</style>
