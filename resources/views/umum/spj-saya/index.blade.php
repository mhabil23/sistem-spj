@extends('layouts.umum')

@php
$title = 'SPJ Saya';
$subtitle = 'Pantau dan kelola seluruh dokumen SPJ yang diajukan sendiri oleh Subbagian Umum.';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/teknis/spj.css'])
    <style>
        .my-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 14px;
            margin-bottom: 24px;
        }
        .my-stat-card {
            background: var(--dash-card-bg, #ffffff);
            border: 1px solid var(--dash-border, #e2edf5);
            border-radius: var(--radius-md, 12px);
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: all 0.2s ease;
        }
        .my-stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
        }
        .my-stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }
        .icon-blue { background: #e0f2fe; color: #0284c7; }
        .icon-amber { background: #fef3c7; color: #d97706; }
        .icon-indigo { background: #e0e7ff; color: #4f46e5; }
        .icon-rose { background: #ffe4e6; color: #e11d48; }
        .icon-emerald { background: #d1fae5; color: #059669; }

        .my-stat-val {
            font-size: 20px;
            font-weight: 800;
            color: var(--dash-text-dark, #0f172a);
            line-height: 1.2;
        }
        .my-stat-lbl {
            font-size: 11.5px;
            font-weight: 600;
            color: var(--dash-text-muted, #64748b);
            text-transform: uppercase;
            letter-spacing: 0.02em;
            margin-top: 2px;
        }

        html.dark-theme .my-stat-card {
            background: var(--dash-card-bg, #142530);
            border-color: var(--dash-border, #1f3747);
        }
        html.dark-theme .my-stat-val {
            color: var(--dash-text-dark, #f0f6fa);
        }
        html.dark-theme .icon-blue { background: rgba(2, 132, 199, 0.15); color: #38bdf8; }
        html.dark-theme .icon-amber { background: rgba(217, 119, 6, 0.15); color: #fbbf24; }
        html.dark-theme .icon-indigo { background: rgba(79, 70, 229, 0.15); color: #818cf8; }
        html.dark-theme .icon-rose { background: rgba(225, 29, 72, 0.15); color: #fb7185; }
        html.dark-theme .icon-emerald { background: rgba(5, 150, 105, 0.15); color: #34d399; }
    </style>
@endpush

<div style="padding-top: 4px;">
    <!-- PAGE HEADING -->
    <div class="dashboard-heading">
        <div>
            <h2 class="dashboard-heading-title">SPJ Saya (Pengajuan Mandiri Umum)</h2>
            <p class="dashboard-heading-subtitle">Pantau seluruh berkas SPJ yang diajukan sendiri oleh akun Subbagian Umum untuk kegiatan operasional kantor.</p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <a href="{{ route('umum.spj.index') }}" class="btn-dash-back" title="Lihat semua SPJ masuk dari seksi lain">
                <ion-icon name="file-tray-full-outline" style="font-size: 17px;"></ion-icon>
                <span>Kotak Masuk Verifikasi</span>
            </a>
            <a href="{{ route('umum.spj.create') }}" class="btn-dash-primary" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; text-decoration: none;">
                <ion-icon name="add-circle-outline" style="font-size: 18px;"></ion-icon>
                <span>Buat Pengajuan Baru</span>
            </a>
        </div>
    </div>

    <!-- STATS SUMMARY CARDS -->
    <div class="my-stats-grid">
        <div class="my-stat-card">
            <div class="my-stat-icon icon-blue">
                <ion-icon name="folder-open-outline"></ion-icon>
            </div>
            <div>
                <div class="my-stat-val">{{ $stats['total'] }}</div>
                <div class="my-stat-lbl">Total SPJ Saya</div>
            </div>
        </div>

        <div class="my-stat-card">
            <div class="my-stat-icon icon-amber">
                <ion-icon name="document-text-outline"></ion-icon>
            </div>
            <div>
                <div class="my-stat-val">{{ $stats['draft'] }}</div>
                <div class="my-stat-lbl">Draf Belum Diajukan</div>
            </div>
        </div>

        <div class="my-stat-card">
            <div class="my-stat-icon icon-indigo">
                <ion-icon name="hourglass-outline"></ion-icon>
            </div>
            <div>
                <div class="my-stat-val">{{ $stats['diproses'] }}</div>
                <div class="my-stat-lbl">Dalam Alur Proses</div>
            </div>
        </div>

        <div class="my-stat-card">
            <div class="my-stat-icon icon-rose">
                <ion-icon name="alert-circle-outline"></ion-icon>
            </div>
            <div>
                <div class="my-stat-val">{{ $stats['revisi'] }}</div>
                <div class="my-stat-lbl">Perlu Revisi</div>
            </div>
        </div>

        <div class="my-stat-card">
            <div class="my-stat-icon icon-emerald">
                <ion-icon name="checkmark-done-circle-outline"></ion-icon>
            </div>
            <div>
                <div class="my-stat-val">{{ $stats['selesai'] }}</div>
                <div class="my-stat-lbl">Selesai / Cair</div>
            </div>
        </div>
    </div>

    <!-- MAIN PANEL -->
    <div class="spj-panel">
        <div class="spj-panel-header">
            <div>
                <h2>Daftar SPJ Mandiri Subbag Umum</h2>
                <p>Data pengajuan belanja pegawai, belanja modal, atau operasional kantor yang diajukan oleh akun Anda.</p>
            </div>
            <div class="header-stat-pill">
                Total Anggaran: <strong>Rp {{ number_format($stats['totalNilai'], 0, ',', '.') }}</strong>
            </div>
        </div>

        <!-- FILTER & SEARCH BAR -->
        <div class="filter-box">
            <form action="{{ route('umum.spj-saya.index') }}" method="GET" class="filter-grid">
                <div class="filter-field" style="flex: 2; min-width: 240px;">
                    <label class="filter-label">Cari Kegiatan / No. SPJ</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik kata kunci kegiatan atau nomor..." class="dash-input">
                </div>
                
                <div class="filter-field" style="min-width: 180px;">
                    <label class="filter-label">Status Pengajuan</label>
                    <select name="status" class="dash-input">
                        <option value="semua">Semua Status</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draf</option>
                        <option value="diajukan" {{ request('status') === 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                        <option value="proses" {{ request('status') === 'proses' ? 'selected' : '' }}>Sedang Diproses</option>
                        <option value="revisi" {{ request('status') === 'revisi' ? 'selected' : '' }}>Perlu Revisi</option>
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="btn-dash-primary" style="height: 40px; padding: 0 18px;">
                        <ion-icon name="funnel-outline" style="font-size: 16px;"></ion-icon> Filter
                    </button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('umum.spj-saya.index') }}" class="btn-dash-reset">Reset</a>
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
                        <th>Uraian Kegiatan &amp; Kategori</th>
                        <th>Tanggal Pelaksanaan</th>
                        <th>Nilai Anggaran</th>
                        <th>Status Dokumen</th>
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
                        <td style="max-width: 320px; font-weight: 600;" title="{{ $spj->kegiatan }}">
                            <div style="font-size: 13.5px; color: var(--dash-text-dark); line-height: 1.4;">{{ $spj->kegiatan }}</div>
                            <div style="display: flex; align-items: center; gap: 6px; margin-top: 3px; flex-wrap: wrap;">
                                <span style="font-size: 10.5px; font-weight: 700; color: #0284c7; background: #e0f2fe; padding: 1px 6px; border-radius: 4px;">Subbagian Umum</span>
                                @if($spj->keterangan)
                                    <span style="font-size: 11px; color: var(--dash-text-muted);">{{ Str::limit($spj->keterangan, 32) }}</span>
                                @endif
                            </div>
                        </td>
                        <td style="color: var(--dash-text-muted); white-space: nowrap;">
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <ion-icon name="calendar-outline" style="font-size: 14px;"></ion-icon>
                                <span>{{ $spj->periode_kegiatan_ringkas }}</span>
                            </div>
                        </td>
                        <td style="font-weight: 700; color: var(--dash-text-dark); white-space: nowrap;">
                            Rp {{ number_format($spj->nilai, 0, ',', '.') }}
                        </td>
                        <td>
                            @php
                                $badgeClass = 'dash-badge-draft';
                                if(in_array($spj->status, ['selesai', 'disetujui_ppk', 'disetujui_umum', 'disetujui_ppspm'])) $badgeClass = 'dash-badge-mint';
                                elseif($spj->status === 'diajukan') {
                                    $badgeClass = $spj->pemeriksaan_dimulai_at ? 'dash-badge-amber' : 'dash-badge-cyan';
                                }
                                elseif($spj->status === 'menunggu_otp_kpa') $badgeClass = 'dash-badge-cyan';
                                elseif($spj->status === 'proses_ls') $badgeClass = 'dash-badge-purple';
                                elseif(in_array($spj->status, ['dikembalikan', 'revisi_bendahara', 'revisi_ppk', 'revisi_ppspm', 'revisi_umum'])) $badgeClass = 'dash-badge-peach';
                            @endphp
                            <span class="dash-badge {{ $badgeClass }}">
                                <span class="badge-dot"></span>
                                {{ $spj->status_label }}
                            </span>
                        </td>
                        <td style="text-align: right; padding-right: 24px; white-space: nowrap;">
                            <div style="display: flex; gap: 6px; justify-content: flex-end; align-items: center;">
                                <a href="{{ route('umum.spj.print', $spj->id) }}" target="_blank" class="action-btn" style="background: #f0f9ff; color: #0284c7; border: 1px solid #bae6fd; text-decoration: none;" title="Cetak Lembar Checklist SPJ">
                                    <ion-icon name="print-outline"></ion-icon>
                                    <span>Cetak</span>
                                </a>
                                <a href="{{ route('umum.spj.show', $spj->id) }}" class="action-btn action-btn-view" title="Lihat Detail SPJ">
                                    <ion-icon name="eye-outline"></ion-icon>
                                    <span>Detail</span>
                                </a>
                                
                                @if(in_array($spj->status, ['draft', 'dikembalikan', 'revisi_umum', 'revisi_ppk', 'revisi_ppspm', 'revisi_bendahara']))
                                <a href="{{ route('umum.spj-saya.edit', $spj->id) }}" class="action-btn action-btn-edit" title="Perbaiki atau Ubah Dokumen SPJ">
                                    <ion-icon name="create-outline"></ion-icon>
                                    <span>Edit</span>
                                </a>
                                @endif

                                @if($spj->status === 'draft')
                                <form action="{{ route('umum.spj-saya.destroy', $spj->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus draf SPJ ini?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn" style="background: #fff1f2; color: #e11d48; border: 1px solid #fecdd3; cursor: pointer;" title="Hapus Draf SPJ">
                                        <ion-icon name="trash-outline"></ion-icon>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 56px 24px; color: var(--dash-text-muted);">
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                                <ion-icon name="folder-open-outline" style="font-size: 46px; opacity: 0.6; color: #0284c7;"></ion-icon>
                                <span style="font-size: 15px; font-weight: 600; color: var(--dash-text-dark);">Belum ada SPJ yang Anda ajukan secara mandiri.</span>
                                <span style="font-size: 13px; color: var(--dash-text-muted); max-width: 420px; line-height: 1.4;">
                                    Untuk mengajukan belanja operasional kantor, belanja pegawai, atau belanja modal, silakan klik tombol di bawah.
                                </span>
                                <a href="{{ route('umum.spj.create') }}" class="btn-dash-primary" style="margin-top: 10px; display: inline-flex; align-items: center; gap: 6px; padding: 8px 18px; text-decoration: none;">
                                    <ion-icon name="add-circle-outline" style="font-size: 18px;"></ion-icon>
                                    <span>Ajukan SPJ Sekarang</span>
                                </a>
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

