@extends('layouts.teknis')

@php
$title = 'Riwayat SPJ';
$subtitle = 'Lihat riwayat pengajuan SPJ Anda yang telah diajukan.';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/teknis/spj.css'])
@endpush

<div style="padding-top: 4px;">
    <!-- PAGE HEADING -->
    <div class="dashboard-heading">
        <div>
            <h2 class="dashboard-heading-title">Riwayat SPJ</h2>
            <p class="dashboard-heading-subtitle">Daftar dokumen SPJ yang telah diajukan dan sedang atau telah selesai diproses.</p>
        </div>
    </div>

    <!-- MAIN PANEL -->
    <div class="spj-panel">
        <div class="spj-panel-header">
            <div>
                <h2>Data Riwayat SPJ</h2>
                <p>Pantau perkembangan status dan proses verifikasi pengajuan SPJ Anda.</p>
            </div>
            <div class="header-stat-pill">
                Total: <strong>{{ $riwayatSpjs->count() }}</strong> Riwayat
            </div>
        </div>

        <!-- FILTER & SEARCH BAR -->
        <div class="filter-box">
            <form action="{{ route('teknis.riwayat.index') }}" method="GET" class="filter-grid">
                <div class="filter-field" style="flex: 2; min-width: 240px;">
                    <label class="filter-label">Cari Uraian Kegiatan</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik kata kunci kegiatan..." class="dash-input">
                </div>

                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="btn-dash-primary" style="height: 40px; padding: 0 18px;">
                        <ion-icon name="funnel-outline" style="font-size: 16px;"></ion-icon> Filter
                    </button>
                    @if(request()->filled('search'))
                        <a href="{{ route('teknis.riwayat.index') }}" class="btn-dash-reset">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- TABLE CONTAINER -->
        <div class="spj-table-container">
            <table class="spj-table">
                <thead>
                    <tr>
                        <th style="width: 55px; text-align: center;">No</th>
                        <th>Uraian Kegiatan</th>
                        <th>Tanggal Diperbarui</th>
                        <th>Nilai Anggaran</th>
                        <th>Status Akhir</th>
                        <th style="text-align: right; padding-right: 24px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatSpjs as $spj)
                    <tr>
                        <td style="text-align: center; font-weight: 700; color: var(--dash-text-muted);">
                            {{ $loop->iteration }}
                        </td>
                        <td style="max-width: 300px; font-weight: 600;" title="{{ $spj->kegiatan }}">
                            <div style="font-size: 13.5px; color: var(--dash-text-dark); line-height: 1.4;">{{ $spj->kegiatan }}</div>
                            @if($spj->keterangan)
                                <div style="font-size: 11px; color: var(--dash-text-muted); margin-top: 2px;">{{ Str::limit($spj->keterangan, 40) }}</div>
                            @endif
                        </td>
                        <td style="color: var(--dash-text-muted); white-space: nowrap;">
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <ion-icon name="time-outline" style="font-size: 14px;"></ion-icon>
                                <span>{{ $spj->updated_at->format('d M Y, H:i') }}</span>
                            </div>
                        </td>
                        <td style="font-weight: 700; color: var(--dash-text-dark); white-space: nowrap;">
                            Rp {{ number_format($spj->nilai, 0, ',', '.') }}
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
                        <td style="text-align: right; padding-right: 24px; white-space: nowrap;">
                            <div style="display: flex; gap: 8px; justify-content: flex-end; align-items: center;">
                                <a href="{{ route('teknis.spj.show', $spj->id) }}" class="action-btn action-btn-view" title="Lihat Detail SPJ">
                                    <ion-icon name="eye-outline"></ion-icon>
                                    <span>Detail</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 56px 24px; color: var(--dash-text-muted);">
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                                <ion-icon name="time-outline" style="font-size: 46px; opacity: 0.6;"></ion-icon>
                                <span style="font-size: 14px; font-weight: 500;">Belum ada riwayat SPJ yang diajukan.</span>
                                @if(request()->filled('search'))
                                    <a href="{{ route('teknis.riwayat.index') }}" class="btn-dash-reset" style="margin-top: 6px;">Bersihkan Filter Pencarian</a>
                                @endif
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
