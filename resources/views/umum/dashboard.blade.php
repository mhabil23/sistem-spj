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
            padding: 18px 24px;
            text-align: left;
            background: var(--bg-card);
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 700;
            border-bottom: 2px dashed var(--bg-sage-light);
        }

        .custom-table td {
            padding: 20px 24px;
            border-bottom: 1px solid var(--bg-sage-light);
            color: var(--text-dark);
            font-size: 15px;
            font-weight: 500;
            vertical-align: middle;
        }

        .custom-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .custom-table tbody tr:hover {
            background-color: var(--bg-cream);
        }

        .custom-table tbody tr:last-child td {
            border-bottom: none;
        }

        .dash-panel {
            background: var(--bg-card);
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(174, 193, 166, 0.15);
            border: none;
            overflow: hidden;
        }

        .dash-panel-header {
            padding: 28px;
            border-bottom: 2px dashed var(--bg-sage-light);
            background: var(--bg-card);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .dash-panel-header h2 {
            font-family: 'Fredoka', sans-serif;
            font-size: 22px;
            color: var(--text-dark);
            margin: 0;
            font-weight: 600;
        }

        .dash-panel-header p {
            font-size: 14px;
            color: var(--text-muted);
            margin: 6px 0 0 0;
            font-weight: 500;
        }

        .dash-panel-header a {
            font-size: 15px;
            font-weight: 600;
            color: var(--accent-orange);
            text-decoration: none;
            transition: all 0.2s;
        }

        .dash-panel-header a:hover {
            color: var(--accent-orange-hover);
        }

        .stat-card-custom {
            background: var(--bg-card);
            border-radius: 28px;
            padding: 32px 28px;
            display: flex;
            align-items: center;
            gap: 24px;
            box-shadow: 0 8px 32px rgba(174, 193, 166, 0.15);
            border: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card-custom:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 40px rgba(174, 193, 166, 0.25);
        }

        .stat-icon-custom {
            width: 56px;
            height: 56px;
            border-radius: 18px 8px 18px 8px; /* Organic */
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            flex-shrink: 0;
        }

        .stat-info-custom h3 {
            font-family: 'Fredoka', sans-serif;
            font-size: 32px;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
            line-height: 1.1;
        }

        .stat-info-custom p {
            font-size: 14px;
            color: var(--text-muted);
            margin: 8px 0 0 0;
            font-weight: 600;
        }
    </style>
@endpush

<div style="padding: 32px 40px; box-sizing: border-box; max-width: 100%;">
    <!-- STATISTIK -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-bottom: 32px;">
        <div class="stat-card-custom">
            <div class="stat-icon-custom" style="background-color: var(--bg-sage); color: var(--text-dark);">
                <ion-icon name="documents-outline"></ion-icon>
            </div>
            <div class="stat-info-custom">
                <h3>{{ $totalAntrean }}</h3>
                <p>Menunggu Diperiksa</p>
            </div>
        </div>
        <div class="stat-card-custom">
            <div class="stat-icon-custom" style="background-color: var(--bg-sage); color: var(--text-dark);">
                <ion-icon name="checkmark-done-circle-outline"></ion-icon>
            </div>
            <div class="stat-info-custom">
                <h3>{{ $totalSelesai }}</h3>
                <p>Telah Diverifikasi</p>
            </div>
        </div>
        <div class="stat-card-custom">
            <div class="stat-icon-custom" style="background-color: #fee2e2; color: #dc2626;">
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
                        <td style="color: var(--text-muted);">{{ $spj->updated_at->format('d M Y') }}</td>
                        <td>{{ $spj->user->name ?? 'Teknis' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($spj->kegiatan, 40) }}</td>
                        <td>
                            <span class="badge pending" style="font-size: 12px; padding: 6px 14px; background: var(--bg-sage-light); color: var(--text-dark); border-radius: 8px 16px 8px 16px; font-weight: 700;">Menunggu Pemeriksaan</span>
                        </td>
                        <td>
                            <a href="{{ route('umum.spj.show', $spj->id) }}" 
                               style="display: flex; align-items: center; justify-content: center; width: 38px; height: 38px; border-radius: 12px; text-decoration: none; color: white; background-color: var(--accent-orange); transition: all 0.2s;" 
                               title="Periksa"
                               onmouseover="this.style.backgroundColor='var(--accent-orange-hover)';" 
                               onmouseout="this.style.backgroundColor='var(--accent-orange)';">
                                <ion-icon name="eye-outline" style="font-size: 20px;"></ion-icon>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div style="text-align: center; padding: 48px 24px; color: var(--text-muted); font-size: 15px; font-weight: 500;">
                                <ion-icon name="checkmark-done-circle-outline" style="font-size: 56px; color: var(--bg-sage); margin-bottom: 16px; display: block; margin-left: auto; margin-right: auto;"></ion-icon>
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
<section class="dash-panel" style="animation: slideUp 0.7s ease-out forwards; animation-delay: 0.3s; margin-top: 2rem; background: var(--bg-sage); box-shadow: 0 12px 40px rgba(174, 193, 166, 0.4);">
    @if(isset($recentSpjs) && $recentSpjs->isNotEmpty())
    @php $topSpj = $recentSpjs->first(); @endphp
    <div class="dash-panel-header" style="background: var(--bg-sage); border-bottom: 2px dashed rgba(255,255,255,0.3);">
        <div>
            <h2 title="{{ $topSpj->kegiatan }}">Alur Kegiatan: {{ Str::limit($topSpj->kegiatan, 40) }}</h2>
            <p style="color: var(--text-dark);">Posisi dokumen terakhir yang Anda proses</p>
        </div>
        <a href="{{ route('umum.spj.show', $topSpj->id) }}" style="color: var(--accent-orange); background: white; padding: 8px 16px; border-radius: 999px;">Lihat detail &rarr;</a>
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
            <div style="position: absolute; top: 22px; left: 40px; right: 40px; height: 4px; background: rgba(255,255,255,0.4); z-index: 1; border-radius: 4px;"></div>
            
            <!-- TEKNIS -->
            <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--text-dark); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; box-shadow: 0 0 0 8px var(--bg-sage);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <strong style="font-family: 'Fredoka', sans-serif; font-size: 15px; font-weight: 600; color: var(--text-dark);">Teknis</strong>
            </div>

            <!-- UMUM -->
            <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                <div style="width: 48px; height: 48px; border-radius: 50%; background: {{ $isPpk ? 'var(--text-dark)' : ($isUmum ? 'var(--accent-orange)' : 'white') }}; color: {{ $isPpk || $isUmum ? 'white' : 'var(--bg-sage)' }}; display: flex; align-items: center; justify-content: center; font-weight: bold; box-shadow: 0 0 0 8px var(--bg-sage);">
                    @if($isPpk) <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    @else 2 @endif
                </div>
                <strong style="font-family: 'Fredoka', sans-serif; font-size: 15px; font-weight: 600; color: {{ $isUmum ? 'var(--text-dark)' : 'rgba(44,63,45,0.5)' }};">Umum</strong>
            </div>

            <!-- PPK -->
            <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                <div style="width: 48px; height: 48px; border-radius: 50%; background: {{ $isBendahara ? 'var(--text-dark)' : ($isPpk ? 'var(--accent-orange)' : 'white') }}; color: {{ $isBendahara || $isPpk ? 'white' : 'var(--bg-sage)' }}; display: flex; align-items: center; justify-content: center; font-weight: bold; box-shadow: 0 0 0 8px var(--bg-sage);">
                    @if($isBendahara) <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    @else 3 @endif
                </div>
                <strong style="font-family: 'Fredoka', sans-serif; font-size: 15px; font-weight: 600; color: {{ $isPpk ? 'var(--text-dark)' : 'rgba(44,63,45,0.5)' }};">PPK</strong>
            </div>

            <!-- BENDAHARA -->
            <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                <div style="width: 48px; height: 48px; border-radius: 50%; background: {{ $isDone ? 'var(--text-dark)' : ($isBendahara ? 'var(--accent-orange)' : 'white') }}; color: {{ $isDone || $isBendahara ? 'white' : 'var(--bg-sage)' }}; display: flex; align-items: center; justify-content: center; font-weight: bold; box-shadow: 0 0 0 8px var(--bg-sage);">
                    @if($isDone) <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    @else 4 @endif
                </div>
                <strong style="font-family: 'Fredoka', sans-serif; font-size: 15px; font-weight: 600; color: {{ $isBendahara ? 'var(--text-dark)' : 'rgba(44,63,45,0.5)' }};">Bendahara</strong>
            </div>
            
            <!-- ARSIP / SELESAI -->
            <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                <div style="width: 48px; height: 48px; border-radius: 50%; background: {{ $isDone ? 'var(--text-dark)' : 'white' }}; color: {{ $isDone ? 'white' : 'var(--bg-sage)' }}; display: flex; align-items: center; justify-content: center; font-weight: bold; box-shadow: 0 0 0 8px var(--bg-sage);">
                    @if($isDone) <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    @else 5 @endif
                </div>
                <strong style="font-family: 'Fredoka', sans-serif; font-size: 15px; font-weight: 600; color: {{ $isDone ? 'var(--text-dark)' : 'rgba(44,63,45,0.5)' }};">Arsip</strong>
            </div>

        </div>
    </div>
    @else
    <div class="dash-panel-header" style="background: var(--bg-sage); border-bottom: 2px dashed rgba(255,255,255,0.3);">
        <div>
            <h2 style="font-family: 'Fredoka', sans-serif;">Alur SPJ</h2>
            <p style="color: var(--text-dark);">Belum ada SPJ untuk ditampilkan alurnya.</p>
        </div>
    </div>
    @endif
</section>
@endsection
