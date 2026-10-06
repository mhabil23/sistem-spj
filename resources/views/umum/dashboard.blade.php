@extends('layouts.umum')

@php
$title = 'Dashboard Umum';
$subtitle = 'Ringkasan antrean pemeriksaan SPJ.';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/umum/dashboard.css'])
@endpush


<!-- STATISTIK -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background-color: #dbeafe; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <ion-icon name="download-outline"></ion-icon>
        </div>
        <div class="stat-info">
            <h3>{{ $totalAntrean }}</h3>
            <p>Menunggu Diperiksa</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background-color: #d1fae5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <ion-icon name="checkmark-circle-outline"></ion-icon>
        </div>
        <div class="stat-info">
            <h3>{{ $totalSelesai }}</h3>
            <p>Telah Diverifikasi</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background-color: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <ion-icon name="close-circle-outline"></ion-icon>
        </div>
        <div class="stat-info">
            <h3>{{ $totalRevisi }}</h3>
            <p>Dikembalikan (Revisi)</p>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem; margin-top: 1.5rem;">
    <!-- DAFTAR ANTREAN SPJ -->
    <div class="panel">
        <div class="panel-header">
            <div>
                <h2>Antrean SPJ Terbaru</h2>
                <p>Daftar SPJ yang perlu segera Anda periksa.</p>
            </div>
            <a href="{{ route('umum.spj.index') }}">Lihat Semua →</a>
        </div>
        
        <div style="overflow-x: auto;">
            <table class="table">
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
                        <td>{{ $spj->updated_at->format('d M Y') }}</td>
                        <td>{{ $spj->user->name ?? 'Teknis' }}</td>
                        <td>{{ Str::limit($spj->kegiatan, 50) }}</td>
                        <td>
                            <span class="badge pending">Menunggu Pemeriksaan</span>
                        </td>
                        <td>
                            <a href="{{ route('umum.spj.show', $spj->id) }}" class="btn-primary" style="padding: 0.25rem 0.75rem; font-size: 0.875rem; text-decoration: none;">Periksa</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #6b7280; padding: 2rem;">Tidak ada antrean SPJ saat ini. Semua sudah diperiksa! 🎉</td>
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
            <div style="position: absolute; top: 20px; left: 40px; right: 40px; height: 4px; background: #e2e8f0; z-index: 1; border-radius: 4px;"></div>
            
            <!-- TEKNIS -->
            <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: var(--success, #10b981); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; box-shadow: 0 0 0 6px white, 0 4px 10px rgba(0,0,0,0.1);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <strong style="font-size: 0.9rem; color: #0f172a;">Teknis</strong>
            </div>

            <!-- UMUM -->
            <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: {{ $isPpk ? 'var(--success, #10b981)' : ($isUmum ? 'var(--primary, #3b82f6)' : '#f1f5f9') }}; color: {{ $isPpk || $isUmum ? 'white' : '#94a3b8' }}; display: flex; align-items: center; justify-content: center; font-weight: bold; box-shadow: 0 0 0 6px white, 0 4px 10px rgba(0,0,0,0.1);">
                    @if($isPpk) <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    @else 2 @endif
                </div>
                <strong style="font-size: 0.9rem; color: {{ $isUmum ? '#0f172a' : '#94a3b8' }};">Umum</strong>
            </div>

            <!-- PPK -->
            <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: {{ $isBendahara ? 'var(--success, #10b981)' : ($isPpk ? 'var(--primary, #3b82f6)' : '#f1f5f9') }}; color: {{ $isBendahara || $isPpk ? 'white' : '#94a3b8' }}; display: flex; align-items: center; justify-content: center; font-weight: bold; box-shadow: 0 0 0 6px white, 0 4px 10px rgba(0,0,0,0.1);">
                    @if($isBendahara) <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    @else 3 @endif
                </div>
                <strong style="font-size: 0.9rem; color: {{ $isPpk ? '#0f172a' : '#94a3b8' }};">PPK</strong>
            </div>

            <!-- BENDAHARA -->
            <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: {{ $isDone ? 'var(--success, #10b981)' : ($isBendahara ? 'var(--primary, #3b82f6)' : '#f1f5f9') }}; color: {{ $isDone || $isBendahara ? 'white' : '#94a3b8' }}; display: flex; align-items: center; justify-content: center; font-weight: bold; box-shadow: 0 0 0 6px white, 0 4px 10px rgba(0,0,0,0.1);">
                    @if($isDone) <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    @else 4 @endif
                </div>
                <strong style="font-size: 0.9rem; color: {{ $isBendahara ? '#0f172a' : '#94a3b8' }};">Bendahara</strong>
            </div>
            
            <!-- ARSIP / SELESAI -->
            <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: {{ $isDone ? 'var(--success, #10b981)' : '#f1f5f9' }}; color: {{ $isDone ? 'white' : '#94a3b8' }}; display: flex; align-items: center; justify-content: center; font-weight: bold; box-shadow: 0 0 0 6px white, 0 4px 10px rgba(0,0,0,0.1);">
                    @if($isDone) <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    @else 5 @endif
                </div>
                <strong style="font-size: 0.9rem; color: {{ $isDone ? '#0f172a' : '#94a3b8' }};">Arsip</strong>
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
