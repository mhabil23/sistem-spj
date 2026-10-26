@extends('layouts.bendahara')

@php
$title = 'Kotak Masuk & Antrean Pencairan SPJ';
@endphp

@push('styles')
    @vite(['resources/css/bendahara/spj.css'])
@endpush

@section('content')
<div style="padding: 28px 36px; box-sizing: border-box; width: 100%;">

    <!-- BREADCRUMB & PAGE HEADER -->
    <div class="bnd-header-wrapper" style="margin-bottom: 24px;">
        <div class="bnd-breadcrumb">
            <a href="{{ route('bendahara.dashboard') }}">
                <ion-icon name="home-outline"></ion-icon> Dashboard
            </a>
            <ion-icon name="chevron-forward-outline"></ion-icon>
            <span>Kotak Masuk SPJ</span>
        </div>

        <div class="bnd-page-header">
            <div class="bnd-header-title-box">
                <h1 style="font-size: 24px; font-weight: 800; color: #0f172a; margin: 0 0 6px 0;">
                    Kotak Masuk &amp; Antrean Pencairan SPJ
                </h1>
                <p style="color: #64748b; font-size: 13.5px; margin: 0;">
                    Kelola dan eksekusi pembayaran SPJ yang telah diverifikasi PPSPM atau sedang dalam proses otorisasi BRI QLOLA.
                </p>
            </div>
            <div class="bnd-header-actions">
                <a href="{{ route('bendahara.riwayat.index') }}" class="bnd-btn bnd-btn-back">
                    <ion-icon name="archive-outline"></ion-icon>
                    <span>Lihat Riwayat Selesai</span>
                </a>
            </div>
        </div>
    </div>

    <!-- STATS CARDS GRID -->
    <div class="bnd-stats-grid">
        <!-- STAT 1: TOTAL ANTREAN -->
        <div class="bnd-stat-card primary">
            <div class="bnd-stat-icon blue">
                <ion-icon name="file-tray-full-outline"></ion-icon>
            </div>
            <div class="bnd-stat-info">
                <div class="bnd-stat-label">Total Antrean Aktif</div>
                <div class="bnd-stat-value" style="color: #0284c7;">
                    {{ $totalAntrean }} Berkas
                </div>
                <div class="bnd-stat-sub">
                    <ion-icon name="alert-circle-outline"></ion-icon>
                    <span>Memerlukan tindakan Bendahara</span>
                </div>
            </div>
        </div>

        <!-- STAT 2: TOTAL NOMINAL PENCAIRAN -->
        <div class="bnd-stat-card success">
            <div class="bnd-stat-icon emerald">
                <ion-icon name="wallet-outline"></ion-icon>
            </div>
            <div class="bnd-stat-info">
                <div class="bnd-stat-label">Total Nilai Tagihan</div>
                <div class="bnd-stat-value" style="color: #059669;">
                    Rp {{ number_format($totalNilai, 0, ',', '.') }}
                </div>
                <div class="bnd-stat-sub">
                    <ion-icon name="cash-outline" style="color: #059669;"></ion-icon>
                    <span>Total dana yang harus dicairkan</span>
                </div>
            </div>
        </div>

        <!-- STAT 3: SIAP DICAIRKAN (PPSPM) -->
        <div class="bnd-stat-card">
            <div class="bnd-stat-icon amber">
                <ion-icon name="shield-checkmark-outline"></ion-icon>
            </div>
            <div class="bnd-stat-info">
                <div class="bnd-stat-label">Siap Dicairkan (PPSPM)</div>
                <div class="bnd-stat-value" style="color: #d97706;">
                    {{ $siapDicairkanCount }} Berkas
                </div>
                <div class="bnd-stat-sub">
                    <span>Nomor SPM telah diterbitkan</span>
                </div>
            </div>
        </div>

        <!-- STAT 4: MENUNGGU OTP QLOLA KPA -->
        <div class="bnd-stat-card">
            <div class="bnd-stat-icon blue">
                <ion-icon name="key-outline"></ion-icon>
            </div>
            <div class="bnd-stat-info">
                <div class="bnd-stat-label">Menunggu OTP KPA</div>
                <div class="bnd-stat-value" style="color: #2563eb;">
                    {{ $menungguOtpCount }} Berkas
                </div>
                <div class="bnd-stat-sub">
                    <span>Input QLOLA menunggu KPA</span>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER CARD MODERN -->
    <div class="bnd-filter-card">
        <form action="{{ route('bendahara.spj.index') }}" method="GET" class="bnd-filter-grid">
            <div class="bnd-form-group">
                <label class="bnd-form-label">Cari Kegiatan / No. SPJ / SPM / Pengaju</label>
                <div style="position: relative;">
                    <ion-icon name="search-outline" style="position: absolute; left: 12px; top: 12px; color: #94a3b8; font-size: 16px;"></ion-icon>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik kata kunci pencarian..." class="bnd-form-control" style="padding-left: 36px;">
                </div>
            </div>

            <div class="bnd-form-group">
                <label class="bnd-form-label">Status Pencairan</label>
                <select name="status" class="bnd-form-control">
                    <option value="">Semua Status Antrean</option>
                    <option value="disetujui_ppspm" {{ request('status') === 'disetujui_ppspm' ? 'selected' : '' }}>Siap Dicairkan (PPSPM)</option>
                    <option value="menunggu_otp_kpa" {{ request('status') === 'menunggu_otp_kpa' ? 'selected' : '' }}>Menunggu OTP QLOLA KPA</option>
                </select>
            </div>

            <div class="bnd-form-group">
                <label class="bnd-form-label">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="bnd-form-control">
            </div>

            <div class="bnd-form-group">
                <label class="bnd-form-label">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="bnd-form-control">
            </div>

            <div class="bnd-filter-actions">
                <button type="submit" class="bnd-btn" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: #ffffff; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25); height: 40px;">
                    <ion-icon name="funnel-outline"></ion-icon>
                    <span>Filter</span>
                </button>
                @if(request()->hasAny(['search', 'status', 'start_date', 'end_date']))
                <a href="{{ route('bendahara.spj.index') }}" class="bnd-btn bnd-btn-back" style="height: 40px; color: #e11d48; border-color: #fecdd3; background: #fff1f2;">
                    <span>Reset</span>
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- TABLE CARD MODERN -->
    <div class="bnd-table-card">
        <form id="bulkVerifyForm" action="{{ route('bendahara.spj.bulk-verify') }}" method="POST">
            @csrf
            <input type="hidden" name="bulk_action" id="bulkActionInput" value="setujui">

            <div class="bnd-table-toolbar">
                <div class="bnd-toolbar-left">
                    <h3 class="bnd-toolbar-title">Daftar Kotak Masuk Pencairan</h3>
                    <span class="bnd-toolbar-counter">{{ $spjs->count() }} Berkas</span>
                </div>

                <!-- DUA TOMBOL AKSI MASSAL -->
                <div class="bnd-bulk-actions">
                    <button type="button" onclick="submitBulkAction('otp_kpa')" class="bnd-btn-bulk-otp" title="Tandai berkas terpilih sebagai Menunggu OTP QLOLA KPA">
                        <ion-icon name="key-outline"></ion-icon>
                        <span>Massal: Menunggu OTP KPA</span>
                    </button>
                    <button type="button" onclick="submitBulkAction('setujui')" class="bnd-btn-bulk-cair" title="Selesaikan pencairan dana untuk berkas terpilih">
                        <ion-icon name="wallet-outline"></ion-icon>
                        <span>Massal: Selesaikan Pembayaran</span>
                    </button>
                </div>
            </div>

            <div style="overflow-x: auto;">
                <table class="bnd-table">
                    <thead>
                        <tr>
                            <th style="width: 44px; text-align: center;">
                                <input type="checkbox" id="selectAll" onclick="toggleSelectAll()" class="bnd-checkbox">
                            </th>
                            <th>Tanggal Pengajuan</th>
                            <th>No. SPJ / SPM</th>
                            <th>Uraian Kegiatan</th>
                            <th>Pelaksana / Pengaju</th>
                            <th style="text-align: right;">Nilai Tagihan (Rp)</th>
                            <th style="text-align: center;">Status Pencairan</th>
                            <th style="text-align: center; width: 110px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($spjs as $spj)
                        <tr>
                            <td style="text-align: center;">
                                <input type="checkbox" name="spj_ids[]" value="{{ $spj->id }}" class="spj-checkbox bnd-checkbox">
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <ion-icon name="calendar-outline" style="color: #94a3b8; font-size: 15px;"></ion-icon>
                                    <div>
                                        <div style="font-weight: 700; color: #1e293b;">{{ $spj->updated_at->format('d M Y') }}</div>
                                        <div style="font-size: 11px; color: #94a3b8;">{{ $spj->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <span style="display: inline-block; background: #eff6ff; color: #1d4ed8; padding: 2px 8px; border-radius: 6px; font-size: 11.5px; font-weight: 700; border: 1px solid #bfdbfe;">
                                        {{ $spj->nomor_spj ?: '-' }}
                                    </span>
                                    @if($spj->nomor_spm)
                                    <div style="font-size: 11px; color: #64748b; margin-top: 3px;">
                                        SPM: <strong>{{ $spj->nomor_spm }}</strong>
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td style="max-width: 320px;">
                                <div style="font-weight: 700; color: #0f172a; line-height: 1.4; margin-bottom: 2px;" title="{{ $spj->kegiatan }}">
                                    {{ \Illuminate\Support\Str::limit($spj->kegiatan, 50) }}
                                </div>
                                @if($spj->keterangan)
                                <div style="font-size: 11.5px; color: #64748b; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ \Illuminate\Support\Str::limit($spj->keterangan, 40) }}
                                </div>
                                @endif
                            </td>
                            <td>
                                <div class="bnd-cell-user">
                                    <div class="bnd-avatar">
                                        {{ strtoupper(substr($spj->user->name ?? 'T', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; color: #1e293b; font-size: 13px;">{{ $spj->user->name ?? 'Staf Teknis' }}</div>
                                        @if($spj->no_wa)
                                        <div style="font-size: 11px; color: #059669; display: flex; align-items: center; gap: 3px;">
                                            <ion-icon name="logo-whatsapp"></ion-icon>
                                            <span>{{ $spj->no_wa }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: right;">
                                <div style="font-size: 14.5px; font-weight: 800; color: #059669; font-feature-settings: 'tnum';">
                                    Rp {{ number_format($spj->nilai, 0, ',', '.') }}
                                </div>
                            </td>
                            <td style="text-align: center;">
                                @if($spj->status === 'menunggu_otp_kpa')
                                    <span class="bnd-badge bnd-badge-cyan">
                                        <span class="bnd-badge-dot"></span>
                                        Menunggu OTP QLOLA KPA
                                    </span>
                                @elseif($spj->status === 'disetujui_ppspm')
                                    <span class="bnd-badge bnd-badge-amber">
                                        <span class="bnd-badge-dot"></span>
                                        Siap Dicairkan
                                    </span>
                                @elseif($spj->status === 'selesai')
                                    <span class="bnd-badge bnd-badge-mint">
                                        <span class="bnd-badge-dot"></span>
                                        Selesai
                                    </span>
                                @else
                                    <span class="bnd-badge bnd-badge-rose">
                                        <span class="bnd-badge-dot"></span>
                                        {{ strtoupper(str_replace('_', ' ', $spj->status)) }}
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ route('bendahara.spj.show', $spj->id) }}" class="bnd-btn-action-view" title="Proses Pencairan SPJ">
                                    <ion-icon name="wallet-outline"></ion-icon>
                                    <span>Proses</span>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">
                                <div style="text-align: center; padding: 56px 24px; color: #64748b;">
                                    <ion-icon name="file-tray-outline" style="font-size: 52px; color: #cbd5e1; margin-bottom: 12px; display: block; margin-left: auto; margin-right: auto;"></ion-icon>
                                    <strong style="display: block; font-size: 15px; color: #334155; margin-bottom: 4px;">Tidak ada dokumen SPJ dalam antrean</strong>
                                    <p style="font-size: 13px; color: #94a3b8; margin: 0;">Semua berkas SPJ yang telah diverifikasi PPSPM telah selesai diproses atau tidak cocok dengan filter pencarian.</p>
                                    @if(request()->hasAny(['search', 'status', 'start_date', 'end_date']))
                                    <a href="{{ route('bendahara.spj.index') }}" class="bnd-btn bnd-btn-back" style="margin-top: 14px; display: inline-flex;">
                                        Reset Filter Pencarian
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- TABLE FOOTER -->
            <div class="bnd-table-footer">
                <div>
                    Menampilkan <strong>{{ $spjs->count() }}</strong> dari <strong>{{ $totalAntrean }}</strong> total berkas antrean
                </div>
                <div style="font-weight: 700; color: #0f172a;">
                    Subtotal Nominal di Halaman: <span style="color: #059669; font-size: 14px;">Rp {{ number_format($spjs->sum('nilai'), 0, ',', '.') }}</span>
                </div>
            </div>
        </form>
    </div>

</div>

@push('scripts')
<script>
    function toggleSelectAll() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.spj-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = selectAll.checked);
    }

    function submitBulkAction(actionType) {
        const checkboxes = document.querySelectorAll('.spj-checkbox:checked');
        if (checkboxes.length === 0) {
            alert('Pilih setidaknya satu dokumen SPJ dengan mencentang kotak di tabel.');
            return;
        }

        const actionText = actionType === 'otp_kpa' 
            ? 'diubah statusnya menjadi "Menunggu OTP QLOLA KPA"' 
            : 'diselesaikan pembayarannya ("Proses Pembayaran Selesai")';

        if (confirm('Anda yakin ingin memproses ' + checkboxes.length + ' dokumen SPJ terpilih untuk ' + actionText + '?')) {
            document.getElementById('bulkActionInput').value = actionType;
            document.getElementById('bulkVerifyForm').submit();
        }
    }
</script>
@endpush
@endsection
