@extends('layouts.umum')

@php
$title = 'Daftar Antrean SPJ';
$subtitle = 'Kelola dan periksa seluruh SPJ yang diajukan oleh staf Teknis.';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/umum/riwayat.css'])
@endpush


<div class="page-heading">
    <div>
        <h2>Semua Antrean SPJ</h2>
        <p>SPJ yang berstatus 'Menunggu' harus segera diperiksa.</p>
    </div>
</div>

<div class="panel" style="margin-bottom: 20px; padding: 20px;">
    <form action="{{ route('umum.spj.index') }}" method="GET" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 200px;">
            <label style="display: block; font-size: 11px; color: #64748b; margin-bottom: 5px; font-weight: 600;">Cari (No. SPJ / Nama / Kegiatan)</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik kata kunci..." style="width: 100%; padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-family: inherit; font-size: 13px;">
        </div>
        <div>
            <label style="display: block; font-size: 11px; color: #64748b; margin-bottom: 5px; font-weight: 600;">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" style="padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-family: inherit; font-size: 13px;">
        </div>
        <div>
            <label style="display: block; font-size: 11px; color: #64748b; margin-bottom: 5px; font-weight: 600;">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" style="padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-family: inherit; font-size: 13px;">
        </div>
        <div>
            <button type="submit" class="btn-primary" style="height: 36px; padding: 0 16px; font-size: 12px;">Terapkan Filter</button>
        </div>
        @if(request()->hasAny(['search', 'start_date', 'end_date']))
        <div>
            <a href="{{ route('umum.spj.index') }}" style="display: inline-block; padding: 8px 16px; font-size: 12px; color: #ef4444; text-decoration: none; font-weight: 600; border: 1px solid #fee2e2; border-radius: 8px; background: #fef2f2;">Reset</a>
        </div>
        @endif
    </form>
</div>

<div class="panel">
    <div style="overflow-x: auto;">
        <table class="table">
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
                    <td>{{ $spj->updated_at->format('d M Y') }}</td>
                    <td><strong>{{ $spj->nomor_spj }}</strong></td>
                    <td>{{ $spj->user->name ?? 'Teknis' }}</td>
                    <td>{{ $spj->kegiatan }}</td>
                    <td>Rp {{ number_format($spj->nilai, 0, ',', '.') }}</td>
                    <td>
                        @php
                            $badgeClass = 'pending';
                            $statusText = 'Menunggu';
                            
                            if($spj->status == 'diajukan') {
                                $badgeClass = 'pending';
                                $statusText = 'Menunggu';
                            } elseif($spj->status == 'revisi_umum') {
                                $badgeClass = 'returned';
                                $statusText = 'Dikembalikan';
                            } elseif(in_array($spj->status, ['disetujui_umum', 'disetujui_ppk', 'disetujui_ppspm', 'selesai'])) {
                                $badgeClass = 'completed';
                                $statusText = 'Disetujui';
                            }
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                    </td>
                    <td>
                        <a href="{{ route('umum.spj.show', $spj->id) }}" class="btn-primary" style="padding: 0.25rem 0.75rem; font-size: 0.875rem; text-decoration: none; {{ $spj->status == 'diajukan' ? '' : 'background-color: #6b7280;' }}">
                            {{ $spj->status == 'diajukan' ? 'Periksa' : 'Detail' }}
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

