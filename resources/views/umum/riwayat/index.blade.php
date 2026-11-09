@extends('layouts.umum')

@php
$title = 'Riwayat Pemeriksaan SPJ';
$subtitle = 'Arsip & pemantauan seluruh berkas SPJ yang masuk ke alur verifikasi Subbagian Umum.';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/umum/riwayat.css'])
@endpush

<div style="padding-top: 8px;">
    <!-- Page Heading -->
    <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2 style="font-size: 24px; font-weight: 700; color: var(--dash-text-dark); margin: 0 0 4px 0; letter-spacing: -0.01em;">Riwayat Pemeriksaan SPJ</h2>
            <p style="color: var(--dash-text-muted); font-size: 14px; margin: 0;">Seluruh berkas dokumen pengajuan SPJ beserta jejak pemeriksaan dan tindak lanjut perbaikan.</p>
        </div>
        
        <!-- Summary Stat Pills -->
        <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
            <div style="font-size: 12.5px; font-weight: 600; color: var(--dash-text-muted); background-color: var(--dash-card-bg); padding: 7px 14px; border-radius: 9999px; border: 1px solid var(--dash-border);">
                Total Berkas: <strong style="color: var(--dash-text-dark);">{{ $stats['total'] ?? $riwayatSpjs->count() }}</strong>
            </div>
            @if(!empty($stats['revisi']) && $stats['revisi'] > 0)
                <div style="font-size: 12.5px; font-weight: 600; color: #ef4444; background-color: var(--dash-peach-bg); padding: 7px 14px; border-radius: 9999px; border: 1px solid var(--dash-peach-border);">
                    Perlu Revisi: <strong>{{ $stats['revisi'] }}</strong>
                </div>
            @endif
            @if(!empty($stats['perbaikan']) && $stats['perbaikan'] > 0)
                <div style="font-size: 12.5px; font-weight: 600; color: #0891b2; background-color: #ecfeff; padding: 7px 14px; border-radius: 9999px; border: 1px solid #a5f3fc;">
                    Perbaikan Masuk: <strong>{{ $stats['perbaikan'] }}</strong>
                </div>
            @endif
            <div style="font-size: 12.5px; font-weight: 600; color: #16a34a; background-color: #f0fdf4; padding: 7px 14px; border-radius: 9999px; border: 1px solid #bbf7d0;">
                Selesai / Cair: <strong>{{ $stats['selesai'] ?? 0 }}</strong>
            </div>
        </div>
    </div>

    <!-- Main Table Panel -->
    <div class="panel-card">
        <!-- Filter Bar -->
        <div class="filter-box">
            <form action="{{ route('umum.riwayat.index') }}" method="GET" class="filter-grid" style="align-items: flex-end;">
                <div class="filter-field" style="flex: 2; min-width: 220px;">
                    <label class="filter-label">Cari Pengaju / Kegiatan / No. SPJ</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik kata kunci pencarian..." class="dash-input">
                </div>
                
                <div class="filter-field" style="flex: 1.2; min-width: 180px;">
                    <label class="filter-label">Status Berkas</label>
                    <select name="status" class="dash-input" style="height: 42px;">
                        <option value="semua" {{ request('status') === 'semua' || !request('status') ? 'selected' : '' }}>Semua Status Berkas</option>
                        <option value="revisi" {{ request('status') === 'revisi' ? 'selected' : '' }}>⚠️ Perlu Revisi / Dikembalikan ({{ $stats['revisi'] ?? 0 }})</option>
                        <option value="perbaikan" {{ request('status') === 'perbaikan' ? 'selected' : '' }}>✔ Perbaikan Telah Diajukan ({{ $stats['perbaikan'] ?? 0 }})</option>
                        <option value="diajukan" {{ request('status') === 'diajukan' ? 'selected' : '' }}>Menunggu Verifikasi / Fisik</option>
                        <option value="proses" {{ request('status') === 'proses' ? 'selected' : '' }}>Sedang Diproses (PPK / PPSPM / Bendahara)</option>
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai / Cair (Arsip)</option>
                    </select>
                </div>

                <div class="filter-field" style="min-width: 140px;">
                    <label class="filter-label">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="dash-input">
                </div>
                
                <div class="filter-field" style="min-width: 140px;">
                    <label class="filter-label">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="dash-input">
                </div>

                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="btn-dash-primary" style="height: 42px;">
                        <ion-icon name="funnel-outline" style="font-size: 16px;"></ion-icon> Filter
                    </button>
                    @if(request()->hasAny(['search', 'status', 'start_date', 'end_date']))
                        <a href="{{ route('umum.riwayat.index') }}" class="btn-dash-reset" style="height: 42px; display: inline-flex; align-items: center;">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table Container -->
        <div class="modern-table-wrapper">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th style="width: 140px;">Terakhir Diupdate</th>
                        <th style="width: 180px;">Pengaju (Teknis)</th>
                        <th>Nama Kegiatan & Anggaran</th>
                        <th style="width: 220px;">Status Saat Ini</th>
                        <th style="width: 260px;">Catatan Revisi / Perbaikan</th>
                        <th style="text-align: center; width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatSpjs as $spj)
                    <tr>
                        <td style="color: var(--dash-text-muted); font-size: 13px; white-space: nowrap;">
                            <div style="font-weight: 600; color: var(--dash-text-dark);">{{ $spj->updated_at->locale('id')->translatedFormat('d M Y') }}</div>
                            <div style="font-size: 11px; color: var(--dash-text-muted);">Pukul {{ $spj->updated_at->format('H:i') }} WITA</div>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: var(--dash-text-dark);">{{ $spj->user->name ?? 'Staf Teknis' }}</div>
                            <div style="font-size: 11px; color: var(--dash-text-muted);">{{ $spj->seksi_label }}</div>
                        </td>
                        <td>
                            <a href="{{ route('umum.spj.show', $spj->id) }}" style="color: #0284c7; font-weight: 600; text-decoration: none; line-height: 1.4; display: block; max-width: 320px; white-space: normal;" onmouseover="this.style.textDecoration='underline';" onmouseout="this.style.textDecoration='none';">
                                {{ $spj->kegiatan }}
                            </a>
                            <div style="font-size: 11.5px; color: var(--dash-text-muted); margin-top: 3px; display: flex; align-items: center; gap: 8px;">
                                @if($spj->nomor_spj)
                                    <span>No: <strong style="color: var(--dash-text-dark);">{{ $spj->nomor_spj }}</strong></span>
                                    <span>&bull;</span>
                                @endif
                                <span style="font-weight: 700; color: #059669;">Rp {{ number_format($spj->nilai, 0, ',', '.') }}</span>
                            </div>
                        </td>
                        <td>
                            @php
                                $badgeClass = 'dash-badge-diajukan';
                                $statusText = 'Menunggu Verifikasi';

                                if (str_contains($spj->status, 'revisi')) {
                                    $badgeClass = 'dash-badge-revisi';
                                    $statusText = 'Perlu Revisi (' . strtoupper(str_replace('revisi_', '', $spj->status)) . ')';
                                } elseif ($spj->status === 'diajukan') {
                                    if ($spj->was_revisi) {
                                        $badgeClass = 'dash-badge-perbaikan';
                                        $statusText = 'Perbaikan Diajukan (Menunggu Cek)';
                                    } elseif (!$spj->berkas_fisik_diterima_at) {
                                        $badgeClass = 'dash-badge-diajukan';
                                        $statusText = 'Menunggu Berkas Fisik';
                                    } elseif (!$spj->pemeriksaan_dimulai_at) {
                                        $badgeClass = 'dash-badge-disetujui_ppk';
                                        $statusText = 'Fisik Diterima (Siap Cek)';
                                    } else {
                                        $badgeClass = 'dash-badge-diajukan';
                                        $statusText = 'Sedang Diperiksa Umum';
                                    }
                                } elseif ($spj->status === 'disetujui_umum') {
                                    $badgeClass = 'dash-badge-disetujui_umum';
                                    $statusText = 'Disetujui Umum (Lanjut PPK)';
                                } elseif ($spj->status === 'disetujui_ppk') {
                                    $badgeClass = 'dash-badge-disetujui_ppk';
                                    $statusText = 'Disetujui PPK (Lanjut PPSPM)';
                                } elseif ($spj->status === 'disetujui_ppspm') {
                                    $badgeClass = 'dash-badge-disetujui_ppspm';
                                    $statusText = 'Siap Diproses Bendahara (SPM Terbit)';
                                } elseif (in_array($spj->status, ['menunggu_otp_kpa', 'proses_ls'])) {
                                    $badgeClass = 'dash-badge-purple';
                                    $statusText = 'Proses Pembayaran (Bendahara)';
                                } elseif ($spj->status === 'selesai') {
                                    $badgeClass = 'dash-badge-selesai';
                                    $statusText = 'Selesai & Cair (Arsip)';
                                } else {
                                    $badgeClass = 'dash-badge-diajukan';
                                    $statusText = $spj->status_label;
                                }
                            @endphp
                            <span class="dash-badge {{ $badgeClass }}">{{ $statusText }}</span>
                            
                            @if($spj->diajukan_at)
                            @php
                                $diff = \Carbon\Carbon::parse($spj->diajukan_at)->diff($spj->updated_at);
                                $timeStr = '';
                                if($diff->d > 0) $timeStr .= $diff->d . 'h ';
                                if($diff->h > 0) $timeStr .= $diff->h . 'j ';
                                $timeStr .= $diff->i . 'm';
                            @endphp
                            <div style="color: var(--dash-text-muted); font-size: 11px; display: flex; align-items: center; gap: 4px; margin-top: 5px;">
                                <ion-icon name="time-outline" style="font-size: 13px;"></ion-icon> Durasi: {{ $timeStr }}
                            </div>
                            @endif
                        </td>
                        <td>
                            @if(str_contains($spj->status, 'revisi') && $spj->catatan_revisi)
                                <div style="color: #ef4444; font-size: 12px; line-height: 1.4; max-width: 260px; white-space: normal; background-color: var(--dash-peach-bg); border: 1px solid var(--dash-peach-border); padding: 7px 10px; border-radius: 8px;">
                                    <div style="font-weight: 700; font-size: 10.5px; text-transform: uppercase; margin-bottom: 2px; display: flex; align-items: center; gap: 4px;">
                                        <ion-icon name="alert-circle" style="font-size: 14px;"></ion-icon> Perlu Perbaikan:
                                    </div>
                                    {{ \Illuminate\Support\Str::limit($spj->catatan_revisi, 90) }}
                                </div>
                            @elseif($spj->status === 'diajukan' && $spj->was_revisi)
                                <div style="font-size: 12px; line-height: 1.4; max-width: 260px; white-space: normal; background-color: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.28); color: #065f46; padding: 7px 10px; border-radius: 8px;">
                                    <div style="font-weight: 700; font-size: 11px; color: #15803d; margin-bottom: 2px; display: flex; align-items: center; gap: 4px;">
                                        <ion-icon name="checkmark-circle" style="font-size: 15px;"></ion-icon> Telah Diperbaiki Pengaju
                                    </div>
                                    @if($spj->catatan_revisi)
                                        <span style="font-size: 11px; color: var(--dash-text-muted); display: block; margin-top: 2px;">Tindak lanjut: "{{ \Illuminate\Support\Str::limit($spj->catatan_revisi, 50) }}"</span>
                                    @endif
                                </div>
                            @elseif($spj->was_revisi && in_array($spj->status, ['disetujui_umum', 'disetujui_ppk', 'disetujui_ppspm', 'menunggu_otp_kpa', 'proses_ls', 'selesai']))
                                <div style="font-size: 11.5px; color: #10b981; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                    <ion-icon name="checkmark-done-circle" style="font-size: 16px;"></ion-icon> Revisi Selesai & Disetujui
                                </div>
                            @else
                                <span style="color: var(--dash-text-muted); font-size: 13px;">-</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <div style="display: inline-flex; gap: 8px; align-items: center; justify-content: center;">
                                <a href="{{ route('umum.spj.show', $spj->id) }}" 
                                   class="btn-table-view" 
                                   style="background-color: #3b82f6;" 
                                   title="Lihat Detail SPJ">
                                    <ion-icon name="eye-outline"></ion-icon>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="dash-empty-state">
                                <ion-icon name="folder-open-outline" class="dash-empty-icon"></ion-icon>
                                <p style="margin: 0; font-weight: 500;">Belum ada riwayat pemeriksaan SPJ.</p>
                                <p style="margin: 4px 0 0 0; font-size: 12px; opacity: 0.8;">Seluruh dokumen yang diajukan atau sedang diproses akan tampil di sini.</p>
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
