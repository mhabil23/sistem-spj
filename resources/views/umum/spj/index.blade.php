@extends('layouts.umum')

@php
$title = 'Kotak Masuk SPJ';
$subtitle = 'Kelola dan periksa seluruh SPJ yang diajukan oleh staf Teknis.';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/umum/riwayat.css'])
@endpush

<div style="padding-top: 8px;">
    <!-- Page Heading -->
    <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2 style="font-size: 24px; font-weight: 700; color: var(--dash-text-dark); margin: 0 0 4px 0; letter-spacing: -0.01em;">Kotak Masuk SPJ</h2>
            <p style="color: var(--dash-text-muted); font-size: 14px; margin: 0;">SPJ yang berstatus 'Menunggu Pemeriksaan' harus segera ditinjau dan diproses.</p>
        </div>
        <div style="font-size: 13px; font-weight: 600; color: var(--dash-text-muted); background-color: var(--dash-card-bg); padding: 8px 16px; border-radius: 9999px; border: 1px solid var(--dash-border);">
            Total: <span style="color: var(--dash-text-dark); font-weight: 700;">{{ $spjs->count() }}</span> Pengajuan
        </div>
    </div>

    <!-- Main Table Panel -->
    <div class="panel-card">
        <!-- Filter Bar -->
        <div class="filter-box">
            <form action="{{ route('umum.spj.index') }}" method="GET" class="filter-grid">
                <div class="filter-field" style="flex: 2; min-width: 240px;">
                    <label class="filter-label">Cari Pengaju / Kegiatan</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik kata kunci pencarian..." class="dash-input">
                </div>
                <div class="filter-field">
                    <label class="filter-label">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="dash-input">
                </div>
                <div class="filter-field">
                    <label class="filter-label">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="dash-input">
                </div>
                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="btn-dash-primary">
                        <ion-icon name="funnel-outline" style="font-size: 16px;"></ion-icon> Filter
                    </button>
                    @if(request()->hasAny(['search', 'start_date', 'end_date']))
                        <a href="{{ route('umum.spj.index') }}" class="btn-dash-reset">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table Container -->
        <div class="modern-table-wrapper">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>Tanggal Pengajuan</th>
                        <th>Nomor SPJ</th>
                        <th>Pengaju (Teknis)</th>
                        <th>Nama Kegiatan</th>
                        <th>Nilai Pengajuan</th>
                        <th>Status / Tahapan</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($spjs as $spj)
                    <tr>
                        <td style="color: var(--dash-text-muted); font-size: 13px;">
                            {{ ($spj->diajukan_at ? \Carbon\Carbon::parse($spj->diajukan_at) : $spj->created_at)->format('d M Y') }}
                        </td>
                        <td style="font-weight: 600; color: var(--dash-text-dark);">
                            {{ $spj->nomor_spj ?: '-' }}
                        </td>
                        <td>
                            <div style="font-weight: 600; color: var(--dash-text-dark);">{{ $spj->user->name ?? 'Staf Teknis' }}</div>
                            <div style="font-size: 11px; color: var(--dash-text-muted);">{{ $spj->user->seksi ?? 'Seksi Teknis' }}</div>
                        </td>
                        <td>
                            <div style="font-weight: 500; max-width: 280px; white-space: normal; line-height: 1.4; color: var(--dash-text-dark);">
                                {{ $spj->kegiatan }}
                            </div>
                        </td>
                        <td style="font-weight: 700; color: #10b981; font-size: 13px;">
                            Rp {{ number_format($spj->nilai, 0, ',', '.') }}
                        </td>
                        <td>
                            @php
                                $badgeClass = 'dash-badge-diajukan';
                                $statusText = 'Menunggu';
                                $btnBg = 'var(--dash-btn-primary-bg)';
                                $btnTitle = 'Detail';

                                if($spj->status == 'diajukan') {
                                    $badgeClass = 'dash-badge-diajukan';
                                    $statusText = 'Menunggu Pemeriksaan';
                                    $btnBg = 'var(--dash-btn-primary-bg)';
                                    $btnTitle = 'Periksa Dokumen';
                                } elseif($spj->status == 'disetujui_umum') {
                                    $badgeClass = 'dash-badge-disetujui_umum';
                                    $statusText = 'Diteruskan ke PPK';
                                    $btnBg = '#0284c7';
                                    $btnTitle = 'Input Keputusan PPK';
                                } elseif($spj->status == 'disetujui_ppk') {
                                    $badgeClass = 'dash-badge-disetujui_ppk';
                                    $statusText = 'Diteruskan ke PPSPM';
                                    $btnBg = '#8b5cf6';
                                    $btnTitle = 'Input Keputusan PPSPM';
                                } elseif($spj->status == 'disetujui_ppspm') {
                                    $badgeClass = 'dash-badge-disetujui_ppspm';
                                    $statusText = 'Di Bendahara';
                                    $btnBg = 'var(--dash-btn-primary-bg)';
                                    $btnTitle = 'Lihat Status';
                                } elseif(str_contains($spj->status, 'revisi')) {
                                    $badgeClass = 'dash-badge-revisi';
                                    $statusText = 'Dikembalikan (' . strtoupper(str_replace('revisi_', '', $spj->status)) . ')';
                                    $btnBg = '#ef4444';
                                    $btnTitle = 'Lihat Catatan Revisi';
                                } elseif($spj->status == 'selesai') {
                                    $badgeClass = 'dash-badge-selesai';
                                    $statusText = 'Selesai (Cair)';
                                    $btnBg = '#0284c7';
                                    $btnTitle = 'Lihat Arsip';
                                } else {
                                    $badgeClass = 'dash-badge-diajukan';
                                    $statusText = strtoupper(str_replace('_', ' ', $spj->status));
                                }
                            @endphp
                            <span class="dash-badge {{ $badgeClass }}">{{ $statusText }}</span>
                        </td>
                        <td style="text-align: center;">
                            <a href="{{ route('umum.spj.show', $spj->id) }}" 
                               class="btn-table-view" 
                               style="background-color: {{ $btnBg }};" 
                               title="{{ $btnTitle }}">
                                <ion-icon name="eye-outline"></ion-icon>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="dash-empty-state">
                                <ion-icon name="folder-open-outline" class="dash-empty-icon"></ion-icon>
                                <p style="margin: 0; font-weight: 500;">Tidak ada antrean SPJ untuk ditampilkan.</p>
                                <p style="margin: 4px 0 0 0; font-size: 12px; opacity: 0.8;">SPJ baru yang diajukan oleh staf Teknis akan muncul di tabel ini.</p>
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
