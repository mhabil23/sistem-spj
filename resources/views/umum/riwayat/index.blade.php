@extends('layouts.umum')

@php
$title = 'Riwayat Pemeriksaan SPJ';
$subtitle = 'Arsip seluruh SPJ yang pernah Anda verifikasi (diterima atau ditolak).';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/umum/riwayat.css'])
    <style>
        .filter-wrapper {
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 24px;
        }

        .filter-form {
            display: flex;
            gap: 16px;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
            min-width: 200px;
        }

        .filter-label {
            font-size: 12px;
            font-weight: 600;
            color: #475569;
        }

        .filter-input {
            height: 42px;
            padding: 0 16px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background-color: #ffffff;
            color: #0f172a;
            font-size: 13px;
            font-family: inherit;
            transition: all 0.2s;
        }

        .filter-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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
        
        .empty-state {
            text-align: center;
            padding: 48px 24px;
            color: #64748b;
            font-size: 14px;
        }

        .index-panel {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }
    </style>
@endpush

<div style="padding: 32px 40px; box-sizing: border-box; max-width: 100%;">
    <div class="page-heading" style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h2 style="font-size: 24px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">Riwayat Pemeriksaan</h2>
            <p style="color: #64748b; font-size: 14px; margin: 0;">Seluruh dokumen yang sudah melewati meja verifikasi Umum.</p>
        </div>
    </div>

    <div class="index-panel">
        <div class="filter-wrapper">
            <form action="{{ route('umum.riwayat.index') }}" method="GET" class="filter-form">
                <div class="filter-group" style="flex: 2;">
                    <label class="filter-label">Cari (Nama / Kegiatan)</label>
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
                    <a href="{{ route('umum.riwayat.index') }}" class="btn-reset">Reset</a>
                </div>
                @endif
            </form>
        </div>

        <div style="overflow-x: auto;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Terakhir Diupdate</th>
                        <th>Kegiatan</th>
                        <th>Pengaju</th>
                        <th>Status Saat Ini</th>
                        <th>Catatan (Jika Revisi)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatSpjs as $spj)
                    <tr>
                        <td style="color: #64748b;">{{ $spj->updated_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('umum.spj.show', $spj->id) }}" style="color: #0b5a93; font-weight: 600; text-decoration: none;">{{ \Illuminate\Support\Str::limit($spj->kegiatan, 40) }}</a>
                        </td>
                        <td>{{ $spj->user->name ?? 'Teknis' }}</td>
                        <td>
                            @php
                                if(str_contains($spj->status, 'revisi')) {
                                    $badgeClass = 'returned';
                                    $statusText = 'Dikembalikan (' . strtoupper(explode('_', $spj->status)[1] ?? '') . ')';
                                    $badgeStyle = 'background: #fef2f2; color: #991b1b; border: 1px solid #fecaca;';
                                } elseif($spj->status == 'selesai') {
                                    $badgeClass = 'completed';
                                    $statusText = 'Selesai (Arsip)';
                                    $badgeStyle = 'background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0;';
                                } else {
                                    $badgeClass = 'approved';
                                    $statusText = 'Disetujui Lanjut';
                                    $badgeStyle = 'background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe;';
                                }
                            @endphp
                            <span class="badge {{ $badgeClass }}" style="display: inline-block; font-size: 10px; padding: 4px 10px; border-radius: 999px; font-weight: 600; margin-bottom: 5px; {{ $badgeStyle }}">{{ $statusText }}</span>
                            
                            @if($spj->diajukan_at)
                            @php
                                $diff = \Carbon\Carbon::parse($spj->diajukan_at)->diff($spj->updated_at);
                                $timeStr = '';
                                if($diff->d > 0) $timeStr .= $diff->d . 'h ';
                                if($diff->h > 0) $timeStr .= $diff->h . 'j ';
                                $timeStr .= $diff->i . 'm';
                            @endphp
                            <small style="color: #64748b; font-size: 10px; display: flex; align-items: center; gap: 4px; font-weight: 500;">
                                <ion-icon name="time-outline" style="font-size: 12px;"></ion-icon> SLA: {{ $timeStr }}
                            </small>
                            @endif
                        </td>
                        <td>
                            @if($spj->status == 'revisi_umum' && $spj->catatan_revisi)
                                <span style="color: #dc2626; font-size: 12px; line-height: 1.4; display: block; max-width: 200px; white-space: normal;">{{ \Illuminate\Support\Str::limit($spj->catatan_revisi, 50) }}</span>
                            @else
                                <span style="color: #9ca3af;">-</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('umum.riwayat.destroy', $spj->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus permanen riwayat pengajuan ini? Tindakan ini tidak dapat dibatalkan.');" style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        style="display: flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 8px; background-color: #ef4444; color: white; border: none; cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 4px rgba(239,68,68,0.25);" 
                                        title="Hapus Permanen"
                                        onmouseover="this.style.transform='translateY(-2px)';" 
                                        onmouseout="this.style.transform='translateY(0)';">
                                    <ion-icon name="trash-outline" style="font-size: 18px;"></ion-icon>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <ion-icon name="folder-open-outline" style="font-size: 48px; color: #cbd5e1; margin-bottom: 12px; display: block; margin-left: auto; margin-right: auto;"></ion-icon>
                                Belum ada riwayat pemeriksaan SPJ.
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

