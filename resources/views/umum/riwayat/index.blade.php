@extends('layouts.umum')

@php
$title = 'Riwayat Pemeriksaan SPJ';
$subtitle = 'Arsip seluruh SPJ yang pernah Anda verifikasi (diterima atau ditolak).';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/umum/riwayat.css'])
@endpush

<div style="padding-top: 8px;">
    <!-- Page Heading -->
    <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2 style="font-size: 24px; font-weight: 700; color: var(--dash-text-dark); margin: 0 0 4px 0; letter-spacing: -0.01em;">Riwayat Pemeriksaan</h2>
            <p style="color: var(--dash-text-muted); font-size: 14px; margin: 0;">Seluruh berkas dokumen yang sudah melewati meja verifikasi Umum.</p>
        </div>
        <div style="font-size: 13px; font-weight: 600; color: var(--dash-text-muted); background-color: var(--dash-card-bg); padding: 8px 16px; border-radius: 9999px; border: 1px solid var(--dash-border);">
            Total Arsip: <span style="color: var(--dash-text-dark); font-weight: 700;">{{ $riwayatSpjs->count() }}</span> Dokumen
        </div>
    </div>

    <!-- Main Table Panel -->
    <div class="panel-card">
        <!-- Filter Bar -->
        <div class="filter-box">
            <form action="{{ route('umum.riwayat.index') }}" method="GET" class="filter-grid">
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
                        <a href="{{ route('umum.riwayat.index') }}" class="btn-dash-reset">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table Container -->
        <div class="modern-table-wrapper">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>Terakhir Diupdate</th>
                        <th>Pengaju (Teknis)</th>
                        <th>Nama Kegiatan</th>
                        <th>Status Saat Ini</th>
                        <th>Catatan Revisi</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatSpjs as $spj)
                    <tr>
                        <td style="color: var(--dash-text-muted); font-size: 13px;">
                            {{ $spj->updated_at->format('d M Y, H:i') }}
                        </td>
                        <td>
                            <div style="font-weight: 600; color: var(--dash-text-dark);">{{ $spj->user->name ?? 'Staf Teknis' }}</div>
                            <div style="font-size: 11px; color: var(--dash-text-muted);">{{ $spj->user->seksi ?? 'Seksi Teknis' }}</div>
                        </td>
                        <td>
                            <a href="{{ route('umum.spj.show', $spj->id) }}" style="color: #0284c7; font-weight: 600; text-decoration: none; line-height: 1.4; display: block; max-width: 280px; white-space: normal;" onmouseover="this.style.textDecoration='underline';" onmouseout="this.style.textDecoration='none';">
                                {{ $spj->kegiatan }}
                            </a>
                        </td>
                        <td>
                            @php
                                if(str_contains($spj->status, 'revisi')) {
                                    $badgeClass = 'dash-badge-revisi';
                                    $statusText = 'Dikembalikan (' . strtoupper(str_replace('revisi_', '', $spj->status)) . ')';
                                } elseif($spj->status == 'selesai') {
                                    $badgeClass = 'dash-badge-selesai';
                                    $statusText = 'Selesai (Arsip)';
                                } elseif($spj->status == 'disetujui_ppspm') {
                                    $badgeClass = 'dash-badge-disetujui_ppspm';
                                    $statusText = 'Di Bendahara';
                                } elseif($spj->status == 'disetujui_ppk') {
                                    $badgeClass = 'dash-badge-disetujui_ppk';
                                    $statusText = 'Diteruskan PPSPM';
                                } else {
                                    $badgeClass = 'dash-badge-disetujui_umum';
                                    $statusText = 'Disetujui Lanjut';
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
                                <div style="color: #ef4444; font-size: 12px; line-height: 1.4; max-width: 220px; white-space: normal; background-color: var(--dash-peach-bg); border: 1px solid var(--dash-peach-border); padding: 6px 10px; border-radius: 6px;">
                                    {{ \Illuminate\Support\Str::limit($spj->catatan_revisi, 60) }}
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
                                <form action="{{ route('umum.riwayat.destroy', $spj->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus permanen riwayat pengajuan ini? Tindakan ini tidak dapat dibatalkan.');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn-table-delete" 
                                            title="Hapus Permanen">
                                        <ion-icon name="trash-outline"></ion-icon>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="dash-empty-state">
                                <ion-icon name="folder-open-outline" class="dash-empty-icon"></ion-icon>
                                <p style="margin: 0; font-weight: 500;">Belum ada riwayat pemeriksaan SPJ.</p>
                                <p style="margin: 4px 0 0 0; font-size: 12px; opacity: 0.8;">Dokumen yang sudah diproses atau disetujui akan diarsipkan di sini.</p>
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
