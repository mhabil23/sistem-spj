@extends('layouts.teknis')

@php
$title = 'Daftar SPJ';
$subtitle = 'Kelola semua pengajuan SPJ Anda di sini.';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/teknis/spj.css'])
@endpush

<div style="padding-top: 4px;">
    <!-- PAGE HEADING -->
    <div class="dashboard-heading">
        <div>
            <h2 class="dashboard-heading-title">Daftar Pengajuan SPJ</h2>
            <p class="dashboard-heading-subtitle">Pantau dan kelola seluruh dokumen Surat Pertanggungjawaban (SPJ) Anda.</p>
        </div>
        <div>
            <a href="{{ route('teknis.spj.create') }}" class="btn-dash-primary">
                <ion-icon name="add-circle-outline" style="font-size: 18px;"></ion-icon>
                Buat SPJ Baru
            </a>
        </div>
    </div>

    <!-- MAIN PANEL -->
    <div class="spj-panel">
        <div class="spj-panel-header">
            <div>
                <h2>Data Pengajuan SPJ</h2>
                <p>Seluruh riwayat dan tahapan berkas SPJ yang telah didaftarkan.</p>
            </div>
            <div class="header-stat-pill">
                Total: <strong>{{ $spjs->count() }}</strong> Pengajuan
            </div>
        </div>

        <!-- FILTER & SEARCH BAR -->
        <div class="filter-box">
            <form action="{{ route('teknis.spj.index') }}" method="GET" class="filter-grid">
                <div class="filter-field" style="flex: 2; min-width: 240px;">
                    <label class="filter-label">Cari Uraian Kegiatan</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik kata kunci kegiatan..." class="dash-input">
                </div>
                
                <div class="filter-field" style="min-width: 180px;">
                    <label class="filter-label">Status Pengajuan</label>
                    <select name="status" class="dash-input">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="diajukan" {{ request('status') === 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                        <option value="disetujui_umum" {{ request('status') === 'disetujui_umum' ? 'selected' : '' }}>Disetujui Umum</option>
                        <option value="disetujui_ppk" {{ request('status') === 'disetujui_ppk' ? 'selected' : '' }}>Disetujui PPK</option>
                        <option value="disetujui_ppspm" {{ request('status') === 'disetujui_ppspm' ? 'selected' : '' }}>Disetujui PPSPM</option>
                        <option value="menunggu_otp_kpa" {{ request('status') === 'menunggu_otp_kpa' ? 'selected' : '' }}>Menunggu OTP QLOLA KPA</option>
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="dikembalikan" {{ request('status') === 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    </select>
                </div>

                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="btn-dash-primary" style="height: 40px; padding: 0 18px;">
                        <ion-icon name="funnel-outline" style="font-size: 16px;"></ion-icon> Filter
                    </button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('teknis.spj.index') }}" class="btn-dash-reset">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- TABLE CONTAINER -->
        <div class="spj-table-container">
            <table class="spj-table">
                <thead>
                    <tr>
                        <th style="width: 55px; text-align: center;">No</th>
                        <th>Nomor SPJ</th>
                        <th>Uraian Kegiatan</th>
                        <th>Tanggal Kegiatan</th>
                        <th>Nilai Anggaran</th>
                        <th>Status Akhir</th>
                        <th style="text-align: right; padding-right: 24px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($spjs as $spj)
                    <tr>
                        <td style="text-align: center; font-weight: 700; color: var(--dash-text-muted);">
                            {{ $loop->iteration }}
                        </td>
                        <td style="white-space: nowrap; font-weight: 700;">
                            <span style="display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 700; background: var(--dash-input-bg); border: 1px solid var(--dash-border); color: var(--dash-text-dark);">
                                {{ $spj->nomor_spj ?: '-' }}
                            </span>
                        </td>
                        <td style="max-width: 300px; font-weight: 600;" title="{{ $spj->kegiatan }}">
                            <div style="font-size: 13.5px; color: var(--dash-text-dark); line-height: 1.4;">{{ $spj->kegiatan }}</div>
                            @if($spj->keterangan)
                                <div style="font-size: 11px; color: var(--dash-text-muted); margin-top: 2px;">{{ Str::limit($spj->keterangan, 40) }}</div>
                            @endif
                        </td>
                        <td style="color: var(--dash-text-muted); white-space: nowrap;">
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <ion-icon name="calendar-outline" style="font-size: 14px;"></ion-icon>
                                <span>{{ $spj->tanggal->format('d M Y') }}</span>
                            </div>
                        </td>
                        <td style="font-weight: 700; color: var(--dash-text-dark); white-space: nowrap;">
                            Rp {{ number_format($spj->nilai, 0, ',', '.') }}
                        </td>
                        <td>
                            @php
                                $badgeClass = 'dash-badge-draft';
                                if(in_array($spj->status, ['selesai', 'disetujui_ppk', 'disetujui_umum', 'disetujui_ppspm'])) $badgeClass = 'dash-badge-mint';
                                elseif(in_array($spj->status, ['diajukan', 'menunggu_otp_kpa'])) $badgeClass = 'dash-badge-cyan';
                                elseif(in_array($spj->status, ['dikembalikan', 'revisi_bendahara', 'revisi_ppk', 'revisi_ppspm', 'revisi_umum'])) $badgeClass = 'dash-badge-peach';
                            @endphp
                            <span class="dash-badge {{ $badgeClass }}">
                                <span class="badge-dot"></span>
                                {{ $spj->status === 'menunggu_otp_kpa' ? 'MENUNGGU OTP QLOLA KPA' : str_replace('_', ' ', strtoupper($spj->status)) }}
                            </span>
                        </td>
                        <td style="text-align: right; padding-right: 24px; white-space: nowrap;">
                            <div style="display: flex; gap: 8px; justify-content: flex-end; align-items: center;">
                                <a href="{{ route('teknis.spj.show', $spj->id) }}" class="action-btn action-btn-view" title="Lihat Detail SPJ">
                                    <ion-icon name="eye-outline"></ion-icon>
                                    <span>Detail</span>
                                </a>
                                
                                @if(in_array($spj->status, ['draft', 'dikembalikan', 'revisi_umum', 'revisi_ppk', 'revisi_ppspm', 'revisi_bendahara']))
                                <a href="{{ route('teknis.spj.edit', $spj->id) }}" class="action-btn action-btn-edit" title="Perbaiki Dokumen">
                                    <ion-icon name="create-outline"></ion-icon>
                                    <span>Edit</span>
                                </a>
                                @endif

                                <form action="{{ route('teknis.spj.destroy', $spj->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen SPJ ini?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn action-btn-delete" title="Hapus SPJ">
                                        <ion-icon name="trash-outline" style="font-size: 15px;"></ion-icon>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 56px 24px; color: var(--dash-text-muted);">
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                                <ion-icon name="document-text-outline" style="font-size: 46px; opacity: 0.6;"></ion-icon>
                                <span style="font-size: 14px; font-weight: 500;">Tidak ada dokumen pengajuan SPJ yang sesuai.</span>
                                @if(request()->hasAny(['search', 'status']))
                                    <a href="{{ route('teknis.spj.index') }}" class="btn-dash-reset" style="margin-top: 6px;">Bersihkan Filter Pencarian</a>
                                @endif
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
