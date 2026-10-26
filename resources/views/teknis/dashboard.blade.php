@extends('layouts.teknis')

@php
$title = 'Dashboard';
$subtitle = 'Pantau dan kelola pengajuan SPJ Anda.';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/teknis/dashboard.css', 'resources/css/teknis/spj.css'])
@endpush

<!-- PAGE HEADER -->
<div class="dashboard-heading">
    <div>
        <h2 class="dashboard-heading-title">
            Selamat Datang, {{ Auth::user()->name ?? 'Pengguna Teknis' }}
        </h2>
        <p class="dashboard-heading-subtitle">
            Berikut ringkasan pengajuan dan status alur SPJ terkini Anda.
        </p>
    </div>

    <a href="{{ route('teknis.spj.create') }}" class="btn-dash-primary">
        <ion-icon name="add-circle-outline" style="font-size: 18px;"></ion-icon>
        Ajukan SPJ Baru
    </a>
</div>

<!-- STATISTIK -->
<section class="stats-grid">
    <!-- TOTAL -->
    <div class="stat-card">
        <div class="stat-icon blue">
            <ion-icon name="documents-outline"></ion-icon>
        </div>
        <div class="stat-content">
            <span>Total SPJ</span>
            <strong>{{ $totalSpj }}</strong>
            <small>Semua pengajuan Anda</small>
        </div>
    </div>

    <!-- DIPROSES -->
    <div class="stat-card">
        <div class="stat-icon orange">
            <ion-icon name="hourglass-outline"></ion-icon>
        </div>
        <div class="stat-content">
            <span>Sedang Diproses</span>
            <strong>{{ $diprosesSpj }}</strong>
            <small>Menunggu pemeriksaan</small>
        </div>
    </div>

    <!-- DIKEMBALIKAN -->
    <div class="stat-card">
        <div class="stat-icon red">
            <ion-icon name="alert-circle-outline"></ion-icon>
        </div>
        <div class="stat-content">
            <span>Dikembalikan</span>
            <strong>{{ $dikembalikanSpj }}</strong>
            <small>Perlu perbaikan</small>
        </div>
    </div>

    <!-- SELESAI -->
    <div class="stat-card">
        <div class="stat-icon green">
            <ion-icon name="checkmark-done-circle-outline"></ion-icon>
        </div>
        <div class="stat-content">
            <span>Selesai</span>
            <strong>{{ $selesaiSpj }}</strong>
            <small>SPJ telah selesai</small>
        </div>
    </div>
</section>

<!-- CONTENT GRID -->
<section class="dashboard-grid">
    <!-- SPJ TERBARU -->
    <div class="spj-panel">
        <div class="spj-panel-header">
            <div>
                <h2>SPJ Saya</h2>
                <p>Pengajuan SPJ terbaru dari akun Anda</p>
            </div>
            <a href="{{ route('teknis.spj.index') }}" style="color: var(--dash-cyan-text); font-size: 13px; font-weight: 600; text-decoration: none;">Lihat semua &rarr;</a>
        </div>

        <div class="spj-panel-body" style="padding: 0;">
            <div class="spj-table-container">
                <table class="spj-table">
                    <thead>
                        <tr>
                            <th>Uraian Kegiatan</th>
                            <th>Terakhir Update</th>
                            <th>Tahapan</th>
                            <th>Status Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSpjs as $spj)
                        <tr>
                            <td style="max-width: 280px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-weight: 600;" title="{{ $spj->kegiatan }}">{{ $spj->kegiatan }}</td>
                            <td style="color: var(--dash-text-muted); font-size: 12px;">{{ $spj->updated_at->diffForHumans() }}</td>
                            <td>
                                <span style="font-size: 12px; color: var(--dash-text-muted); font-weight: 600;">
                                @if(in_array($spj->status, ['draft', 'revisi_umum', 'revisi_ppk', 'revisi_bendahara', 'revisi_ppspm']))
                                    Teknis
                                @elseif($spj->status == 'diajukan')
                                    Umum
                                @elseif($spj->status == 'disetujui_umum')
                                    PPK
                                @elseif($spj->status == 'disetujui_ppk')
                                    PPSPM
                                @elseif($spj->status == 'disetujui_ppspm')
                                    Bendahara
                                @elseif($spj->status == 'menunggu_otp_kpa')
                                    KPA (OTP QLOLA)
                                @elseif($spj->status == 'selesai')
                                    Arsip
                                @else
                                    {{ ucfirst($spj->status) }}
                                @endif
                                </span>
                            </td>
                            <td>
                                @php
                                    $badgeClass = 'dash-badge-draft';
                                    if(in_array($spj->status, ['selesai', 'disetujui_ppk', 'disetujui_umum', 'disetujui_ppspm'])) $badgeClass = 'dash-badge-mint';
                                    elseif(in_array($spj->status, ['diajukan', 'menunggu_otp_kpa'])) $badgeClass = 'dash-badge-cyan';
                                    elseif(in_array($spj->status, ['dikembalikan', 'revisi_bendahara', 'revisi_ppk', 'revisi_ppspm', 'revisi_umum'])) $badgeClass = 'dash-badge-peach';
                                @endphp
                                <span class="dash-badge {{ $badgeClass }}">
                                    <span class="badge-dot"></span>
                                    {{ $spj->status === 'menunggu_otp_kpa' ? 'MENUNGGU OTP QLOLA KPA' : str_replace('_', ' ', strtoupper($spj->status)) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 48px 24px; color: var(--dash-text-muted);">
                                <div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                                    <ion-icon name="document-text-outline" style="font-size: 36px;"></ion-icon>
                                    <span>Belum ada pengajuan SPJ.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- NOTIFIKASI / STATUS -->
    <div class="spj-panel">
        <div class="spj-panel-header">
            <div>
                <h2>Perlu Perhatian</h2>
                <p>Notifikasi pengajuan terbaru</p>
            </div>
        </div>

        <div class="spj-panel-body" style="padding: 20px;">
            @php
                $needAttention = $recentSpjs->filter(function($spj) {
                    return str_contains($spj->status, 'revisi') || in_array($spj->status, ['diajukan', 'disetujui_umum', 'disetujui_ppk', 'disetujui_ppspm', 'menunggu_otp_kpa']);
                });
            @endphp

            @forelse($needAttention->take(4) as $spj)
                @if(str_contains($spj->status, 'revisi'))
                    <div style="display: flex; gap: 14px; padding: 14px; background: var(--dash-peach-bg); border-radius: 12px; margin-bottom: 12px; border-left: 4px solid var(--dash-peach-text); border: 1px solid var(--dash-peach-border);">
                        <div style="color: var(--dash-peach-text); font-size: 20px;">
                            <ion-icon name="warning"></ion-icon>
                        </div>
                        <div style="flex: 1;">
                            <strong style="display: block; font-size: 13px; color: var(--dash-peach-text); margin-bottom: 4px;">{{ Str::limit($spj->kegiatan, 45) }} (Perlu Revisi)</strong>
                            <p style="font-size: 12px; color: var(--dash-text-muted); margin-bottom: 8px; line-height: 1.4;">{{ Str::limit($spj->catatan_revisi, 60, '...') }}</p>
                            <a href="{{ route('teknis.spj.edit', $spj->id) }}" style="font-size: 12px; font-weight: 700; color: var(--dash-peach-text); text-decoration: none;">Perbaiki sekarang &rarr;</a>
                        </div>
                    </div>
                @elseif($spj->status === 'menunggu_otp_kpa')
                    <div style="display: flex; gap: 14px; padding: 14px; background: #eff6ff; border-radius: 12px; margin-bottom: 12px; border-left: 4px solid #2563eb; border: 1px solid #bfdbfe;">
                        <div style="color: #2563eb; font-size: 20px;">
                            <ion-icon name="key-outline"></ion-icon>
                        </div>
                        <div style="flex: 1;">
                            <strong style="display: block; font-size: 13px; color: #1e40af; margin-bottom: 4px;">{{ Str::limit($spj->kegiatan, 45) }}</strong>
                            <p style="font-size: 12px; color: #1e3a8a; margin: 0; line-height: 1.4;">Tahap: MENUNGGU OTP QLOLA KPA (Otorisasi Pembayaran).</p>
                        </div>
                    </div>
                @elseif(in_array($spj->status, ['diajukan', 'disetujui_umum', 'disetujui_ppk', 'disetujui_ppspm']))
                    <div style="display: flex; gap: 14px; padding: 14px; background: var(--dash-input-bg); border-radius: 12px; margin-bottom: 12px; border-left: 4px solid var(--dash-cyan-text); border: 1px solid var(--dash-border);">
                        <div style="color: var(--dash-cyan-text); font-size: 20px;">
                            <ion-icon name="time"></ion-icon>
                        </div>
                        <div style="flex: 1;">
                            <strong style="display: block; font-size: 13px; color: var(--dash-text-dark); margin-bottom: 4px;">{{ Str::limit($spj->kegiatan, 45) }}</strong>
                            <p style="font-size: 12px; color: var(--dash-text-muted); margin: 0; line-height: 1.4;">Tahap: {{ str_replace('_', ' ', strtoupper($spj->status)) }}.</p>
                        </div>
                    </div>
                @endif
            @empty
                <div style="padding: 32px 16px; text-align: center; color: var(--dash-text-muted); font-size: 13px;">
                    <ion-icon name="checkmark-circle-outline" style="font-size: 32px; display: block; margin: 0 auto 8px; color: var(--dash-mint-text);"></ion-icon>
                    Tidak ada pemberitahuan mendesak saat ini.
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- ALUR TIMELINE -->
<section class="spj-panel">
    @if($recentSpjs->isNotEmpty())
    @php $topSpj = $recentSpjs->first(); @endphp
    <div class="spj-panel-header">
        <div>
            <h2 title="{{ $topSpj->kegiatan }}">Alur Kegiatan: {{ Str::limit($topSpj->kegiatan, 40) }}</h2>
            <p>Posisi pengajuan terakhir yang Anda daftarkan</p>
        </div>
        <a href="{{ route('teknis.spj.show', $topSpj->id) }}" style="color: var(--dash-cyan-text); font-size: 13px; font-weight: 600; text-decoration: none;">Lihat detail &rarr;</a>
    </div>

    <div class="alur-stepper-container">
        <div class="alur-stepper-track">
            
            @php
                $isUmum = in_array($topSpj->status, ['diajukan', 'disetujui_umum', 'disetujui_ppk', 'disetujui_ppspm', 'menunggu_otp_kpa', 'selesai']);
                $isPpk = in_array($topSpj->status, ['disetujui_umum', 'disetujui_ppk', 'disetujui_ppspm', 'menunggu_otp_kpa', 'selesai']);
                $isPpspm = in_array($topSpj->status, ['disetujui_ppk', 'disetujui_ppspm', 'menunggu_otp_kpa', 'selesai']);
                $isBendahara = in_array($topSpj->status, ['disetujui_ppspm', 'menunggu_otp_kpa', 'selesai']);
                $isDone = $topSpj->status == 'selesai';
            @endphp
            
            <!-- LINE BACKGROUND -->
            <div class="alur-line-bg"></div>
            
            <!-- STEP 1: TEKNIS -->
            <div class="alur-step-node">
                <div class="alur-circle done">
                    <ion-icon name="checkmark"></ion-icon>
                </div>
                <strong class="alur-step-label">Teknis</strong>
            </div>

            <!-- STEP 2: UMUM -->
            <div class="alur-step-node">
                <div class="alur-circle {{ $isPpk ? 'done' : ($isUmum ? 'current' : 'waiting') }}">
                    @if($isPpk) <ion-icon name="checkmark"></ion-icon>
                    @else 2 @endif
                </div>
                <strong class="alur-step-label {{ $isUmum ? '' : 'muted' }}">Umum</strong>
            </div>

            <!-- STEP 3: PPK -->
            <div class="alur-step-node">
                <div class="alur-circle {{ $isPpspm ? 'done' : ($isPpk ? 'current' : 'waiting') }}">
                    @if($isPpspm) <ion-icon name="checkmark"></ion-icon>
                    @else 3 @endif
                </div>
                <strong class="alur-step-label {{ $isPpk ? '' : 'muted' }}">PPK</strong>
            </div>

            <!-- STEP 4: BENDAHARA -->
            <div class="alur-step-node">
                <div class="alur-circle {{ $isDone ? 'done' : ($isBendahara ? 'current' : 'waiting') }}">
                    @if($isDone) <ion-icon name="checkmark"></ion-icon>
                    @else 4 @endif
                </div>
                <strong class="alur-step-label {{ $isBendahara ? '' : 'muted' }}">Bendahara</strong>
            </div>
            
            <!-- STEP 5: ARSIP -->
            <div class="alur-step-node">
                <div class="alur-circle {{ $isDone ? 'done' : 'waiting' }}">
                    @if($isDone) <ion-icon name="checkmark"></ion-icon>
                    @else 5 @endif
                </div>
                <strong class="alur-step-label {{ $isDone ? '' : 'muted' }}">Arsip</strong>
            </div>

        </div>
    </div>
    @else
    <div class="spj-panel-header">
        <div>
            <h2>Alur SPJ</h2>
            <p>Belum ada SPJ aktif untuk ditampilkan alur prosesnya.</p>
        </div>
    </div>
    @endif
</section>

@endsection
