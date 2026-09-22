@extends('layouts.PPK')

@php
$title = 'Daftar Antrean SPJ';
$subtitle = 'Kelola dan periksa seluruh SPJ yang diajukan oleh staf Teknis.';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/ppk/riwayat.css'])
@endpush


<div class="page-heading">
    <div>
        <h2>Semua Antrean SPJ</h2>
        <p>SPJ yang berstatus 'Menunggu' harus segera diperiksa.</p>
    </div>
</div>

<div class="panel" style="margin-bottom: 20px; padding: 20px;">
    <form action="{{ route('ppk.spj.index') }}" method="GET" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
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
            <a href="{{ route('ppk.spj.index') }}" style="display: inline-block; padding: 8px 16px; font-size: 12px; color: #ef4444; text-decoration: none; font-weight: 600; border: 1px solid #fee2e2; border-radius: 8px; background: #fef2f2;">Reset</a>
        </div>
        @endif
    </form>
</div>

<div class="panel">
    <div style="padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0;">
        <h2 style="font-size: 1rem; color: #1e293b; margin: 0;">Daftar Kotak Masuk</h2>
        <button type="button" onclick="submitBulkVerify()" class="btn-primary" style="background-color: #8b5cf6; padding: 0.5rem 1rem; font-size: 0.875rem; border: none; cursor: pointer; display: flex; align-items: center; gap: 5px;">
            <ion-icon name="checkmark-done-outline"></ion-icon> Setujui Terpilih
        </button>
    </div>
    <div style="overflow-x: auto;">
        <form id="bulkVerifyForm" action="{{ route('ppk.spj.bulk-verify') }}" method="POST">
            @csrf
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">
                            <input type="checkbox" id="selectAll" onclick="toggleSelectAll()" style="cursor: pointer;">
                        </th>
                        <th>Tanggal Pengajuan</th>
                    <th>Nomor SPJ</th>
                    <th>Pengaju</th>
                    <th>Kegiatan</th>
                    <th>Nilai (Rp)</th>
                    <th>Status PPK</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($spjs as $spj)
                <tr>
                    <td style="text-align: center;">
                        <input type="checkbox" name="spj_ids[]" value="{{ $spj->id }}" class="spj-checkbox" style="cursor: pointer;">
                    </td>
                    <td>{{ $spj->updated_at->format('d M Y') }}</td>
                    <td><strong>{{ $spj->nomor_spj }}</strong></td>
                    <td>{{ $spj->user->name ?? 'Teknis' }}</td>
                    <td>{{ $spj->kegiatan }}</td>
                    <td>Rp {{ number_format($spj->nilai, 0, ',', '.') }}</td>
                    <td>
                        @php
                            $badgeClass = 'pending';
                            $statusText = 'Menunggu';
                            
                            if($spj->status == 'disetujui_umum') {
                                $badgeClass = 'pending';
                                $statusText = 'Menunggu PPK';
                            } elseif($spj->status == 'revisi_ppk') {
                                $badgeClass = 'returned';
                                $statusText = 'Dikembalikan';
                            } elseif(in_array($spj->status, ['disetujui_ppk', 'disetujui_ppspm', 'selesai'])) {
                                $badgeClass = 'completed';
                                $statusText = 'Disetujui';
                            }
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                    </td>
                    <td>
                        <a href="{{ route('ppk.spj.show', $spj->id) }}" class="btn-primary" style="padding: 0.25rem 0.75rem; font-size: 0.875rem; text-decoration: none; {{ $spj->status == 'disetujui_umum' ? 'background-color: #8b5cf6;' : 'background-color: #6b7280;' }}">
                            {{ $spj->status == 'disetujui_umum' ? 'Periksa' : 'Detail' }}
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #6b7280; padding: 2rem;">Tidak ada dokumen SPJ yang mengantre.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </form>
    </div>
</div>

<script>
    function toggleSelectAll() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.spj-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = selectAll.checked);
    }

    function submitBulkVerify() {
        const checkboxes = document.querySelectorAll('.spj-checkbox:checked');
        if (checkboxes.length === 0) {
            alert('Pilih setidaknya satu SPJ untuk disetujui.');
            return;
        }
        
        if (confirm('Anda yakin ingin menyetujui ' + checkboxes.length + ' SPJ yang terpilih?')) {
            document.getElementById('bulkVerifyForm').submit();
        }
    }
</script>

@endsection

