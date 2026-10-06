@extends('layouts.umum')

@php
$title = 'Daftar Antrean SPJ';
$subtitle = 'Kelola dan periksa seluruh SPJ yang diajukan oleh staf Teknis.';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/umum/riwayat.css'])
    <style>
        .index-panel {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #e2e8f0;
            margin-bottom: 24px;
            overflow: hidden;
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
            background-color: #10b981; /* Hijau */
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
            background-color: #059669; /* Hijau lebih gelap */
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
    </style>
@endpush


<div style="padding: 32px 40px;">
    <div class="page-heading" style="margin-bottom: 24px;">
        <div>
            <h2 style="font-size: 24px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">Semua Antrean SPJ</h2>
            <p style="color: #64748b; font-size: 14px;">SPJ yang berstatus 'Menunggu' harus segera diperiksa.</p>
        </div>
    </div>

    <div class="index-panel">
        <div class="filter-wrapper">
            <form action="{{ route('umum.spj.index') }}" method="GET" class="filter-form">
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
                    <a href="{{ route('umum.spj.index') }}" class="btn-reset">Reset</a>
                </div>
                @endif
            </form>
        </div>

        <div style="overflow-x: auto;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Tanggal Pengajuan</th>
                        <th>Nomor SPJ</th>
                        <th>Pengaju</th>
                        <th>Kegiatan</th>
                        <th>Nilai (Rp)</th>
                        <th>Status Umum</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($spjs as $spj)
                    <tr>
                        <td style="color: #64748b;">{{ $spj->updated_at->format('d M Y') }}</td>
                        <td><strong style="color: #0f172a;">{{ $spj->nomor_spj }}</strong></td>
                        <td>{{ $spj->user->name ?? 'Teknis' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($spj->kegiatan, 40) }}</td>
                        <td style="font-weight: 500;">Rp {{ number_format($spj->nilai, 0, ',', '.') }}</td>
                        <td>
                            @php
                                $badgeClass = 'pending';
                                $statusText = 'Menunggu';
                            } elseif($spj->status == 'revisi_umum') {
                                $badgeClass = 'returned';
                                $statusText = 'Dikembalikan';
                            } elseif($spj->status == 'disetujui_umum') {
                                $badgeClass = 'completed';
                                $statusText = 'Di Meja PPK';
                            } elseif($spj->status == 'disetujui_ppk') {
                                $badgeClass = 'completed';
                                $statusText = 'Di Meja PPSPM';
                            } elseif(in_array($spj->status, ['disetujui_ppspm', 'selesai'])) {
                                $badgeClass = 'completed';
                                $statusText = 'Diteruskan ke Bendahara';
                            }
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                    </td>
                    <td>
                        <a href="{{ route('umum.spj.show', $spj->id) }}" class="btn-primary" style="padding: 0.25rem 0.75rem; font-size: 0.875rem; text-decoration: none; {{ $spj->status == 'diajukan' ? '' : 'background-color: #6b7280;' }}">
                            {{ $spj->status == 'diajukan' ? 'Periksa' : 'Update Status' }}
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #6b7280; padding: 2rem;">Tidak ada data SPJ untuk ditampilkan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

