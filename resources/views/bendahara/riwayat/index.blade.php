@extends('layouts.bendahara')

@php
$title = 'Riwayat Pemeriksaan SPJ';
$subtitle = 'Arsip seluruh SPJ yang pernah Anda verifikasi (diterima atau ditolak).';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/bendahara/riwayat.css'])
    <style>
        .index-panel {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #e2e8f0;
            margin-bottom: 24px;
            overflow: hidden;
            width: 100%;
        }

        .filter-wrapper {
            padding: 24px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .filter-form {
            display: flex;
            gap: 16px;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

        .filter-label {
            display: block;
            font-size: 12px;
            color: #475569;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .filter-input {
            width: 100%;
            padding: 10px 16px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-family: inherit;
            font-size: 13px;
            transition: all 0.2s;
            outline: none;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02) inset;
        }

        .filter-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .btn-search {
            height: 42px;
            padding: 0 24px;
            font-size: 13px;
            font-weight: 600;
            background-color: #10b981;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
            display: inline-flex;
            align-items: center;
        }

        .btn-search:hover {
            background-color: #059669;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(16, 185, 129, 0.25);
        }

        .btn-reset {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 42px;
            padding: 0 20px;
            font-size: 13px;
            color: #ef4444;
            text-decoration: none;
            font-weight: 600;
            border: 1px solid #fca5a5;
            border-radius: 8px;
            background: #fef2f2;
            transition: all 0.2s;
        }

        .btn-reset:hover {
            background: #fee2e2;
            color: #dc2626;
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

        .btn-export {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 8px;
            color: white;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
        }

        .btn-export-excel {
            background-color: #10b981;
            box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
        }

        .btn-export-excel:hover {
            background-color: #059669;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(16, 185, 129, 0.25);
        }

        .btn-export-pdf {
            background-color: #ef4444;
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2);
        }

        .btn-export-pdf:hover {
            background-color: #dc2626;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(239, 68, 68, 0.25);
        }

        .empty-state {
            text-align: center;
            padding: 48px 24px;
            color: #64748b;
            font-size: 14px;
        }
    </style>
@endpush

<div style="padding: 32px 40px; box-sizing: border-box; width: 100%;">
    <div class="page-heading" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px;">
        <div>
            <h2 style="font-size: 24px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">Riwayat Keputusan SPJ</h2>
            <p style="color: #64748b; font-size: 14px; margin: 0;">Seluruh dokumen yang sudah melewati meja verifikasi bendahara.</p>
        </div>
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('bendahara.riwayat.export.csv') }}" class="btn-export btn-export-excel">
                <ion-icon name="document-text-outline" style="font-size: 16px;"></ion-icon> Unduh Excel
            </a>
            <a href="{{ route('bendahara.riwayat.export.pdf') }}" class="btn-export btn-export-pdf">
                <ion-icon name="document-pdf-outline" style="font-size: 16px;"></ion-icon> Unduh PDF
            </a>
        </div>
    </div>

    <div class="index-panel">
        <div class="filter-wrapper">
            <form action="{{ route('bendahara.riwayat.index') }}" method="GET" class="filter-form">
                <div class="filter-group" style="flex: 2;">
                    <label class="filter-label">Cari (No. SPJ / Nama / Kegiatan)</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik kata kunci pencarian..." class="filter-input">
                </div>
                <div class="filter-group">
                    <label class="filter-label">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="filter-input">
                </div>
                <div class="filter-group">
                    <label class="filter-label">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="filter-input">
                </div>
                <div>
                    <button type="submit" class="btn-search">
                        <ion-icon name="funnel-outline" style="margin-right: 6px; font-size: 16px;"></ion-icon> Terapkan Filter
                    </button>
                </div>
                @if(request()->hasAny(['search', 'start_date', 'end_date']))
                <div>
                    <a href="{{ route('bendahara.riwayat.index') }}" class="btn-reset">Reset</a>
                </div>
                @endif
            </form>
        </div>

        <div style="overflow-x: auto;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Terakhir Diupdate</th>
                        <th>Nomor SPJ / Kegiatan</th>
                        <th>Nomor SPM</th>
                        <th>Pengaju</th>
                        <th>Nilai (Rp)</th>
                        <th>Status Saat Ini</th>
                        <th>Bukti / Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatSpjs as $spj)
                    <tr>
                        <td style="color: #64748b;">{{ $spj->updated_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('bendahara.spj.show', $spj->id) }}" style="color: #3b82f6; font-weight: 700; text-decoration: none;">{{ $spj->nomor_spj }}</a><br>
                            <small style="color: #64748b;">{{ \Illuminate\Support\Str::limit($spj->kegiatan, 30) }}</small>
                        </td>
                        <td><strong style="color: #0f172a;">{{ $spj->nomor_spm ?? '-' }}</strong></td>
                        <td>{{ $spj->user->name ?? 'Teknis' }}</td>
                        <td style="font-weight: 500;">Rp {{ number_format($spj->nilai, 0, ',', '.') }}</td>
                        <td>
                            @php
                                if(str_contains($spj->status, 'revisi')) {
                                    $badgeClass = 'returned';
                                    $statusText = 'Dikembalikan';
                                } elseif($spj->status == 'selesai') {
                                    $badgeClass = 'completed';
                                    $statusText = 'Selesai (Cair)';
                                } else {
                                    $badgeClass = 'approved';
                                    $statusText = 'Disetujui Lanjut';
                                }
                            @endphp
                            <span class="badge {{ $badgeClass }}" style="display: inline-block; margin-bottom: 6px; font-size: 10px; padding: 4px 10px;">{{ $statusText }}</span>
                            
                            @if($spj->diajukan_at)
                            @php
                                $diff = \Carbon\Carbon::parse($spj->diajukan_at)->diff($spj->updated_at);
                                $timeStr = '';
                                if($diff->d > 0) $timeStr .= $diff->d . 'h ';
                                if($diff->h > 0) $timeStr .= $diff->h . 'j ';
                                $timeStr .= $diff->i . 'm';
                            @endphp
                            <div style="color: #64748b; font-size: 10px; display: flex; align-items: center; gap: 4px; font-weight: 500;">
                                <ion-icon name="time-outline" style="font-size: 12px;"></ion-icon> SLA: {{ $timeStr }}
                            </div>
                            @endif
                        </td>
                        <td>
                            @if($spj->status == 'revisi_bendahara' && $spj->catatan_revisi)
                                <span style="color: #dc2626; font-size: 12px; font-weight: 500;"><ion-icon name="alert-circle-outline" style="vertical-align: middle;"></ion-icon> {{ \Illuminate\Support\Str::limit($spj->catatan_revisi, 30) }}</span>
                            @elseif($spj->bukti_transfer)
                                <span style="color: #0d9488; font-size: 12px; font-weight: 600;"><ion-icon name="document-attach-outline" style="vertical-align: middle;"></ion-icon> {{ $spj->bukti_transfer }}</span>
                            @else
                                <span style="color: #94a3b8;">-</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px; align-items: center;">
                                <form action="{{ route('bendahara.riwayat.destroy', $spj->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus permanen riwayat pengajuan ini?');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            style="display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; border: none; background-color: #fee2e2; color: #ef4444; cursor: pointer; transition: all 0.2s;"
                                            onmouseover="this.style.backgroundColor='#fecaca'; this.style.transform='scale(1.05)';"
                                            onmouseout="this.style.backgroundColor='#fee2e2'; this.style.transform='scale(1)';"
                                            title="Hapus Permanen">
                                        <ion-icon name="trash-outline" style="font-size: 16px;"></ion-icon>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <ion-icon name="folder-open-outline" style="font-size: 48px; color: #cbd5e1; margin-bottom: 12px; display: block; margin-left: auto; margin-right: auto;"></ion-icon>
                                Belum ada riwayat pencairan SPJ.
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
