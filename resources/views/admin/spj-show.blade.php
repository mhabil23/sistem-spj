@extends('layouts.admin')

@php
    $title = 'Detail SPJ';
    $dokumen = $spj->dokumen_file ? json_decode($spj->dokumen_file, true) : [];
@endphp

@section('content')

@push('styles')
<style>
    .detail-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
        width: 100%;
        color: #1e293b;
    }

    /* Page Header */
    .detail-header-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 24px 28px;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .header-info h2 {
        font-size: 1.5rem;
        font-weight: 800;
        color: #082052;
        margin: 0 0 6px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .header-info p {
        color: #64748b;
        font-size: 0.9rem;
        margin: 0;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-action-detail {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }

    .btn-action-primary {
        background: #024b86;
        color: #ffffff;
    }

    .btn-action-primary:hover {
        background: #082052;
        transform: translateY(-1px);
        color: #ffffff;
    }

    .btn-action-secondary {
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #cbd5e1;
    }

    .btn-action-secondary:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .btn-action-danger {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    .btn-action-danger:hover {
        background: #dc2626;
        color: #ffffff;
    }

    /* Alert Banner */
    .alert-banner {
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        gap: 14px;
        align-items: flex-start;
    }

    .alert-banner.info {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #1e40af;
    }

    .alert-banner.success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
    }

    .alert-banner.warning {
        background: #fffbeb;
        border: 1px solid #fde68a;
        color: #92400e;
    }

    .alert-banner.danger {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .alert-banner i {
        font-size: 24px;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .alert-content h4 {
        margin: 0 0 4px 0;
        font-size: 14.5px;
        font-weight: 700;
    }

    .alert-content p {
        margin: 0;
        font-size: 13px;
        line-height: 1.5;
    }

    /* Grid Layout */
    .detail-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
    }

    @media (max-width: 992px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }

    .detail-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .detail-card-header {
        padding: 18px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fafafa;
    }

    .detail-card-header h3 {
        font-size: 15px;
        font-weight: 700;
        color: #082052;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .detail-card-body {
        padding: 24px;
    }

    /* Info Table */
    .info-list {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px 24px;
    }

    @media (max-width: 640px) {
        .info-list {
            grid-template-columns: 1fr;
        }
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .info-label {
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
    }

    .info-value {
        font-size: 14.5px;
        font-weight: 600;
        color: #0f172a;
    }

    .info-value.highlight {
        color: #024b86;
        font-size: 17px;
        font-weight: 800;
    }

    /* Badges */
    .badge-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        width: fit-content;
    }

    .badge-status-pill.diajukan { background: #e0f2fe; color: #0369a1; }
    .badge-status-pill.disetujui_umum { background: #ffedd5; color: #c2410c; }
    .badge-status-pill.disetujui_ppk { background: #e0e7ff; color: #3730a3; }
    .badge-status-pill.disetujui_ppspm { background: #dcfce7; color: #15803d; }
    .badge-status-pill.menunggu_otp_kpa { background: #dbeafe; color: #1d4ed8; }
    .badge-status-pill.selesai { background: #dcfce7; color: #166534; }
    .badge-status-pill.revisi { background: #fee2e2; color: #b91c1c; }
    .badge-status-pill.draft { background: #f1f5f9; color: #475569; }

    /* Checklist Documents */
    .doc-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 8px;
    }

    @media (max-width: 640px) {
        .doc-grid {
            grid-template-columns: 1fr;
        }
    }

    .doc-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border-radius: 10px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        font-size: 13px;
        font-weight: 500;
    }

    .doc-item.checked {
        background: #f0fdf4;
        border-color: #bbf7d0;
        color: #166534;
        font-weight: 600;
    }

    .doc-item i {
        font-size: 16px;
    }

    /* Timeline Log */
    .timeline {
        position: relative;
        padding-left: 28px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 6px;
        bottom: 6px;
        width: 2px;
        background: #e2e8f0;
    }

    .timeline-item {
        position: relative;
    }

    .timeline-dot {
        position: absolute;
        left: -28px;
        top: 2px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #ffffff;
        border: 2px solid #024b86;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        color: #024b86;
    }

    .timeline-item:first-child .timeline-dot {
        background: #024b86;
        color: #ffffff;
    }

    .timeline-content {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 16px;
    }

    .timeline-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 4px;
        flex-wrap: wrap;
        gap: 6px;
    }

    .timeline-title {
        font-size: 13.5px;
        font-weight: 700;
        color: #0f172a;
    }

    .timeline-time {
        font-size: 11.5px;
        color: #64748b;
    }

    .timeline-body {
        font-size: 12.5px;
        color: #475569;
        line-height: 1.5;
        margin-top: 4px;
    }

    .timeline-user {
        font-size: 11.5px;
        font-weight: 600;
        color: #024b86;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-top: 6px;
    }
</style>
@endpush

<div class="detail-container">

    {{-- HEADER CARD --}}
    <div class="detail-header-card">
        <div class="header-info">
            <h2>
                <i class="bi bi-file-earmark-text-fill" style="color: #024b86;"></i>
                {{ $spj->nomor_spj ?: 'SPJ Tanpa Nomor' }}
            </h2>
            <p>
                Dibuat oleh: <strong>{{ $spj->user->name ?? 'Pengguna BPS' }}</strong> 
                &bull; Tanggal Input: {{ $spj->created_at->format('d F Y, H:i') }}
            </p>
        </div>

        <div class="header-actions">
            <a href="{{ route('admin.spj.edit', $spj->id) }}" class="btn-action-detail btn-action-primary">
                <i class="bi bi-pencil-square"></i> Edit Data
            </a>

            <button type="button" onclick="window.print()" class="btn-action-detail btn-action-secondary">
                <i class="bi bi-printer"></i> Cetak
            </button>

            <form action="{{ route('admin.spj.destroy', $spj->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data SPJ ini? Tindakan ini tidak dapat dibatalkan.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-action-detail btn-action-danger">
                    <i class="bi bi-trash3"></i> Hapus
                </button>
            </form>

            <a href="{{ route('admin.spj.index') }}" class="btn-action-detail btn-action-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    {{-- STATUS SPECIFIC BANNERS --}}
    @if(str_contains((string) $spj->status, 'revisi') || $spj->status === 'dikembalikan')
        <div class="alert-banner danger">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div class="alert-content">
                <h4>Memerlukan Perbaikan / Revisi</h4>
                <p>
                    <strong>Catatan Revisi:</strong> {{ $spj->catatan_revisi ?: 'Terdapat berkas atau data yang perlu disesuaikan sebelum dapat diproses kembali.' }}
                </p>
            </div>
        </div>
    @elseif($spj->status === 'menunggu_otp_kpa')
        <div class="alert-banner info">
            <i class="bi bi-key-fill"></i>
            <div class="alert-content">
                <h4>Menunggu Otorisasi OTP QLOLA BRI oleh KPA</h4>
                <p>
                    Transaksi pembayaran telah diinput ke sistem perbankan BRI QLOLA oleh Bendahara Pengeluaran dan saat ini sedang menunggu otorisasi OTP dari Kuasa Pengguna Anggaran (KPA) agar dana dapat dicairkan.
                </p>
            </div>
        </div>
    @elseif($spj->status === 'selesai')
        <div class="alert-banner success">
            <i class="bi bi-check-circle-fill"></i>
            <div class="alert-content">
                <h4>Pencairan Dana Selesai</h4>
                <p>
                    SPJ telah dinyatakan selesai dan dana tagihan telah dicairkan oleh Bendahara.
                    @if($spj->bukti_transfer)
                        &bull; Bukti / No. Referensi Transfer: <strong>{{ $spj->bukti_transfer }}</strong>
                    @endif
                    @if($spj->diselesaikan_at)
                        &bull; Diselesaikan pada: {{ $spj->diselesaikan_at->format('d/m/Y H:i') }}
                    @endif
                </p>
            </div>
        </div>
    @endif

    {{-- MAIN GRID --}}
    <div class="detail-grid">

        {{-- LEFT COLUMN: DETAIL & DOKUMEN --}}
        <div style="display: flex; flex-direction: column; gap: 20px;">

            {{-- DATA UTAMA SPJ --}}
            <div class="detail-card">
                <div class="detail-card-header">
                    <h3><i class="bi bi-info-circle-fill" style="color: #024b86;"></i> Rincian Berkas SPJ</h3>
                    @php
                        $badgeClass = 'draft';
                        if ($spj->status === 'diajukan') $badgeClass = 'diajukan';
                        elseif ($spj->status === 'disetujui_umum') $badgeClass = 'disetujui_umum';
                        elseif ($spj->status === 'disetujui_ppk') $badgeClass = 'disetujui_ppk';
                        elseif ($spj->status === 'disetujui_ppspm') $badgeClass = 'disetujui_ppspm';
                        elseif ($spj->status === 'menunggu_otp_kpa') $badgeClass = 'menunggu_otp_kpa';
                        elseif ($spj->status === 'selesai') $badgeClass = 'selesai';
                        elseif (str_contains((string) $spj->status, 'revisi')) $badgeClass = 'revisi';
                    @endphp
                    <span class="badge-status-pill {{ $badgeClass }}">
                        <i class="bi bi-circle-fill" style="font-size: 7px;"></i>
                        {{ strtoupper(str_replace('_', ' ', $spj->status)) }}
                    </span>
                </div>

                <div class="detail-card-body">
                    <div class="info-list">
                        <div class="info-item">
                            <span class="info-label">Nama Kegiatan / Uraian</span>
                            <span class="info-value" style="font-size: 15.5px; color: #082052;">{{ $spj->kegiatan }}</span>
                        </div>

                        <div class="info-item">
                            <span class="info-label">Jumlah Anggaran</span>
                            <span class="info-value highlight">Rp {{ number_format($spj->nilai, 0, ',', '.') }}</span>
                        </div>

                        <div class="info-item">
                            <span class="info-label">Nomor SPJ</span>
                            <span class="info-value">{{ $spj->nomor_spj ?: '-' }}</span>
                        </div>

                        <div class="info-item">
                            <span class="info-label">Tanggal Kegiatan</span>
                            <span class="info-value">{{ $spj->tanggal ? \Carbon\Carbon::parse($spj->tanggal)->locale('id')->translatedFormat('d F Y') : '-' }}</span>
                        </div>

                        <div class="info-item">
                            <span class="info-label">Pengusul / Penanggung Jawab</span>
                            <span class="info-value">{{ $spj->user->name ?? '-' }} ({{ ucfirst($spj->user->role ?? 'Teknis') }})</span>
                        </div>

                        <div class="info-item">
                            <span class="info-label">No. WhatsApp Konfirmasi</span>
                            <span class="info-value">{{ $spj->no_wa ?: '-' }}</span>
                        </div>

                        @if($spj->nomor_spm)
                        <div class="info-item">
                            <span class="info-label">Nomor SPM</span>
                            <span class="info-value" style="color: #024b86; font-weight: 700;">{{ $spj->nomor_spm }}</span>
                        </div>
                        @endif

                        @if($spj->bukti_transfer)
                        <div class="info-item">
                            <span class="info-label">Referensi / Bukti Pencairan</span>
                            <span class="info-value" style="color: #15803d; font-weight: 700;">{{ $spj->bukti_transfer }}</span>
                        </div>
                        @endif

                        <div class="info-item" style="grid-column: 1 / -1;">
                            <span class="info-label">Keterangan / Catatan Tambahan</span>
                            <span class="info-value" style="font-weight: 400; color: #475569; line-height: 1.5;">
                                {{ $spj->keterangan ?: 'Tidak ada keterangan tambahan.' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KELENGKAPAN BERKAS ADMINISTRASI --}}
            <div class="detail-card">
                <div class="detail-card-header">
                    <h3><i class="bi bi-folder-check" style="color: #024b86;"></i> Kelengkapan Berkas Fisik</h3>
                    <span style="font-size: 12px; color: #64748b;">
                        {{ count(array_filter($dokumen, fn($d) => !empty($d['disiapkan']))) }} dari {{ count($dokumen) }} disiapkan
                    </span>
                </div>

                <div class="detail-card-body">
                    @if(count($dokumen) > 0)
                        <div class="doc-grid">
                            @foreach($dokumen as $doc)
                                @php $isReady = !empty($doc['disiapkan']); @endphp
                                <div class="doc-item {{ $isReady ? 'checked' : '' }}">
                                    <i class="bi {{ $isReady ? 'bi-check-circle-fill text-success' : 'bi-dash-circle text-muted' }}"></i>
                                    <span>{{ $doc['nama'] ?? 'Dokumen' }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p style="color: #94a3b8; font-size: 13px; font-style: italic; margin: 0;">
                            Belum ada checklist berkas yang ditentukan untuk SPJ ini.
                        </p>
                    @endif
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN: JEJAK AUDIT & RIWAYAT PROSES --}}
        <div>
            <div class="detail-card" style="height: 100%;">
                <div class="detail-card-header">
                    <h3><i class="bi bi-clock-history" style="color: #024b86;"></i> Jejak Riwayat & Audit</h3>
                    <span style="font-size: 12px; font-weight: 700; color: #024b86;">
                        {{ $spj->histories->count() }} Entri
                    </span>
                </div>

                <div class="detail-card-body">
                    @if($spj->histories->count() > 0)
                        <div class="timeline">
                            @foreach($spj->histories as $history)
                                <div class="timeline-item">
                                    <div class="timeline-dot">
                                        <i class="bi bi-check"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <div class="timeline-header">
                                            <span class="timeline-title">{{ $history->aksi }}</span>
                                            <span class="timeline-time">{{ $history->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="timeline-body">
                                            {{ $history->keterangan ?: 'Perubahan status proses SPJ.' }}
                                        </div>
                                        <div class="timeline-user">
                                            <i class="bi bi-person-fill"></i>
                                            {{ $history->user->name ?? 'Sistem' }} &bull; {{ $history->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; padding: 30px 10px; color: #94a3b8;">
                            <i class="bi bi-journal-x" style="font-size: 32px; display: block; margin-bottom: 8px;"></i>
                            <p style="margin: 0; font-size: 13px;">Belum ada catatan riwayat untuk SPJ ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
