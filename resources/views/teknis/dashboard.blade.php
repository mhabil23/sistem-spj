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
<div class="page-heading" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; animation: slideUp 0.4s ease-out forwards;">
    <div>
        <h2 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin-bottom: 0.25rem;">
            Selamat Datang, {{ Auth::user()->name ?? 'Pengguna Teknis' }}
        </h2>
        <p style="color: #64748b; font-size: 0.95rem;">
            Berikut ringkasan pengajuan SPJ Anda.
        </p>
    </div>

    <a href="{{ route('teknis.spj.create') }}" class="spj-btn spj-btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        Ajukan SPJ Baru
    </a>
</div>

<!-- STATISTIK -->
<section class="stats-grid" style="animation: slideUp 0.5s ease-out forwards; animation-delay: 0.1s;">
    <!-- TOTAL -->
    <div class="stat-card">
        <div class="stat-icon blue">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
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
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
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
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        </div>
        <div class="stat-content">
            <span>Dikembalikan</span>
            <strong>{{ $dikembalikanSpj }}</strong>
            <small>Perlu diperbaiki</small>
        </div>
    </div>

    <!-- SELESAI -->
    <div class="stat-card">
        <div class="stat-icon green">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>
        <div class="stat-content">
            <span>Selesai</span>
            <strong>{{ $selesaiSpj }}</strong>
            <small>SPJ telah selesai</small>
        </div>
    </div>
</section>

