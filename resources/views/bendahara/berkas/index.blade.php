@extends('layouts.bendahara')

@php
$title = 'Verifikasi & Kelengkapan Berkas SPJ - Bendahara';
@endphp

@push('styles')
    @vite(['resources/css/bendahara/spj.css'])
    <style>
        .badge-teknis-check {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }

        .badge-bendahara-pending {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            background: #fffbeb;
            color: #b45309;
            border: 1px solid #fde68a;
        }

        .badge-bendahara-done {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        .bnd-filter-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            color: var(--bnd-slate-600);
            background: #ffffff;
            border: 1px solid var(--bnd-slate-200);
            transition: var(--bnd-transition);
        }

        .bnd-filter-pill:hover {
            background: var(--bnd-slate-50);
            color: var(--bnd-slate-900);
        }

        .bnd-filter-pill.active {
            background: #0284c7;
            color: #ffffff;
            border-color: #0284c7;
            box-shadow: 0 2px 8px rgba(2, 132, 199, 0.25);
        }

        .bnd-filter-pill .count-badge {
            background: rgba(0, 0, 0, 0.08);
            padding: 2px 7px;
            border-radius: 999px;
            font-size: 11px;
        }

        .bnd-filter-pill.active .count-badge {
            background: rgba(255, 255, 255, 0.25);
            color: #ffffff;
        }
    </style>
@endpush

@section('content')
<div class="bnd-header-wrapper">
    <!-- BREADCRUMB -->
    <div class="bnd-breadcrumb">
        <a href="{{ route('bendahara.dashboard') }}">
            <ion-icon name="home-outline"></ion-icon> Dashboard
        </a>
        <ion-icon name="chevron-forward-outline"></ion-icon>
        <span>Verifikasi Berkas SPJ</span>
    </div>

    <!-- MAIN PAGE HEADER -->
    <div class="bnd-page-header">
        <div class="bnd-header-title-box">
            <h1>
                <span>Verifikasi Kelengkapan Berkas SPJ</span>
                <span class="bnd-badge bnd-badge-cyan">
                    <span class="bnd-badge-dot"></span>
                    Perpajakan &amp; Syarat Bendahara
                </span>
            </h1>
            <p style="color: var(--bnd-slate-500); font-size: 13.5px; margin: 4px 0 0 0;">
                Lihat berkas fisik yang telah dicentang staf teknis di awal, dan lengkapi centang berkas perpajakan serta syarat pembayaran khusus Bendahara.
            </p>
        </div>

        <div class="bnd-header-actions">
            <a href="{{ route('bendahara.spj.index') }}" class="bnd-btn bnd-btn-back">
                <ion-icon name="file-tray-full-outline"></ion-icon>
                <span>Kotak Masuk SPJ</span>
            </a>
        </div>
    </div>
</div>

