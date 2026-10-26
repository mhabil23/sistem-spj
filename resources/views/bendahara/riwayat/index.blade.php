@extends('layouts.bendahara')

@php
$title = 'Riwayat & Arsip Keputusan SPJ';
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
            <span>Riwayat Pemeriksaan</span>
        </div>

        <div class="bnd-page-header">
            <div class="bnd-header-title-box">
                <h1 style="font-size: 24px; font-weight: 800; color: #0f172a; margin: 0 0 6px 0;">
                    Riwayat &amp; Arsip Keputusan SPJ
                </h1>
                <p style="color: #64748b; font-size: 13.5px; margin: 0;">
                    Seluruh dokumen yang telah melewati meja verifikasi bendahara, otorisasi BRI QLOLA, atau dalam masa revisi.
                </p>
            </div>
            <div class="bnd-header-actions">
                <a href="{{ route('bendahara.riwayat.export.csv') }}" class="bnd-btn" style="background: #059669; color: #ffffff; box-shadow: 0 4px 12px rgba(5,150,105,0.25);">
                    <ion-icon name="document-text-outline" style="font-size: 16px;"></ion-icon>
                    <span>Unduh Excel</span>
                </a>
                <a href="{{ route('bendahara.riwayat.export.pdf') }}" class="bnd-btn" style="background: #e11d48; color: #ffffff; box-shadow: 0 4px 12px rgba(225,29,72,0.25);">
                    <ion-icon name="document-pdf-outline" style="font-size: 16px;"></ion-icon>
                    <span>Unduh PDF</span>
                </a>
            </div>
        </div>
    </div>

    <!-- STATS CARDS GRID -->
    <div class="bnd-stats-grid">
        <!-- STAT 1: TOTAL RIWAYAT -->
        <div class="bnd-stat-card primary">
            <div class="bnd-stat-icon blue">
                <ion-icon name="archive-outline"></ion-icon>
            </div>
            <div class="bnd-stat-info">
                <div class="bnd-stat-label">Total Arsip Terdata</div>
                <div class="bnd-stat-value" style="color: #0284c7;">
                    {{ $totalRiwayat }} Berkas
                </div>
                <div class="bnd-stat-sub">
                    <ion-icon name="layers-outline"></ion-icon>
                    <span>Keseluruhan rekam jejak verifikasi</span>
                </div>
            </div>
        </div>

        <!-- STAT 2: TOTAL DANA CAIR -->
        <div class="bnd-stat-card success">
            <div class="bnd-stat-icon emerald">
                <ion-icon name="wallet-outline"></ion-icon>
            </div>
            <div class="bnd-stat-info">
                <div class="bnd-stat-label">Total Dana Berhasil Cair</div>
                <div class="bnd-stat-value" style="color: #059669;">
                    Rp {{ number_format($totalDanaCair, 0, ',', '.') }}
                </div>
                <div class="bnd-stat-sub">
                    <ion-icon name="checkmark-done-circle-outline" style="color: #059669;"></ion-icon>
                    <span>Total tagihan tuntas ditransfer</span>
                </div>
            </div>
        </div>

        <!-- STAT 3: TOTAL SELESAI -->
        <div class="bnd-stat-card">
            <div class="bnd-stat-icon emerald">
                <ion-icon name="checkmark-circle-outline"></ion-icon>
            </div>
            <div class="bnd-stat-info">
                <div class="bnd-stat-label">Selesai Dibayarkan</div>
                <div class="bnd-stat-value" style="color: #059669;">
                    {{ $totalSelesai }} SPJ
                </div>
                <div class="bnd-stat-sub">
                    <span>Transaksi bank sukses</span>
                </div>
            </div>
        </div>

        <!-- STAT 4: REVISI -->
        <div class="bnd-stat-card">
            <div class="bnd-stat-icon" style="background: #ffe4e6; color: #e11d48;">
                <ion-icon name="alert-circle-outline"></ion-icon>
            </div>
            <div class="bnd-stat-info">
                <div class="bnd-stat-label">Dalam Revisi</div>
                <div class="bnd-stat-value" style="color: #e11d48;">
                    {{ $totalRevisi }} Berkas
                </div>
                <div class="bnd-stat-sub">
                    <span>Dikembalikan ke teknis</span>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER CARD MODERN -->
    <div class="bnd-filter-card">
        <form action="{{ route('bendahara.riwayat.index') }}" method="GET" class="bnd-filter-grid">
            <div class="bnd-form-group">
                <label class="bnd-form-label">Cari Kegiatan / No. SPJ / SPM / Bukti / PIC</label>
                <div style="position: relative;">
                    <ion-icon name="search-outline" style="position: absolute; left: 12px; top: 12px; color: #94a3b8; font-size: 16px;"></ion-icon>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik kata kunci pencarian..." class="bnd-form-control" style="padding-left: 36px;">
                </div>
            </div>

            <div class="bnd-form-group">
                <label class="bnd-form-label">Status Arsip</label>
                <select name="status" class="bnd-form-control">
                    <option value="">Semua Status</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai (Cair)</option>
                    <option value="menunggu_otp_kpa" {{ request('status') === 'menunggu_otp_kpa' ? 'selected' : '' }}>Menunggu OTP QLOLA KPA</option>
                    <option value="disetujui_ppspm" {{ request('status') === 'disetujui_ppspm' ? 'selected' : '' }}>Siap Dicairkan (PPSPM)</option>
                    <option value="revisi_bendahara" {{ request('status') === 'revisi_bendahara' ? 'selected' : '' }}>Dikembalikan (Revisi)</option>
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
                <a href="{{ route('bendahara.riwayat.index') }}" class="bnd-btn bnd-btn-back" style="height: 40px; color: #e11d48; border-color: #fecdd3; background: #fff1f2;">
                    <span>Reset</span>
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- TABLE CARD MODERN -->
    <div class="bnd-table-card">
        <div class="bnd-table-toolbar">
            <div class="bnd-toolbar-left">
                <h3 class="bnd-toolbar-title">Daftar Arsip &amp; Riwayat Verifikasi</h3>
                <span class="bnd-toolbar-counter">{{ $riwayatSpjs->count() }} Berkas</span>
            </div>
            <div style="font-size: 12.5px; color: #64748b;">
                Tersusun berdasarkan riwayat pembaruan terakhir
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table class="bnd-table">
                <thead>
                    <tr>
                        <th>Terakhir Diperbarui</th>
                        <th>No. SPJ / SPM</th>
                        <th>Uraian Kegiatan</th>
                        <th>Pengaju</th>
                        <th style="text-align: right;">Nilai Tagihan (Rp)</th>
                        <th style="text-align: center;">Status &amp; SLA</th>
                        <th>Referensi Bukti / Catatan</th>
                        <th style="text-align: center; width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatSpjs as $spj)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <ion-icon name="calendar-outline" style="color: #94a3b8; font-size: 15px;"></ion-icon>
                                <div>
                                    <div style="font-weight: 700; color: #1e293b; font-size: 13px;">{{ $spj->updated_at->format('d M Y') }}</div>
                                    <div style="font-size: 11px; color: #94a3b8;">{{ $spj->updated_at->format('H:i') }} WITA</div>
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
                        <td style="max-width: 280px;">
                            <a href="{{ route('bendahara.spj.show', $spj->id) }}" style="font-weight: 700; color: #0f172a; text-decoration: none; display: block; line-height: 1.4; margin-bottom: 2px; transition: color 0.15s;" onmouseover="this.style.color='#0284c7';" onmouseout="this.style.color='#0f172a';" title="{{ $spj->kegiatan }}">
                                {{ \Illuminate\Support\Str::limit($spj->kegiatan, 45) }}
                            </a>
                            @if($spj->keterangan)
                            <div style="font-size: 11.5px; color: #64748b; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ \Illuminate\Support\Str::limit($spj->keterangan, 36) }}
                            </div>
                            @endif
                        </td>
                        <td>
                            <div class="bnd-cell-user">
                                <div class="bnd-avatar" style="width: 28px; height: 28px; font-size: 11px;">
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
                            <div style="font-size: 14px; font-weight: 800; color: #059669; font-feature-settings: 'tnum';">
                                Rp {{ number_format($spj->nilai, 0, ',', '.') }}
                            </div>
                        </td>
                        <td style="text-align: center;">
                            <div>
                                @if($spj->status === 'selesai')
                                    <span class="bnd-badge bnd-badge-mint">
                                        <span class="bnd-badge-dot"></span>
                                        Selesai (Cair)
                                    </span>
                                @elseif($spj->status === 'menunggu_otp_kpa')
                                    <span class="bnd-badge bnd-badge-cyan">
                                        <span class="bnd-badge-dot"></span>
                                        Menunggu OTP KPA
                                    </span>
                                @elseif($spj->status === 'disetujui_ppspm')
                                    <span class="bnd-badge bnd-badge-amber">
                                        <span class="bnd-badge-dot"></span>
                                        Siap Dicairkan
                                    </span>
                                @else
                                    <span class="bnd-badge bnd-badge-rose">
                                        <span class="bnd-badge-dot"></span>
                                        {{ str_replace('_', ' ', strtoupper($spj->status)) }}
                                    </span>
                                @endif

                                @if($spj->diajukan_at)
                                @php
                                    $diff = \Carbon\Carbon::parse($spj->diajukan_at)->diff($spj->updated_at);
                                    $timeStr = '';
                                    if($diff->d > 0) $timeStr .= $diff->d . 'h ';
                                    if($diff->h > 0) $timeStr .= $diff->h . 'j ';
                                    $timeStr .= $diff->i . 'm';
                                @endphp
                                <div style="color: #64748b; font-size: 11px; display: flex; align-items: center; justify-content: center; gap: 4px; font-weight: 600; margin-top: 4px;">
                                    <ion-icon name="time-outline" style="font-size: 12px;"></ion-icon> SLA: {{ $timeStr }}
                                </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($spj->status === 'revisi_bendahara' && $spj->catatan_revisi)
                                <div style="display: flex; align-items: center; gap: 6px; color: #e11d48; font-size: 12px; font-weight: 600;" title="{{ $spj->catatan_revisi }}">
                                    <ion-icon name="alert-circle-outline" style="font-size: 16px; flex-shrink: 0;"></ion-icon>
                                    <span>{{ \Illuminate\Support\Str::limit($spj->catatan_revisi, 28) }}</span>
                                </div>
                            @elseif($spj->bukti_transfer)
                                <div style="display: flex; align-items: center; gap: 6px; color: #059669; font-size: 12px; font-weight: 700;">
                                    <ion-icon name="checkmark-done-outline" style="font-size: 16px; flex-shrink: 0;"></ion-icon>
                                    <span>{{ $spj->bukti_transfer }}</span>
                                </div>
                            @else
                                <span style="color: #94a3b8; font-size: 13px;">-</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 6px; justify-content: center; align-items: center;">
                                <a href="{{ route('bendahara.spj.show', $spj->id) }}" 
                                   style="display: flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 8px; border: 1px solid #bfdbfe; background: #eff6ff; color: #1d4ed8; text-decoration: none; transition: all 0.2s;"
                                   onmouseover="this.style.background='#dbeafe'; this.style.transform='translateY(-1px)';"
                                   onmouseout="this.style.background='#eff6ff'; this.style.transform='translateY(0)';"
                                   title="Lihat Detail SPJ">
                                    <ion-icon name="eye-outline" style="font-size: 17px;"></ion-icon>
                                </a>

                                @if(!in_array($spj->status, ['disetujui_ppspm', 'menunggu_otp_kpa']))
                                <form action="{{ route('bendahara.riwayat.destroy', $spj->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus permanen riwayat SPJ ini?');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            style="display: flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 8px; border: 1px solid #fecdd3; background: #fff1f2; color: #e11d48; cursor: pointer; transition: all 0.2s;"
                                            onmouseover="this.style.background='#ffe4e6'; this.style.transform='translateY(-1px)';"
                                            onmouseout="this.style.background='#fff1f2'; this.style.transform='translateY(0)';"
                                            title="Hapus Dari Riwayat">
                                        <ion-icon name="trash-outline" style="font-size: 16px;"></ion-icon>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div style="text-align: center; padding: 56px 24px; color: #64748b;">
                                <ion-icon name="archive-outline" style="font-size: 52px; color: #cbd5e1; margin-bottom: 12px; display: block; margin-left: auto; margin-right: auto;"></ion-icon>
                                <strong style="display: block; font-size: 15px; color: #334155; margin-bottom: 4px;">Belum Ada Riwayat Tersimpan</strong>
                                <p style="font-size: 13px; color: #94a3b8; margin: 0;">Dokumen yang telah melewati meja verifikasi bendahara akan otomatis tercatat di sini.</p>
                                @if(request()->hasAny(['search', 'status', 'start_date', 'end_date']))
                                <a href="{{ route('bendahara.riwayat.index') }}" class="bnd-btn bnd-btn-back" style="margin-top: 14px; display: inline-flex;">
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
                Menampilkan <strong>{{ $riwayatSpjs->count() }}</strong> dari <strong>{{ $totalRiwayat }}</strong> total berkas riwayat
            </div>
            <div style="font-weight: 700; color: #0f172a;">
                Subtotal Nilai di Halaman: <span style="color: #059669; font-size: 14px;">Rp {{ number_format($riwayatSpjs->sum('nilai'), 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

</div>
@endsection
