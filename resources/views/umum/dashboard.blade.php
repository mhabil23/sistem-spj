@extends('layouts.umum')

@php
$title = 'Dashboard Umum';
$subtitle = 'Ringkasan antrean pemeriksaan SPJ.';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/umum/dashboard.css'])
    <style>
        .custom-table {
            width: 100%;
            border-collapse: collapse;
            white-space: nowrap;
        }

        .custom-table th {
            padding: 16px 24px;
            text-align: left;
            background: var(--bg-card);
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 2px solid var(--border-color);
        }

        .custom-table td {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-main);
            font-size: 13px;
            vertical-align: middle;
        }

        .custom-table tbody tr {
            transition: background-color 0.15s;
        }

        .custom-table tbody tr:hover {
            background-color: var(--bg-card-hover);
        }

        .custom-table tbody tr:last-child td {
            border-bottom: none;
        }

        .dash-panel {
            background: var(--bg-card);
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .dash-panel-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
            background: var(--bg-card);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .dash-panel-header h2 {
            font-size: 16px;
            color: var(--text-main);
            margin: 0;
            font-weight: 700;
        }

        .dash-panel-header p {
            font-size: 12px;
            color: var(--text-muted);
            margin: 4px 0 0 0;
        }

        .dash-panel-header a {
            font-size: 13px;
            font-weight: 600;
            color: var(--accent);
            text-decoration: none;
        }

        .dash-panel-header a:hover {
            text-decoration: underline;
        }

        .stat-card-custom {
            background: var(--bg-card);
            border-radius: 12px;
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
            border: 1px solid var(--border-color);
        }

        .stat-icon-custom {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
            background: var(--bg-card-hover);
            color: var(--accent);
        }

        .stat-info-custom h3 {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
            line-height: 1.2;
        }

        .stat-info-custom p {
            font-size: 13px;
            color: var(--text-muted);
            margin: 4px 0 0 0;
            font-weight: 500;
        }
    </style>
@endpush

<div style="padding: 32px 40px; box-sizing: border-box; max-width: 100%;">
    <!-- STATISTIK -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-bottom: 32px;">
        <div class="stat-card-custom">
            <div class="stat-icon-custom" style="color: var(--text-main);">
                <ion-icon name="download-outline"></ion-icon>
            </div>
            <div class="stat-info-custom">
                <h3>{{ $totalAntrean }}</h3>
                <p>Menunggu Diperiksa</p>
            </div>
        </div>
        <div class="stat-card-custom">
            <div class="stat-icon-custom" style="color: #10b981;">
                <ion-icon name="checkmark-circle-outline"></ion-icon>
            </div>
            <div class="stat-info-custom">
                <h3>{{ $totalSelesai }}</h3>
                <p>Telah Diverifikasi</p>
            </div>
        </div>
        <div class="stat-card-custom">
            <div class="stat-icon-custom" style="color: #ef4444;">
                <ion-icon name="close-circle-outline"></ion-icon>
            </div>
            <div class="stat-info-custom">
                <h3>{{ $totalRevisi }}</h3>
                <p>Dikembalikan (Revisi)</p>
            </div>
        </div>
    </div>

    <!-- DAFTAR ANTREAN SPJ -->
    <div class="dash-panel">
        <div class="dash-panel-header">
            <div>
                <h2>Antrean SPJ Terbaru</h2>
                <p>Daftar SPJ yang perlu segera Anda periksa.</p>
            </div>
            <a href="{{ route('umum.spj.index') }}">Lihat Semua &rarr;</a>
        </div>
        
        <div style="overflow-x: auto;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Tanggal Pengajuan</th>
                        <th>Pengaju (Teknis)</th>
                        <th>Kegiatan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($antreanSpjs as $spj)
                    <tr>
                        <td style="color: var(--text-muted);">{{ $spj->updated_at->format('d M Y') }}</td>
                        <td>{{ $spj->user->name ?? 'Teknis' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($spj->kegiatan, 40) }}</td>
                        <td>
                            <span class="badge pending" style="font-size: 10px; padding: 6px 12px; background: #fffbeb; color: #b45309; border-radius: 999px; font-weight: 600;">Menunggu Pemeriksaan</span>
                        </td>
                        <td>
                            <a href="{{ route('umum.spj.show', $spj->id) }}" 
                               style="display: flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 8px; text-decoration: none; color: #000; background-color: var(--accent); box-shadow: 0 2px 4px rgba(255,183,3,0.25); transition: all 0.2s;" 
                               title="Periksa"
                               onmouseover="this.style.transform='translateY(-2px)';" 
                               onmouseout="this.style.transform='translateY(0)';">
                                <ion-icon name="eye-outline" style="font-size: 18px;"></ion-icon>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div style="text-align: center; padding: 48px 24px; color: var(--text-muted); font-size: 14px;">
                                <ion-icon name="checkmark-done-circle-outline" style="font-size: 48px; color: var(--accent); margin-bottom: 12px; display: block; margin-left: auto; margin-right: auto;"></ion-icon>
                                Tidak ada antrean SPJ saat ini. Semua sudah diperiksa!
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ALUR TIMELINE -->
<section class="dash-panel" style="animation: slideUp 0.7s ease-out forwards; animation-delay: 0.3s; margin-top: 2rem;">
    @if(isset($recentSpjs) && $recentSpjs->isNotEmpty())
    @php $topSpj = $recentSpjs->first(); @endphp
    <div class="dash-panel-header">
        <div>
            <h2 title="{{ $topSpj->kegiatan }}">Alur Kegiatan: {{ Str::limit($topSpj->kegiatan, 40) }}</h2>
            <p>Posisi dokumen terakhir yang Anda proses</p>
        </div>
        <a href="{{ route('umum.spj.show', $topSpj->id) }}">Lihat detail &rarr;</a>
    </div>

    <div style="overflow-x: auto; padding: 3rem 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; min-width: 600px; position: relative;">
            
            @php
                $isUmum = in_array($topSpj->status, ['diajukan', 'disetujui_umum', 'disetujui_ppk', 'disetujui_ppspm', 'selesai']);
                $isPpk = in_array($topSpj->status, ['disetujui_umum', 'disetujui_ppk', 'disetujui_ppspm', 'selesai']);
                $isBendahara = in_array($topSpj->status, ['disetujui_ppspm', 'selesai']);
                $isDone = $topSpj->status == 'selesai';
            @endphp
            
            <!-- LINE BACKGROUND -->
            <div style="position: absolute; top: 20px; left: 40px; right: 40px; height: 4px; background: var(--border-color); z-index: 1; border-radius: 4px;"></div>
            
            <!-- TEKNIS -->
            <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: var(--accent); color: #000; display: flex; align-items: center; justify-content: center; font-weight: bold; box-shadow: 0 0 0 6px var(--bg-card), 0 4px 10px rgba(0,0,0,0.5);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <strong style="font-size: 0.9rem; color: var(--text-main);">Teknis</strong>
            </div>

            <!-- UMUM -->
            <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: {{ $isPpk ? 'var(--accent)' : ($isUmum ? 'var(--accent-hover)' : 'var(--bg-card-hover)') }}; color: {{ $isPpk || $isUmum ? '#000' : 'var(--text-muted)' }}; display: flex; align-items: center; justify-content: center; font-weight: bold; box-shadow: 0 0 0 6px var(--bg-card), 0 4px 10px rgba(0,0,0,0.5);">
                    @if($isPpk) <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    @else 2 @endif
                </div>
                <strong style="font-size: 0.9rem; color: {{ $isUmum ? 'var(--text-main)' : 'var(--text-muted)' }};">Umum</strong>
            </div>

            <!-- PPK -->
            <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: {{ $isBendahara ? 'var(--accent)' : ($isPpk ? 'var(--accent-hover)' : 'var(--bg-card-hover)') }}; color: {{ $isBendahara || $isPpk ? '#000' : 'var(--text-muted)' }}; display: flex; align-items: center; justify-content: center; font-weight: bold; box-shadow: 0 0 0 6px var(--bg-card), 0 4px 10px rgba(0,0,0,0.5);">
                    @if($isBendahara) <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    @else 3 @endif
                </div>
                <strong style="font-size: 0.9rem; color: {{ $isPpk ? 'var(--text-main)' : 'var(--text-muted)' }};">PPK</strong>
            </div>

            <!-- BENDAHARA -->
            <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: {{ $isDone ? 'var(--accent)' : ($isBendahara ? 'var(--accent-hover)' : 'var(--bg-card-hover)') }}; color: {{ $isDone || $isBendahara ? '#000' : 'var(--text-muted)' }}; display: flex; align-items: center; justify-content: center; font-weight: bold; box-shadow: 0 0 0 6px var(--bg-card), 0 4px 10px rgba(0,0,0,0.5);">
                    @if($isDone) <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    @else 4 @endif
                </div>
                <strong style="font-size: 0.9rem; color: {{ $isBendahara ? 'var(--text-main)' : 'var(--text-muted)' }};">Bendahara</strong>
            </div>
            
            <!-- ARSIP / SELESAI -->
            <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: {{ $isDone ? 'var(--accent)' : 'var(--bg-card-hover)' }}; color: {{ $isDone ? '#000' : 'var(--text-muted)' }}; display: flex; align-items: center; justify-content: center; font-weight: bold; box-shadow: 0 0 0 6px var(--bg-card), 0 4px 10px rgba(0,0,0,0.5);">
                    @if($isDone) <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    @else 5 @endif
                </div>
                <strong style="font-size: 0.9rem; color: {{ $isDone ? 'var(--text-main)' : 'var(--text-muted)' }};">Arsip</strong>
            </div>

        </div>
    </div>
    @else
    <div class="dash-panel-header">
        <div>
            <h2>Alur SPJ</h2>
            <p>Belum ada SPJ untuk ditampilkan alurnya.</p>
        </div>
    </div>
    @endif
</section>
@endsection