<!-- STATS GRID -->
<div class="bnd-stats-grid" style="margin-bottom: 24px;">
    <!-- STAT 1: TOTAL MASUK BENDAHARA -->
    <div class="bnd-stat-card primary">
        <div class="bnd-stat-icon blue">
            <ion-icon name="documents-outline"></ion-icon>
        </div>
        <div class="bnd-stat-info">
            <div class="bnd-stat-label">Total SPJ di Bendahara</div>
            <div class="bnd-stat-value">{{ $stats['total'] }}</div>
            <div class="bnd-stat-sub">
                <span>Seluruh berkas masuk meja Bendahara</span>
            </div>
        </div>
    </div>

    <!-- STAT 2: SIAP DIPROSES (SPM TERBIT) -->
    <div class="bnd-stat-card">
        <div class="bnd-stat-icon amber">
            <ion-icon name="time-outline"></ion-icon>
        </div>
        <div class="bnd-stat-info">
            <div class="bnd-stat-label">Siap Diproses (SPM Disetujui)</div>
            <div class="bnd-stat-value" style="color: #d97706;">{{ $stats['siap_cair'] }}</div>
            <div class="bnd-stat-sub">
                <span>Perlu verifikasi &amp; pilih opsi bayar</span>
            </div>
        </div>
    </div>

    <!-- STAT 3: DALAM PROSES BANK -->
    <div class="bnd-stat-card">
        <div class="bnd-stat-icon violet">
            <ion-icon name="card-outline"></ion-icon>
        </div>
        <div class="bnd-stat-info">
            <div class="bnd-stat-label">Dalam Proses Bank (OTP / LS)</div>
            <div class="bnd-stat-value" style="color: #7c3aed;">{{ $stats['proses_bank'] }}</div>
            <div class="bnd-stat-sub">
                <span>Menunggu OTP QLOLA &amp; transfer bank</span>
            </div>
        </div>
    </div>

    <!-- STAT 4: BERKAS BENDAHARA LENGKAP -->
    <div class="bnd-stat-card success">
        <div class="bnd-stat-icon emerald">
            <ion-icon name="checkmark-done-circle-outline"></ion-icon>
        </div>
        <div class="bnd-stat-info">
            <div class="bnd-stat-label">Berkas Bendahara Lengkap</div>
            <div class="bnd-stat-value" style="color: #059669;">{{ $stats['lengkap'] }}</div>
            <div class="bnd-stat-sub">
                <span>Semua syarat pajak/bayar telah dicentang</span>
            </div>
        </div>
    </div>
</div>

<!-- FILTER & SEARCH PANEL -->
<div class="bnd-card" style="margin-bottom: 24px; padding: 18px 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
            <a href="{{ route('bendahara.berkas.index') }}" class="bnd-filter-pill {{ !request()->filled('status') && !request()->filled('kelengkapan') ? 'active' : '' }}">
                <span>Semua SPJ</span>
                <span class="count-badge">{{ $stats['total'] }}</span>
            </a>
            <a href="{{ route('bendahara.berkas.index', ['status' => 'disetujui_ppspm']) }}" class="bnd-filter-pill {{ request('status') === 'disetujui_ppspm' ? 'active' : '' }}">
                <span>Siap Diproses</span>
                <span class="count-badge">{{ $stats['siap_cair'] }}</span>
            </a>
            <a href="{{ route('bendahara.berkas.index', ['status' => 'proses_bank']) }}" class="bnd-filter-pill {{ request('status') === 'proses_bank' ? 'active' : '' }}">
                <span>Proses Bank (OTP / LS)</span>
                <span class="count-badge">{{ $stats['proses_bank'] }}</span>
            </a>
            <a href="{{ route('bendahara.berkas.index', ['kelengkapan' => 'perlu_lengkap']) }}" class="bnd-filter-pill {{ request('kelengkapan') === 'perlu_lengkap' ? 'active' : '' }}">
                <span>Perlu Dicentang Bendahara</span>
                <span class="count-badge" style="background: rgba(217, 119, 6, 0.2); color: #d97706;">{{ $stats['perlu_lengkap'] }}</span>
            </a>
            <a href="{{ route('bendahara.berkas.index', ['status' => 'selesai']) }}" class="bnd-filter-pill {{ request('status') === 'selesai' ? 'active' : '' }}">
                <span>Selesai Dicairkan</span>
                <span class="count-badge">{{ $stats['selesai'] }}</span>
            </a>
        </div>

        <form action="{{ route('bendahara.berkas.index') }}" method="GET" style="display: flex; gap: 8px; margin: 0;">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            @if(request('kelengkapan'))
                <input type="hidden" name="kelengkapan" value="{{ request('kelengkapan') }}">
            @endif
            <div style="position: relative;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kegiatan, No. SPJ, pengaju..." class="bnd-choice-input" style="width: 260px; max-width: 100%; min-width: 180px; padding-left: 36px; padding-top: 8px; padding-bottom: 8px; font-size: 13px;">
                <ion-icon name="search-outline" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--bnd-slate-400); font-size: 16px;"></ion-icon>
            </div>
            <button type="submit" class="bnd-btn" style="background: #0284c7; color: #ffffff; padding: 8px 14px; font-size: 13px;">
                Cari
            </button>
            @if(request()->anyFilled(['search', 'status', 'kelengkapan']))
                <a href="{{ route('bendahara.berkas.index') }}" class="bnd-btn bnd-btn-back" style="padding: 8px 12px;" title="Reset Filter">
                    <ion-icon name="close-outline"></ion-icon>
                </a>
            @endif
        </form>
    </div>
