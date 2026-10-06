@extends('layouts.bendahara')

@php
$title = 'Dashboard Bendahara';
$subtitle = 'Ringkasan antrean pemeriksaan SPJ.';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/bendahara/dashboard.css'])
    <style>
        .dash-panel {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #e2e8f0;
            overflow: hidden;
            width: 100%;
        }

        .dash-panel-header {
            padding: 24px;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dash-panel-header h2 {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 4px 0;
        }

        .dash-panel-header p {
            color: #64748b;
            font-size: 13px;
            margin: 0;
        }

        .dash-panel-header a {
            color: #3b82f6;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }

        .dash-panel-header a:hover {
            color: #2563eb;
        }

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
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 2px solid #e2e8f0;
        }

        .custom-table td {
            padding: 16px 24px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 13px;
            vertical-align: middle;
        }

        .custom-table tbody tr {
            transition: background-color 0.15s;
        }

        .custom-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .custom-table tbody tr:last-child td {
            border-bottom: none;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.2s;
            background-color: #10b981;
            color: white;
            box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
        }

        .btn-action:hover {
            background-color: #059669;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(16, 185, 129, 0.25);
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #e2e8f0;
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }
    </style>
@endpush

<div style="padding: 32px 40px; box-sizing: border-box; width: 100%;">
    <div class="page-heading" style="margin-bottom: 32px;">
        <h2 style="font-size: 24px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">Dashboard</h2>
        <p style="color: #64748b; font-size: 14px; margin: 0;">Selamat datang di Panel Bendahara.</p>
    </div>

    <!-- STATISTIK -->
    <div class="stats-container">
        <div class="stat-card">
            <div style="width: 56px; height: 56px; border-radius: 12px; background-color: #eff6ff; color: #3b82f6; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                <ion-icon name="download-outline"></ion-icon>
            </div>
            <div>
                <span style="display: block; color: #64748b; font-size: 13px; font-weight: 600; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Menunggu Diperiksa ({{ $totalAntrean }} SPJ)</span>
                <h3 style="font-size: 24px; font-weight: 700; color: #0f172a; margin: 0;">Rp {{ number_format($nilaiAntrean, 0, ',', '.') }}</h3>
            </div>
        </div>
        
        <div class="stat-card">
            <div style="width: 56px; height: 56px; border-radius: 12px; background-color: #f0fdf4; color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                <ion-icon name="checkmark-circle-outline"></ion-icon>
            </div>
            <div>
                <span style="display: block; color: #64748b; font-size: 13px; font-weight: 600; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Telah Diverifikasi ({{ $totalSelesai }} SPJ)</span>
                <h3 style="font-size: 24px; font-weight: 700; color: #0f172a; margin: 0;">Rp {{ number_format($nilaiSelesai, 0, ',', '.') }}</h3>
            </div>
        </div>
        
        <div class="stat-card">
            <div style="width: 56px; height: 56px; border-radius: 12px; background-color: #fef2f2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                <ion-icon name="close-circle-outline"></ion-icon>
            </div>
            <div>
                <span style="display: block; color: #64748b; font-size: 13px; font-weight: 600; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">Dikembalikan (Revisi)</span>
                <h3 style="font-size: 24px; font-weight: 700; color: #0f172a; margin: 0;">{{ $totalRevisi }} Dokumen</h3>
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
            <a href="{{ route('bendahara.spj.index') }}">Lihat Semua &rarr;</a>
        </div>
        
        <div style="overflow-x: auto;">
            <table class="custom-table">
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
                        <td style="color: #64748b;">{{ $spj->updated_at->format('d M Y') }}</td>
                        <td><strong style="color: #0f172a;">{{ $spj->nomor_spj }}</strong></td>
                        <td>{{ $spj->user->name ?? 'Teknis' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($spj->kegiatan, 40) }}</td>
                        <td>
                            <span class="badge pending" style="background: #fffbeb; color: #b45309; padding: 6px 12px; border-radius: 9999px; font-size: 11px; font-weight: 600;">Menunggu Pemeriksaan</span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px; align-items: center;">
                                <a href="{{ route('bendahara.spj.show', $spj->id) }}" class="btn-action">
                                    <ion-icon name="eye-outline" style="margin-right: 4px; font-size: 16px;"></ion-icon> Periksa
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #64748b; padding: 48px 24px;">
                            <ion-icon name="folder-open-outline" style="font-size: 48px; color: #cbd5e1; margin-bottom: 12px; display: block; margin-left: auto; margin-right: auto;"></ion-icon>
                            Tidak ada antrean SPJ saat ini. Semua sudah diperiksa! 🎉
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