<!-- CONTENT GRID -->
<section class="dashboard-grid" style="animation: slideUp 0.6s ease-out forwards; animation-delay: 0.2s;">
    <!-- SPJ TERBARU -->
    <div class="spj-panel">
        <div class="spj-panel-header">
            <div>
                <h2>SPJ Saya</h2>
                <p>Pengajuan SPJ terbaru</p>
            </div>
            <a href="{{ route('teknis.spj.index') }}" style="color: var(--primary); font-size: 0.875rem; font-weight: 600; text-decoration: none;">Lihat semua &rarr;</a>
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
                            <td style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-weight: 600; color: #0f172a;" title="{{ $spj->kegiatan }}">{{ $spj->kegiatan }}</td>
                            <td style="color: #64748b; font-size: 0.9rem;">{{ $spj->updated_at->diffForHumans() }}</td>
                            <td>
                                <span style="font-size: 0.8rem; color: #64748b; font-weight: 500;">
                                @if(in_array($spj->status, ['draft', 'revisi_umum', 'revisi_ppk', 'revisi_bendahara', 'revisi_ppspm']))
                                    Teknis
                                @elseif($spj->status == 'diajukan')
                                    Umum/PPSPM
                                @elseif($spj->status == 'disetujui_umum')
                                    PPK
                                @elseif($spj->status == 'disetujui_ppk')
                                    PPSPM
                                @elseif($spj->status == 'disetujui_ppspm')
                                    Bendahara
                                @elseif($spj->status == 'selesai')
                                    Arsip
                                @else
                                    {{ ucfirst($spj->status) }}
                                @endif
                                </span>
                            </td>
                            <td>
                                @php
                                    $badgeClass = 'spj-badge-draft';
                                    if(in_array($spj->status, ['selesai'])) $badgeClass = 'spj-badge-selesai';
                                    elseif(in_array($spj->status, ['diajukan', 'disetujui_ppk'])) $badgeClass = 'spj-badge-diajukan';
                                    elseif(in_array($spj->status, ['disetujui_ppspm'])) $badgeClass = 'spj-badge-disetujui';
                                    elseif(in_array($spj->status, ['dikembalikan', 'revisi_bendahara', 'revisi_ppk', 'revisi_ppspm'])) $badgeClass = 'spj-badge-dikembalikan';
                                @endphp
                                <span class="spj-badge {{ $badgeClass }}">{{ str_replace('_', ' ', strtoupper($spj->status)) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding: 3rem 2rem; color: #94a3b8;">
                                <div style="display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
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

        <div class="spj-panel-body" style="padding: 1.5rem;">
            @php
                $needAttention = $recentSpjs->filter(function($spj) {
                    return str_contains($spj->status, 'revisi') || in_array($spj->status, ['diajukan', 'disetujui_umum', 'disetujui_ppk', 'disetujui_ppspm']);
                });
            @endphp

            @forelse($needAttention->take(4) as $spj)
                @if(str_contains($spj->status, 'revisi'))
                    <div style="display: flex; gap: 1rem; padding: 1rem; background: var(--danger-bg); border-radius: var(--radius-md); margin-bottom: 1rem; border-left: 4px solid var(--danger);">
                        <div style="color: var(--danger); margin-top: 0.25rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        </div>
                        <div style="flex: 1;">
                            <strong style="display: block; font-size: 0.9rem; color: #7f1d1d; margin-bottom: 0.25rem;">{{ Str::limit($spj->kegiatan, 50) }} dikembalikan</strong>
                            <p style="font-size: 0.8rem; color: #991b1b; margin-bottom: 0.5rem; line-height: 1.4;">{{ Str::limit($spj->catatan_revisi, 60, '...') }}</p>
                            <a href="{{ route('teknis.spj.edit', $spj->id) }}" style="font-size: 0.8rem; font-weight: 600; color: var(--danger); text-decoration: none;">Perbaiki sekarang &rarr;</a>
                        </div>
                    </div>
                @elseif(in_array($spj->status, ['diajukan', 'disetujui_umum', 'disetujui_ppk', 'disetujui_ppspm']))
                    <div style="display: flex; gap: 1rem; padding: 1rem; background: #f8fafc; border-radius: var(--radius-md); margin-bottom: 1rem; border-left: 4px solid var(--primary);">
                        <div style="color: var(--primary); margin-top: 0.25rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        </div>
                        <div style="flex: 1;">
                            <strong style="display: block; font-size: 0.9rem; color: #0f172a; margin-bottom: 0.25rem;">{{ Str::limit($spj->kegiatan, 50) }} diproses</strong>
                            <p style="font-size: 0.8rem; color: #64748b; line-height: 1.4;">Pengajuan di tahap {{ str_replace('_', ' ', $spj->status) }}.</p>
                        </div>
                    </div>
                @endif
            @empty
                <div style="padding: 2rem; text-align: center; color: #94a3b8; font-size: 0.9rem;">
                    Tidak ada pemberitahuan penting saat ini.
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- ALUR -->
<section class="spj-panel" style="animation: slideUp 0.7s ease-out forwards; animation-delay: 0.3s; margin-top: 2rem;">
    @if($recentSpjs->isNotEmpty())
    @php $topSpj = $recentSpjs->first(); @endphp
    <div class="spj-panel-header">
        <div>
            <h2 title="{{ $topSpj->kegiatan }}">Alur Kegiatan: {{ Str::limit($topSpj->kegiatan, 40) }}</h2>
            <p>Posisi pengajuan terakhir Anda</p>
        </div>
        <a href="{{ route('teknis.spj.show', $topSpj->id) }}" style="color: var(--primary); font-size: 0.875rem; font-weight: 600; text-decoration: none;">Lihat detail &rarr;</a>
    </div>

    <div class="spj-panel-body" style="overflow-x: auto; padding: 3rem 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; min-width: 600px; position: relative;">
            
            @php
                $isUmum = in_array($topSpj->status, ['diajukan', 'disetujui_umum', 'disetujui_ppk', 'disetujui_ppspm', 'selesai']);
                $isPpk = in_array($topSpj->status, ['disetujui_umum', 'disetujui_ppk', 'disetujui_ppspm', 'selesai']);
                $isPpspm = in_array($topSpj->status, ['disetujui_ppk', 'disetujui_ppspm', 'selesai']);
                $isBendahara = in_array($topSpj->status, ['disetujui_ppspm', 'selesai']);
                $isDone = $topSpj->status == 'selesai';
            @endphp
            
            <!-- LINE BACKGROUND -->
            <div style="position: absolute; top: 20px; left: 40px; right: 40px; height: 4px; background: #e2e8f0; z-index: 1; border-radius: 4px;"></div>
            
            <!-- TEKNIS -->
            <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: var(--success); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; box-shadow: 0 0 0 6px white, 0 4px 10px rgba(0,0,0,0.1);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <strong style="font-size: 0.9rem; color: #0f172a;">Teknis</strong>
            </div>

            <!-- UMUM -->
            <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: {{ $isPpk ? 'var(--success)' : ($isUmum ? 'var(--primary)' : '#f1f5f9') }}; color: {{ $isPpk || $isUmum ? 'white' : '#94a3b8' }}; display: flex; align-items: center; justify-content: center; font-weight: bold; box-shadow: 0 0 0 6px white, 0 4px 10px rgba(0,0,0,0.1);">
                    @if($isPpk) <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    @else 2 @endif
                </div>
                <strong style="font-size: 0.9rem; color: {{ $isUmum ? '#0f172a' : '#94a3b8' }};">Umum</strong>
            </div>

            <!-- PPK -->
            <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: {{ $isPpspm ? 'var(--success)' : ($isPpk ? 'var(--primary)' : '#f1f5f9') }}; color: {{ $isPpspm || $isPpk ? 'white' : '#94a3b8' }}; display: flex; align-items: center; justify-content: center; font-weight: bold; box-shadow: 0 0 0 6px white, 0 4px 10px rgba(0,0,0,0.1);">
                    @if($isPpspm) <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    @else 3 @endif
                </div>
                <strong style="font-size: 0.9rem; color: {{ $isPpk ? '#0f172a' : '#94a3b8' }};">PPK</strong>
            </div>

            <!-- PPSPM -->
            <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: {{ $isBendahara ? 'var(--success)' : ($isPpspm ? 'var(--primary)' : '#f1f5f9') }}; color: {{ $isBendahara || $isPpspm ? 'white' : '#94a3b8' }}; display: flex; align-items: center; justify-content: center; font-weight: bold; box-shadow: 0 0 0 6px white, 0 4px 10px rgba(0,0,0,0.1);">
                    @if($isBendahara) <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    @else 4 @endif
                </div>
                <strong style="font-size: 0.9rem; color: {{ $isPpspm ? '#0f172a' : '#94a3b8' }};">PPSPM</strong>
            </div>

            <!-- BENDAHARA -->
            <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: {{ $isDone ? 'var(--success)' : ($isBendahara ? 'var(--primary)' : '#f1f5f9') }}; color: {{ $isDone || $isBendahara ? 'white' : '#94a3b8' }}; display: flex; align-items: center; justify-content: center; font-weight: bold; box-shadow: 0 0 0 6px white, 0 4px 10px rgba(0,0,0,0.1);">
                    @if($isDone) <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    @else 5 @endif
                </div>
                <strong style="font-size: 0.9rem; color: {{ $isBendahara ? '#0f172a' : '#94a3b8' }};">Bendahara</strong>
            </div>
            
            <!-- ARSIP / SELESAI -->
            <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: {{ $isDone ? 'var(--success)' : '#f1f5f9' }}; color: {{ $isDone ? 'white' : '#94a3b8' }}; display: flex; align-items: center; justify-content: center; font-weight: bold; box-shadow: 0 0 0 6px white, 0 4px 10px rgba(0,0,0,0.1);">
                    @if($isDone) <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    @else 6 @endif
                </div>
                <strong style="font-size: 0.9rem; color: {{ $isDone ? '#0f172a' : '#94a3b8' }};">Arsip</strong>
            </div>

        </div>
    </div>
    @else
    <div class="spj-panel-header">
        <div>
            <h2>Alur SPJ</h2>
            <p>Belum ada SPJ untuk ditampilkan alurnya.</p>
        </div>
    </div>
    @endif
</section>

@endsection