</div>

<!-- DAFTAR SPJ VERIFIKASI BERKAS -->
<div class="bnd-card">
    <div class="bnd-card-header">
        <div class="bnd-card-header-left">
            <div class="bnd-card-header-icon blue">
                <ion-icon name="folder-open-outline"></ion-icon>
            </div>
            <div>
                <h3 class="bnd-card-header-title">Daftar Berkas SPJ &amp; Status Kelengkapan</h3>
                <p class="bnd-card-header-desc">Klik tombol "Periksa &amp; Centang Berkas" pada SPJ untuk memeriksa dokumen awal dan mencentang bagian Bendahara.</p>
            </div>
        </div>
    </div>

    <div class="bnd-card-body" style="padding: 0;">
        @if($spjs->count() > 0)
        <div style="overflow-x: auto; width: 100%; max-width: 100%;">
            <table class="bnd-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--bnd-slate-50); border-bottom: 1px solid var(--bnd-slate-200); text-align: left;">
                        <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; color: var(--bnd-slate-600); width: 60px;">NO</th>
                        <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; color: var(--bnd-slate-600);">KEGIATAN &amp; PENGAJU</th>
                        <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; color: var(--bnd-slate-600);">NILAI TAGIHAN</th>
                        <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; color: var(--bnd-slate-600);">BERKAS TEKNIS (AWAL)</th>
                        <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; color: var(--bnd-slate-600);">BERKAS BENDAHARA</th>
                        <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; color: var(--bnd-slate-600);">STATUS PEMBAYARAN</th>
                        <th style="padding: 14px 18px; font-size: 12px; font-weight: 700; color: var(--bnd-slate-600); text-align: right;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($spjs as $index => $spj)
                    @php
                        $grouped = $spj->grouped_dokumen;
                        $teknisDocs = $grouped['teknis'] ?? [];
                        $bendaharaDocs = $grouped['bendahara'] ?? [];

                        $teknisReady = count(array_filter($teknisDocs, fn($d) => !empty($d['disiapkan'])));
                        $teknisTotal = count($teknisDocs);

                        $bendaharaReady = count(array_filter($bendaharaDocs, fn($d) => !empty($d['disiapkan'])));
                        $bendaharaTotal = count($bendaharaDocs);

                        $isBendaharaComplete = ($bendaharaTotal > 0 && $bendaharaReady >= $bendaharaTotal);
                    @endphp
                    <tr style="border-bottom: 1px solid var(--bnd-slate-100); transition: background 0.15s ease;">
                        <td style="padding: 16px 18px; font-size: 13px; color: var(--bnd-slate-400); font-weight: 600;">
                            {{ $loop->iteration }}
                        </td>
                        <td style="padding: 16px 18px;">
                            <div style="font-weight: 700; font-size: 14px; color: var(--bnd-slate-900); margin-bottom: 4px;">
                                {{ $spj->kegiatan }}
                            </div>
                            <div style="font-size: 12px; color: var(--bnd-slate-500); display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                <span>No: <strong>{{ $spj->nomor_spj ?: 'SPJ #'.$spj->id }}</strong></span>
                                <span>&bull;</span>
                                <span>Pengaju: <strong>{{ $spj->user->name ?? 'Staf Teknis' }}</strong></span>
                                @if($spj->nomor_spm)
                                    <span>&bull;</span>
                                    <span style="color: #0284c7;">SPM: <strong>{{ $spj->nomor_spm }}</strong></span>
                                @endif
                            </div>
                        </td>
                        <td style="padding: 16px 18px; white-space: nowrap;">
                            <strong style="color: #059669; font-size: 14.5px;">
                                Rp {{ number_format($spj->nilai, 0, ',', '.') }}
                            </strong>
                            <div style="font-size: 11px; color: var(--bnd-slate-400); margin-top: 2px;">
                                {{ $spj->tanggal ? $spj->tanggal->format('d M Y') : '-' }}
                            </div>
                        </td>
                        <td style="padding: 16px 18px; white-space: nowrap;">
                            <span class="badge-teknis-check" title="Dicentang oleh Staf Teknis di awal pengajuan">
                                <ion-icon name="checkmark-circle"></ion-icon>
                                <span>{{ $teknisReady }}/{{ $teknisTotal }} Dicentang di Awal</span>
                            </span>
                        </td>
                        <td style="padding: 16px 18px; white-space: nowrap;">
                            @if($isBendaharaComplete)
                                <span class="badge-bendahara-done">
                                    <ion-icon name="checkmark-done-circle"></ion-icon>
                                    <span>{{ $bendaharaReady }}/{{ $bendaharaTotal }} Lengkap (Dicentang)</span>
                                </span>
                            @else
                                <span class="badge-bendahara-pending">
                                    <ion-icon name="alert-circle-outline"></ion-icon>
                                    <span>{{ $bendaharaReady }}/{{ $bendaharaTotal }} Perlu Dicentang</span>
                                </span>
                            @endif
                        </td>
                        <td style="padding: 16px 18px; white-space: nowrap;">
                            @php
                                $badgeClass = 'bnd-badge-cyan';
                                if ($spj->status === 'disetujui_ppspm') {
                                    $badgeClass = 'bnd-badge-blue';
                                } elseif ($spj->status === 'menunggu_otp_kpa') {
                                    $badgeClass = 'bnd-badge-cyan';
                                } elseif ($spj->status === 'proses_ls') {
                                    $badgeClass = 'bnd-badge-amber';
                                } elseif ($spj->status === 'selesai') {
                                    $badgeClass = 'bnd-badge-mint';
                                } elseif (str_contains($spj->status, 'revisi')) {
                                    $badgeClass = 'bnd-badge-rose';
                                }
                            @endphp
                            <span class="bnd-badge {{ $badgeClass }}">
                                <span class="bnd-badge-dot"></span>
                                {{ $spj->status_label }}
                            </span>
                        </td>
                        <td style="padding: 16px 18px; text-align: right; white-space: nowrap;">
                            <div style="display: inline-flex; gap: 8px; align-items: center;">
                                <a href="{{ route('bendahara.berkas.show', $spj->id) }}" class="bnd-btn" style="background: #0284c7; color: #ffffff; padding: 7px 14px; font-size: 12.5px; box-shadow: 0 2px 6px rgba(2, 132, 199, 0.25);">
                                    <ion-icon name="checkbox-outline" style="font-size: 16px;"></ion-icon>
                                    <span>Periksa &amp; Centang</span>
                                </a>
                                <a href="{{ route('bendahara.spj.show', $spj->id) }}" class="bnd-btn bnd-btn-back" style="padding: 7px 10px;" title="Lihat Detail SPJ &amp; Eksekusi">
                                    <ion-icon name="eye-outline"></ion-icon>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div style="padding: 48px 24px; text-align: center;">
            <ion-icon name="folder-open-outline" style="font-size: 48px; color: var(--bnd-slate-300); margin-bottom: 12px;"></ion-icon>
            <h4 style="font-size: 16px; font-weight: 700; color: var(--bnd-slate-700); margin: 0 0 6px 0;">Tidak Ada Berkas SPJ Ditemukan</h4>
            <p style="font-size: 13.5px; color: var(--bnd-slate-400); margin: 0 0 16px 0;">Tidak ada dokumen SPJ yang sesuai dengan kriteria filter saat ini.</p>
            <a href="{{ route('bendahara.berkas.index') }}" class="bnd-btn bnd-btn-back" style="display: inline-flex;">
                Reset Filter
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
