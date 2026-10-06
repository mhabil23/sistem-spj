@extends('layouts.teknis')

@php
$title = 'Riwayat SPJ';
$subtitle = 'Lihat riwayat pengajuan SPJ Anda yang telah diajukan.';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/teknis/spj.css'])
@endpush

<!-- PAGE HEADER -->
<div class="page-heading" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; animation: slideUp 0.4s ease-out forwards;">
    <div>
        <h2 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin-bottom: 0.25rem;">Riwayat SPJ</h2>
        <p style="color: #64748b; font-size: 0.95rem;">Daftar dokumen SPJ yang telah diajukan dan sedang/selesai diproses.</p>
    </div>
</div>

<!-- PANEL -->
<div class="spj-panel" style="animation-delay: 0.1s;">
    <div class="spj-panel-header">
        <div>
            <h2>Data Riwayat SPJ</h2>
            <p>Pantau status terkini dari SPJ yang Anda ajukan.</p>
        </div>
    </div>

    <div class="spj-panel-body" style="padding: 0;">
        <div class="spj-table-container">
            <table class="spj-table">
                <thead>
                    <tr>
                        <th>Uraian Kegiatan</th>
                        <th>Tanggal Diperbarui</th>
                        <th>Status Akhir</th>
                        <th style="text-align: right; padding-right: 2rem;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatSpjs as $spj)
                    <tr>
                        <td style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $spj->kegiatan }}">
                            {{ $spj->kegiatan }}
                        </td>
                        <td>{{ $spj->updated_at->format('d M Y, H:i') }}</td>
                        <td>
                            @php
                                $badgeClass = 'spj-badge-draft';
                                if(in_array($spj->status, ['selesai'])) $badgeClass = 'spj-badge-selesai';
                                elseif(in_array($spj->status, ['diajukan', 'disetujui_ppk'])) $badgeClass = 'spj-badge-diajukan';
                                elseif(in_array($spj->status, ['disetujui_ppspm'])) $badgeClass = 'spj-badge-disetujui';
                                elseif(in_array($spj->status, ['dikembalikan', 'revisi_bendahara', 'revisi_ppk', 'revisi_ppspm'])) $badgeClass = 'spj-badge-dikembalikan';
                            @endphp
                            <span class="spj-badge {{ $badgeClass }}">{{ str_replace('_', ' ', strtoupper($spj->status)) }}</span>
                        </td>
                        <td style="text-align: right; padding-right: 2rem;">
                            <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
                                <a href="{{ route('teknis.spj.show', $spj->id) }}" class="spj-action-link spj-action-detail">Detail</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center; padding: 4rem 2rem; color: #94a3b8;">
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 1rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                <span>Belum ada riwayat SPJ yang diajukan.</span>
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
