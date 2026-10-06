@extends('layouts.bendahara')

@php
$title = 'Riwayat Pemeriksaan SPJ';
$subtitle = 'Arsip seluruh SPJ yang pernah Anda verifikasi (diterima atau ditolak).';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/bendahara/riwayat.css'])
@endpush


<div class="page-heading" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h2>Riwayat Keputusan SPJ</h2>
        <p>Seluruh dokumen yang sudah melewati meja verifikasi bendahara.</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('bendahara.riwayat.export.csv') }}" class="btn-primary" style="background-color: #10b981; text-decoration: none; display: flex; align-items: center; gap: 5px; font-size: 0.875rem;">
            <ion-icon name="document-text-outline"></ion-icon> Unduh Excel
        </a>
        <a href="{{ route('bendahara.riwayat.export.pdf') }}" class="btn-primary" style="background-color: #ef4444; text-decoration: none; display: flex; align-items: center; gap: 5px; font-size: 0.875rem;">
            <ion-icon name="document-pdf-outline"></ion-icon> Unduh PDF
        </a>
    </div>
</div>

<div class="panel" style="margin-bottom: 20px; padding: 20px;">
    <form action="{{ route('bendahara.riwayat.index') }}" method="GET" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
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
            <a href="{{ route('bendahara.riwayat.index') }}" style="display: inline-block; padding: 8px 16px; font-size: 12px; color: #ef4444; text-decoration: none; font-weight: 600; border: 1px solid #fee2e2; border-radius: 8px; background: #fef2f2;">Reset</a>
        </div>
        @endif
    </form>
</div>

<div class="panel">
    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>Terakhir Diupdate</th>
                    <th>Nomor SPJ</th>
                    <th>Nomor SPM</th>
                    <th>Pengaju</th>
                    <th>Nilai (Rp)</th>
                    <th>Status Saat Ini</th>
                    <th>Bukti Transfer / Catatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayatSpjs as $spj)
                <tr>
                    <td>{{ $spj->updated_at->format('d M Y') }}</td>
                    <td><a href="{{ route('bendahara.spj.show', $spj->id) }}" style="color: #2563eb; font-weight: 600; text-decoration: none;">{{ $spj->nomor_spj }}</a><br><small style="color: #64748b;">{{ Str::limit($spj->kegiatan, 20) }}</small></td>
                    <td><strong>{{ $spj->nomor_spm ?? '-' }}</strong></td>
                    <td>{{ $spj->user->name ?? 'Teknis' }}</td>
                    <td>Rp {{ number_format($spj->nilai, 0, ',', '.') }}</td>
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
                        <span class="badge {{ $badgeClass }}" style="display: block; margin-bottom: 5px;">{{ $statusText }}</span>
                        
                        @if($spj->diajukan_at)
                        @php
                            $diff = \Carbon\Carbon::parse($spj->diajukan_at)->diff($spj->updated_at);
                            $timeStr = '';
                            if($diff->d > 0) $timeStr .= $diff->d . 'h ';
                            if($diff->h > 0) $timeStr .= $diff->h . 'j ';
                            $timeStr .= $diff->i . 'm';
                        @endphp
                        <small style="color: #64748b; font-size: 9px; display: flex; align-items: center; gap: 3px;">
                            <ion-icon name="time-outline" style="font-size: 11px;"></ion-icon> SLA: {{ $timeStr }}
                        </small>
                        @endif
                    </td>
                    <td>
                        @if($spj->status == 'revisi_bendahara' && $spj->catatan_revisi)
                            <span style="color: #dc2626; font-size: 0.75rem;">{{ Str::limit($spj->catatan_revisi, 30) }}</span>
                        @elseif($spj->bukti_transfer)
                            <span style="color: #0d9488; font-size: 0.85rem; font-weight: 600;">{{ $spj->bukti_transfer }}</span>
                        @else
                            <span style="color: #9ca3af;">-</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('bendahara.riwayat.destroy', $spj->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus permanen riwayat pengajuan ini?');" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center;" title="Hapus Permanen">
                                <ion-icon name="trash-outline" style="font-size: 1.25rem;"></ion-icon>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #6b7280; padding: 2rem;">Belum ada riwayat pencairan SPJ.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
