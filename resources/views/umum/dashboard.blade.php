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
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 2px solid #e2e8f0;
        }

        .custom-table td {
            padding: 18px 24px;
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

        .dash-panel {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .dash-panel-header {
            padding: 20px 24px;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .dash-panel-header h2 {
            font-size: 16px;
            color: #0f172a;
            margin: 0;
            font-weight: 700;
        }

        .dash-panel-header p {
            font-size: 12px;
            color: #64748b;
            margin: 4px 0 0 0;
        }

        .dash-panel-header a {
            font-size: 13px;
            font-weight: 600;
            color: #3b82f6;
            text-decoration: none;
        }

        .dash-panel-header a:hover {
            text-decoration: underline;
        }

        .stat-card-custom {
            background: #ffffff;
            border-radius: 12px;
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #e2e8f0;
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
        }

        .stat-info-custom h3 {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            line-height: 1.2;
        }

        .stat-info-custom p {
            font-size: 13px;
            color: #64748b;
            margin: 4px 0 0 0;
            font-weight: 500;
        }
    </style>
@endpush

<div style="padding: 32px 40px; box-sizing: border-box; max-width: 100%;">
    <!-- STATISTIK -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-bottom: 32px;">
        <div class="stat-card-custom">
            <div class="stat-icon-custom" style="background-color: #eff6ff; color: #3b82f6;">
                <ion-icon name="download-outline"></ion-icon>
            </div>
            <div class="stat-info-custom">
                <h3>{{ $totalAntrean }}</h3>
                <p>Menunggu Diperiksa</p>
            </div>
        </div>
        <div class="stat-card-custom">
            <div class="stat-icon-custom" style="background-color: #f0fdf4; color: #10b981;">
                <ion-icon name="checkmark-circle-outline"></ion-icon>
            </div>
            <div class="stat-info-custom">
                <h3>{{ $totalSelesai }}</h3>
                <p>Telah Diverifikasi</p>
            </div>
        </div>
        <div class="stat-card-custom">
            <div class="stat-icon-custom" style="background-color: #fef2f2; color: #ef4444;">
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
                            <span class="badge pending" style="font-size: 10px; padding: 6px 12px; background: #fffbeb; color: #b45309; border-radius: 999px; font-weight: 600;">Menunggu Pemeriksaan</span>
                        </td>
                        <td>
                            <a href="{{ route('umum.spj.show', $spj->id) }}" 
                               style="display: flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 8px; text-decoration: none; color: white; background-color: #10b981; box-shadow: 0 2px 4px rgba(16,185,129,0.25); transition: all 0.2s;" 
                               title="Periksa"
                               onmouseover="this.style.transform='translateY(-2px)';" 
                               onmouseout="this.style.transform='translateY(0)';">
                                <ion-icon name="eye-outline" style="font-size: 18px;"></ion-icon>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
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
@endsection
