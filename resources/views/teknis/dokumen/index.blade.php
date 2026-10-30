@extends('layouts.teknis')

@php
$title = 'Dokumen SPJ';
$subtitle = 'Kelola file dokumen pendukung (PDF/Gambar) untuk SPJ Anda.';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/teknis/spj.css'])
@endpush

<div style="padding-top: 4px;">
    <!-- PAGE HEADING -->
    <div class="dashboard-heading">
        <div>
            <h2 class="dashboard-heading-title">Kelengkapan Dokumen SPJ</h2>
            <p class="dashboard-heading-subtitle">Unggah bukti kuitansi, nota, atau berkas pendukung digital untuk SPJ Anda.</p>
        </div>
    </div>

    <!-- MAIN PANEL -->
    <div class="spj-panel">
        <div class="spj-panel-header">
            <div>
                <h2>Data Berkas Dokumen</h2>
                <p>Daftar pengajuan SPJ dan status lampiran file pendukung.</p>
            </div>
            <div class="header-stat-pill">
                Total: <strong>{{ $spjs->count() }}</strong> Berkas
            </div>
        </div>

        <div class="spj-table-container">
            <table class="spj-table">
                <thead>
                    <tr>
                        <th style="width: 55px; text-align: center;">No</th>
                        <th>Uraian Kegiatan</th>
                        <th>Status SPJ</th>
                        <th>Dokumen Terlampir</th>
                        <th style="text-align: right; padding-right: 24px;">Aksi Unggah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($spjs as $spj)
                    <tr>
                        <td style="text-align: center; font-weight: 700; color: var(--dash-text-muted);">
                            {{ $loop->iteration }}
                        </td>
                        <td style="max-width: 280px; font-weight: 600;" title="{{ $spj->kegiatan }}">
                            <div style="font-size: 13.5px; color: var(--dash-text-dark); line-height: 1.4;">{{ $spj->kegiatan }}</div>
                            <div style="font-size: 11px; color: var(--dash-text-muted); margin-top: 2px;">
                                Rp {{ number_format($spj->nilai, 0, ',', '.') }} &bull; {{ $spj->tanggal->format('d M Y') }}
                            </div>
                        </td>
                        <td>
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
                            <span class="dash-badge {{ $badgeClass }}">
                                <span class="badge-dot"></span>
                                {{ $spj->status_label }}
                            </span>
                        </td>
                        <td>
                            @if($spj->file_lampiran_url)
                                <a href="{{ $spj->file_lampiran_url }}" target="_blank" class="action-btn action-btn-view" style="display: inline-flex; align-items: center; gap: 6px; text-decoration: none;">
                                    <ion-icon name="document-attach-outline" style="font-size: 16px;"></ion-icon>
                                    <span>Lihat File</span>
                                </a>
                            @else
                                <span style="color: var(--dash-text-muted); font-size: 12px; font-style: italic; display: inline-flex; align-items: center; gap: 4px;">
                                    <ion-icon name="alert-circle-outline"></ion-icon>
                                    Belum ada file
                                </span>
                            @endif
                        </td>
                        <td style="text-align: right; padding-right: 24px;">
                            <form action="{{ route('teknis.dokumen.upload', $spj->id) }}" method="POST" enctype="multipart/form-data" style="display: inline-flex; gap: 8px; align-items: center; justify-content: flex-end;">
                                @csrf
                                <input type="file" name="dokumen_file" required accept=".pdf,.jpg,.jpeg,.png" style="font-size: 12px; max-width: 190px; padding: 6px 10px; border-radius: 8px; border: 1px dashed var(--dash-border); background: var(--dash-input-bg); color: var(--dash-text-dark);">
                                <button type="submit" class="btn-dash-primary" style="height: 36px; padding: 0 14px; font-size: 12.5px;">
                                    <ion-icon name="cloud-upload-outline" style="font-size: 16px;"></ion-icon> Upload
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 56px 24px; color: var(--dash-text-muted);">
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                                <ion-icon name="folder-open-outline" style="font-size: 46px; opacity: 0.6;"></ion-icon>
                                <span style="font-size: 14px; font-weight: 500;">Belum ada SPJ yang perlu dilampirkan dokumen.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
