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
            background: #ffffff;
            color: #64748b;
            font-size: 12px;
            font-weight: 600;
            border-bottom: 2px solid rgba(0,0,0,0.03);
        }

        .custom-table td {
            padding: 18px 24px;
            border-bottom: 1px solid rgba(0,0,0,0.02);
            color: #334155;
            font-size: 14px;
            vertical-align: middle;
        }

        .custom-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .custom-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .custom-table tbody tr:last-child td {
            border-bottom: none;
        }

        .dash-panel {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.02);
            border: 1px solid rgba(0,0,0,0.03);
            overflow: hidden;
        }

        .dash-panel-header {
            padding: 24px;
            border-bottom: 1px solid rgba(0,0,0,0.03);
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .dash-panel-header h2 {
            font-size: 18px;
            color: #0f172a;
            margin: 0;
            font-weight: 600;
        }

        .dash-panel-header p {
            font-size: 13px;
            color: #64748b;
            margin: 4px 0 0 0;
        }

        .dash-panel-header a {
            font-size: 14px;
            font-weight: 500;
            color: #0f172a;
            text-decoration: none;
            transition: all 0.2s;
        }

        .dash-panel-header a:hover {
            opacity: 0.7;
        }

        .stat-card-custom {
            background: #ffffff;
            border-radius: 16px;
            padding: 28px 24px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.02);
            border: 1px solid rgba(0,0,0,0.03);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card-custom:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        }

        .stat-icon-custom {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .stat-info-custom h3 {
            font-size: 28px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            line-height: 1.1;
        }

        .stat-info-custom p {
            font-size: 13px;
            color: #64748b;
            margin: 6px 0 0 0;
            font-weight: 500;
        }
    </style>
@endpush

<div style="padding: 32px 40px; box-sizing: border-box; max-width: 100%;">
    <!-- STATISTIK -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-bottom: 32px;">
        <div class="stat-card-custom">
            <div class="stat-icon-custom" style="background-color: #f8fafc; color: #0f172a;">
                <ion-icon name="documents-outline"></ion-icon>
            </div>
            <div class="stat-info-custom">
                <h3>{{ $totalAntrean }}</h3>
                <p>Menunggu Diperiksa</p>
            </div>
        </div>
        <div class="stat-card-custom">
            <div class="stat-icon-custom" style="background-color: #f8fafc; color: #0f172a;">
                <ion-icon name="checkmark-done-circle-outline"></ion-icon>
            </div>
            <div class="stat-info-custom">
                <h3>{{ $totalSelesai }}</h3>
                <p>Telah Diverifikasi</p>
            </div>
        </div>
        <div class="stat-card-custom">
            <div class="stat-icon-custom" style="background-color: #f8fafc; color: #0f172a;">
                <ion-icon name="arrow-undo-outline"></ion-icon>
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
                        <td style="color: #64748b;">{{ $spj->updated_at->format('d M Y') }}</td>
                        <td>{{ $spj->user->name ?? 'Teknis' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($spj->kegiatan, 40) }}</td>
                        <td>
                            <span class="badge pending" style="font-size: 11px; padding: 6px 12px; background: #f8fafc; color: #0f172a; border: 1px solid rgba(0,0,0,0.05); border-radius: 6px; font-weight: 500;">Menunggu Pemeriksaan</span>
                        </td>
                        <td>
                            <a href="{{ route('umum.spj.show', $spj->id) }}" 
                               style="display: flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 8px; text-decoration: none; color: #0f172a; background-color: #f1f5f9; transition: all 0.2s;" 
                               title="Periksa"
                               onmouseover="this.style.backgroundColor='#e2e8f0';" 
                               onmouseout="this.style.backgroundColor='#f1f5f9';">
                                <ion-icon name="eye-outline" style="font-size: 18px;"></ion-icon>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div style="text-align: center; padding: 48px 24px; color: #64748b; font-size: 14px;">
                                <ion-icon name="checkmark-done-circle-outline" style="font-size: 48px; color: #10b981; margin-bottom: 12px; display: block; margin-left: auto; margin-right: auto;"></ion-icon>
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
<section class="panel" style="animation: slideUp 0.7s ease-out forwards; animation-delay: 0.3s; margin-top: 2rem;">
    @if(isset($recentSpjs) && $recentSpjs->isNotEmpty())
    @php $topSpj = $recentSpjs->first(); @endphp
    <div class="panel-header">
        <div>
            <h2 title="{{ $topSpj->kegiatan }}">Alur Kegiatan: {{ Str::limit($topSpj->kegiatan, 40) }}</h2>
            <p>Posisi dokumen terakhir yang Anda proses</p>
        </div>
        <a href="{{ route('umum.spj.show', $topSpj->id) }}" style="color: var(--primary); font-size: 0.875rem; font-weight: 600; text-decoration: none;">Lihat detail &rarr;</a>
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
            <div style="position: absolute; top: 20px; left: 40px; right: 40px; height: 2px; background: #e2e8f0; z-index: 1; border-radius: 4px;"></div>
            
            <!-- TEKNIS -->
            <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: #0f172a; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; box-shadow: 0 0 0 6px white, 0 4px 10px rgba(0,0,0,0.05);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <strong style="font-size: 13px; font-weight: 500; color: #0f172a;">Teknis</strong>
            </div>

            <!-- UMUM -->
            <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: {{ $isPpk ? '#0f172a' : ($isUmum ? '#334155' : '#f1f5f9') }}; color: {{ $isPpk || $isUmum ? 'white' : '#94a3b8' }}; display: flex; align-items: center; justify-content: center; font-weight: bold; box-shadow: 0 0 0 6px white, 0 4px 10px rgba(0,0,0,0.05);">
                    @if($isPpk) <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    @else 2 @endif
                </div>
                <strong style="font-size: 13px; font-weight: 500; color: {{ $isUmum ? '#0f172a' : '#94a3b8' }};">Umum</strong>
            </div>

            <!-- PPK -->
            <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: {{ $isBendahara ? '#0f172a' : ($isPpk ? '#334155' : '#f1f5f9') }}; color: {{ $isBendahara || $isPpk ? 'white' : '#94a3b8' }}; display: flex; align-items: center; justify-content: center; font-weight: bold; box-shadow: 0 0 0 6px white, 0 4px 10px rgba(0,0,0,0.05);">
                    @if($isBendahara) <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    @else 3 @endif
                </div>
                <strong style="font-size: 13px; font-weight: 500; color: {{ $isPpk ? '#0f172a' : '#94a3b8' }};">PPK</strong>
            </div>

            <!-- BENDAHARA -->
            <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: {{ $isDone ? '#0f172a' : ($isBendahara ? '#334155' : '#f1f5f9') }}; color: {{ $isDone || $isBendahara ? 'white' : '#94a3b8' }}; display: flex; align-items: center; justify-content: center; font-weight: bold; box-shadow: 0 0 0 6px white, 0 4px 10px rgba(0,0,0,0.05);">
                    @if($isDone) <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    @else 4 @endif
                </div>
                <strong style="font-size: 13px; font-weight: 500; color: {{ $isBendahara ? '#0f172a' : '#94a3b8' }};">Bendahara</strong>
            </div>
            
            <!-- ARSIP / SELESAI -->
            <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: {{ $isDone ? '#0f172a' : '#f1f5f9' }}; color: {{ $isDone ? 'white' : '#94a3b8' }}; display: flex; align-items: center; justify-content: center; font-weight: bold; box-shadow: 0 0 0 6px white, 0 4px 10px rgba(0,0,0,0.05);">
                    @if($isDone) <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    @else 5 @endif
                </div>
                <strong style="font-size: 13px; font-weight: 500; color: {{ $isDone ? '#0f172a' : '#94a3b8' }};">Arsip</strong>
            </div>

        </div>
    </div>
    @else
    <div class="panel-header">
        <div>
            <h2>Alur SPJ</h2>
            <p>Belum ada SPJ untuk ditampilkan alurnya.</p>
        </div>
    </div>
    @endif
</section>
@endsection
