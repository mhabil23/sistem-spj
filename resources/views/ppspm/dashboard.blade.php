@extends('layouts.ppspm')

@php
$title = 'Dashboard ppspm';
$subtitle = 'Ringkasan antrean pemeriksaan SPJ.';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/ppspm/dashboard.css'])
@endpush


<!-- STATISTIK -->
<div class="stats-grid" style="grid-template-columns: repeat(3, 1fr);">
    <div class="stat-card">
        <div class="stat-icon" style="background-color: #dbeafe; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <ion-icon name="download-outline"></ion-icon>
        </div>
        <div class="stat-info">
            <span style="display: block; color: #64748b; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Menunggu Diperiksa ({{ $totalAntrean }} SPJ)</span>
            <h3 style="font-size: 1.5rem; color: #1e293b; margin: 0;">Rp {{ number_format($nilaiAntrean, 0, ',', '.') }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background-color: #d1fae5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <ion-icon name="checkmark-circle-outline"></ion-icon>
        </div>
        <div class="stat-info">
            <span style="display: block; color: #64748b; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Telah Diverifikasi ({{ $totalSelesai }} SPJ)</span>
            <h3 style="font-size: 1.5rem; color: #1e293b; margin: 0;">Rp {{ number_format($nilaiSelesai, 0, ',', '.') }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background-color: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <ion-icon name="close-circle-outline"></ion-icon>
        </div>
        <div class="stat-info">
            <span style="display: block; color: #64748b; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Dikembalikan (Revisi)</span>
            <h3 style="font-size: 1.5rem; color: #1e293b; margin: 0;">{{ $totalRevisi }} Dokumen</h3>
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
            <a href="{{ route('ppspm.spj.index') }}">Lihat Semua →</a>
        </div>
        
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal Pengajuan</th>
                        <th>Nomor SPJ</th>
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
                        <td><strong>{{ $spj->nomor_spj }}</strong></td>
                        <td>{{ $spj->user->name ?? 'Teknis' }}</td>
                        <td>{{ $spj->kegiatan }}</td>
                        <td>
                            <span class="badge pending">Menunggu Pemeriksaan</span>
                        </td>
                        <td>
                            <a href="{{ route('ppspm.spj.show', $spj->id) }}" class="btn-primary" style="padding: 0.25rem 0.75rem; font-size: 0.875rem; text-decoration: none;">Periksa</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #6b7280; padding: 2rem;">Tidak ada antrean SPJ saat ini. Semua sudah diperiksa! 🎉</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection



