@extends('layouts.admin')

@php
    $title = 'Syarat Dokumen SPJ';
@endphp

@section('content')

@push('styles')
<style>
    :root {
        --bps-navy: #082052;
        --bps-blue: #024b86;
        --bps-blue-light: #e0f2fe;
        --bps-orange: #f37021;
        --bps-orange-light: #fff7ed;
        --bps-green: #10b981;
        --bps-green-light: #ecfdf5;
        --dash-slate-800: #1e293b;
        --dash-slate-600: #475569;
        --dash-slate-500: #64748b;
        --dash-slate-200: #e2e8f0;
        --dash-slate-100: #f1f5f9;
        --dash-slate-50: #f8fafc;
        --dash-radius-xl: 16px;
        --dash-radius-lg: 12px;
        --dash-radius-md: 8px;
    }

    .doc-page-wrapper {
        display: flex;
        flex-direction: column;
        gap: 20px;
        width: 100%;
        color: var(--dash-slate-800);
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    }

    /* HEADER HERO */
    .doc-header-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        background: #ffffff;
        padding: 22px 26px;
        border-radius: var(--dash-radius-xl);
        border: 1px solid var(--dash-slate-200);
        box-shadow: 0 2px 6px rgba(8, 32, 82, 0.04);
        position: relative;
        overflow: hidden;
    }

    .doc-header-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 6px;
        height: 100%;
        background: linear-gradient(180deg, var(--bps-orange) 0%, var(--bps-blue) 100%);
    }

    .doc-header-title h2 {
        margin: 0 0 4px 0;
        font-size: 21px;
        font-weight: 800;
        color: var(--bps-navy);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .doc-header-title p {
        margin: 0;
        color: var(--dash-slate-500);
        font-size: 13.5px;
        max-width: 700px;
        line-height: 1.45;
    }

    .btn-add-doc {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, var(--bps-blue) 0%, #0369a1 100%);
        color: #ffffff;
        border: none;
        padding: 10px 18px;
        border-radius: var(--dash-radius-md);
        font-size: 13.5px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(2, 75, 134, 0.25);
    }

    .btn-add-doc:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(2, 75, 134, 0.35);
    }

    /* STATS GRID */
    .doc-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
    }

    .doc-stat-card {
        background: #ffffff;
        padding: 16px 20px;
        border-radius: var(--dash-radius-lg);
        border: 1px solid var(--dash-slate-200);
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .doc-stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .doc-stat-icon.blue { background: #e0f2fe; color: #0284c7; }
    .doc-stat-icon.green { background: #dcfce7; color: #16a34a; }
    .doc-stat-icon.orange { background: #ffedd5; color: #ea580c; }

    .doc-stat-val {
        font-size: 20px;
        font-weight: 800;
        color: var(--bps-navy);
        line-height: 1.1;
    }

    .doc-stat-lbl {
        font-size: 12px;
        font-weight: 600;
        color: var(--dash-slate-500);
        margin-top: 2px;
    }

    /* FILTER BAR */
    .doc-filter-panel {
        background: #ffffff;
        border-radius: var(--dash-radius-lg);
        border: 1px solid var(--dash-slate-200);
        padding: 16px 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .filter-grid-doc {
        display: grid;
        grid-template-columns: 1.8fr 1.8fr 1.2fr 2fr auto;
        gap: 12px;
        align-items: flex-end;
    }

    @media (max-width: 1024px) {
        .filter-grid-doc {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 640px) {
        .filter-grid-doc {
            grid-template-columns: 1fr;
        }
    }

    .filter-label-doc {
        display: block;
        font-size: 11.5px;
        font-weight: 700;
        color: var(--dash-slate-600);
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .form-control-doc {
        width: 100%;
        height: 38px;
        border-radius: var(--dash-radius-md);
        border: 1px solid var(--dash-slate-200);
        padding: 0 12px;
        font-size: 12.5px;
        color: var(--dash-slate-800);
        background: var(--dash-slate-50);
        outline: none;
        transition: all 0.2s ease;
    }

    .form-control-doc:focus {
        border-color: var(--bps-blue);
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(2, 75, 134, 0.12);
    }

    /* TABLE */
    .doc-table-card {
        background: #ffffff;
        border-radius: var(--dash-radius-xl);
        border: 1px solid var(--dash-slate-200);
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(8, 32, 82, 0.04);
    }

    .doc-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .doc-table th {
        background: #f8fafc;
        padding: 12px 16px;
        font-size: 12px;
        font-weight: 700;
        color: var(--dash-slate-700);
        border-bottom: 1px solid var(--dash-slate-200);
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .doc-table td {
        padding: 12px 16px;
        font-size: 13px;
        color: var(--dash-slate-700);
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .doc-table tr:hover td {
        background: #f8fafc;
    }

    .badge-assignee {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 9px;
        border-radius: 9999px;
        font-size: 11.5px;
        font-weight: 700;
    }

    .badge-assignee.sm {
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }

    .badge-assignee.bendahara {
        background: #fef3c7;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    .badge-assignee.ppk {
        background: #ede9fe;
        color: #6d28d9;
        border: 1px solid #ddd6fe;
    }

    .btn-action-icon {
        background: transparent;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .btn-action-icon.edit { color: #0284c7; }
    .btn-action-icon.edit:hover { background: #e0f2fe; }
    .btn-action-icon.delete { color: #dc2626; }
    .btn-action-icon.delete:hover { background: #fee2e2; }

    /* PAGINATION */
    .admin-pagination-nav {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding: 14px 20px;
        background: #ffffff;
        border-top: 1px solid var(--dash-slate-200);
    }

    .admin-pagination-info {
        font-size: 12.5px;
        color: var(--dash-slate-500);
    }

    .admin-pagination-info span {
        font-weight: 700;
        color: var(--bps-navy);
    }

    .admin-pagination-links {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        flex-wrap: wrap;
    }

    .page-link-item {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 34px;
        padding: 0 10px;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 600;
        color: var(--dash-slate-700);
        background: #ffffff;
        border: 1px solid var(--dash-slate-200);
        text-decoration: none;
        transition: all 0.2s ease;
        gap: 5px;
        cursor: pointer;
    }

    .page-link-item:hover:not(.disabled):not(.active) {
        background: #e0f2fe;
        border-color: #bae6fd;
        color: #0284c7;
    }

    .page-link-item.active {
        background: var(--bps-blue);
        border-color: var(--bps-blue);
        color: #ffffff;
        font-weight: 700;
        cursor: default;
        box-shadow: 0 2px 6px rgba(2, 75, 134, 0.25);
    }

    .page-link-item.disabled {
        color: #94a3b8;
        background: #f8fafc;
        border-color: #e2e8f0;
        cursor: not-allowed;
        opacity: 0.7;
    }

    .page-link-item.dots {
        border: none;
        background: transparent;
        cursor: default;
        padding: 0 4px;
        min-width: auto;
    }

    .admin-pagination-nav svg {
        display: none !important;
    }

    /* MODAL */
    .modal-backdrop-custom {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(3px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }

    .modal-box-custom {
        background: #ffffff;
        width: 100%;
        max-width: 580px;
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        animation: modalScaleIn 0.2s ease-out;
    }

    @keyframes modalScaleIn {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .modal-header-custom {
        background: var(--bps-navy);
        color: #ffffff;
        padding: 16px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-header-custom h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-close-modal {
        background: transparent;
        border: none;
        color: #ffffff;
        font-size: 20px;
        cursor: pointer;
        opacity: 0.8;
    }

    .btn-close-modal:hover { opacity: 1; }

    .modal-body-custom {
        padding: 22px;
        max-height: 75vh;
        overflow-y: auto;
    }

    .form-group-modal {
        margin-bottom: 16px;
    }

    .form-label-modal {
        display: block;
        font-size: 12.5px;
        font-weight: 700;
        color: var(--dash-slate-700);
        margin-bottom: 6px;
    }

    .form-help-modal {
        font-size: 11px;
        color: var(--dash-slate-500);
        margin-top: 4px;
    }

    .modal-footer-custom {
        padding: 14px 22px;
        background: #f8fafc;
        border-top: 1px solid var(--dash-slate-200);
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn-cancel-modal {
        background: #ffffff;
        border: 1px solid var(--dash-slate-200);
        color: var(--dash-slate-600);
        padding: 8px 16px;
        border-radius: var(--dash-radius-md);
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
    }

    .btn-save-modal {
        background: var(--bps-blue);
        color: #ffffff;
        border: none;
        padding: 8px 20px;
        border-radius: var(--dash-radius-md);
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
    }
</style>
@endpush

<div class="doc-page-wrapper">

    <!-- 1. HEADER HERO -->
    <div class="doc-header-box">
        <div class="doc-header-title">
            <h2>
                <i class="bi bi-card-checklist" style="color: var(--bps-orange);"></i>
                Kelola Persyaratan Dokumen SPJ
            </h2>
            <p>
                Konfigurasi daftar checklist dokumen wajib untuk setiap <strong>Nama Belanja</strong> dan <strong>Jenis Belanja</strong>. Setiap penambahan atau perubahan dokumen di sini akan otomatis muncul pada form pengajuan SPJ Staf Teknis dan Subbag Umum.
            </p>
        </div>
        <div>
            <button type="button" class="btn-add-doc" onclick="openAddDocModal()">
                <i class="bi bi-plus-circle-fill"></i>
                Tambah Dokumen Baru
            </button>
        </div>
    </div>

    <!-- 2. STATS CARDS -->
    <div class="doc-stats-grid">
        <div class="doc-stat-card">
            <div class="doc-stat-icon blue">
                <i class="bi bi-file-earmark-check"></i>
            </div>
            <div>
                <div class="doc-stat-val">{{ $totalDokumen }}</div>
                <div class="doc-stat-lbl">Total Dokumen Persyaratan</div>
            </div>
        </div>
        <div class="doc-stat-card">
            <div class="doc-stat-icon green">
                <i class="bi bi-person-badge"></i>
            </div>
            <div>
                <div class="doc-stat-val">{{ $totalSm }}</div>
                <div class="doc-stat-lbl">Wajib Disiapkan Teknis (SM)</div>
            </div>
        </div>
        <div class="doc-stat-card">
            <div class="doc-stat-icon orange">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div>
                <div class="doc-stat-val">{{ $totalBendahara }}</div>
                <div class="doc-stat-lbl">Dilengkapi Bendahara (Pajak/Kas)</div>
            </div>
        </div>
    </div>

    <!-- 3. FILTER BAR -->
    <div class="doc-filter-panel">
        <form action="{{ route('admin.syarat-dokumen.index') }}" method="GET" class="filter-grid-doc">
            <div>
                <label class="filter-label-doc">Kategori / Nama Belanja</label>
                <select name="nama_belanja" class="form-control-doc" onchange="this.form.submit()">
                    <option value="">-- Semua Nama Belanja --</option>
                    @foreach($daftarNamaBelanja as $nb)
                        <option value="{{ $nb }}" {{ request('nama_belanja') === $nb ? 'selected' : '' }}>
                            {{ $nb }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="filter-label-doc">Jenis Belanja</label>
                <select name="jenis_belanja" class="form-control-doc" onchange="this.form.submit()">
                    <option value="">-- Semua Jenis Belanja --</option>
                    @foreach($daftarJenisBelanja as $jb)
                        <option value="{{ $jb }}" {{ request('jenis_belanja') === $jb ? 'selected' : '' }}>
                            {{ $jb }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="filter-label-doc">Pihak / Assignee</label>
                <select name="assignee" class="form-control-doc" onchange="this.form.submit()">
                    <option value="">-- Semua Pihak --</option>
                    <option value="SM" {{ request('assignee') === 'SM' ? 'selected' : '' }}>SM (Teknis)</option>
                    <option value="Bendahara" {{ request('assignee') === 'Bendahara' ? 'selected' : '' }}>Bendahara</option>
                    <option value="PPK" {{ request('assignee') === 'PPK' ? 'selected' : '' }}>PPK</option>
                </select>
            </div>
            <div>
                <label class="filter-label-doc">Cari Dokumen / Belanja</label>
                <input type="text" name="search" class="form-control-doc" value="{{ request('search') }}" placeholder="Ketik kata kunci...">
            </div>
            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn-add-doc" style="height: 38px; padding: 0 16px;">
                    <i class="bi bi-funnel-fill"></i> Filter
                </button>
                @if(request()->hasAny(['nama_belanja', 'jenis_belanja', 'assignee', 'search']))
                    <a href="{{ route('admin.syarat-dokumen.index') }}" class="btn-cancel-modal" style="height: 38px; display: inline-flex; align-items: center; text-decoration: none;">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- 4. DATA TABLE -->
    <div class="doc-table-card">
        <div style="padding: 14px 20px; border-bottom: 1px solid var(--dash-slate-200); background: #ffffff; display: flex; justify-content: space-between; align-items: center;">
            <div style="font-weight: 700; font-size: 14px; color: var(--bps-navy);">
                Daftar Dokumen Persyaratan SPJ
            </div>
            <div style="font-size: 12px; color: var(--dash-slate-500);">
                Menampilkan <strong>{{ $syaratDokumens->firstItem() ?? 0 }} - {{ $syaratDokumens->lastItem() ?? 0 }}</strong> dari {{ $syaratDokumens->total() }} dokumen
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table class="doc-table">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">No</th>
                        <th style="width: 250px;">Nama Belanja (Akun)</th>
                        <th style="width: 250px;">Jenis Belanja</th>
                        <th>Nama Dokumen Persyaratan</th>
                        <th style="width: 170px; text-align: center;">Pihak Penyiap</th>
                        <th style="width: 70px; text-align: center;">Urutan</th>
                        <th style="width: 90px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($syaratDokumens as $index => $doc)
                    <tr>
                        <td style="text-align: center; font-weight: 700; color: var(--dash-slate-500); font-size: 12px;">
                            {{ $syaratDokumens->firstItem() + $index }}
                        </td>
                        <td style="font-weight: 700; color: var(--bps-navy);">
                            <div style="line-height: 1.35;">{{ $doc->nama_belanja }}</div>
                        </td>
                        <td style="font-weight: 600; color: var(--dash-slate-700);">
                            <div style="line-height: 1.35;">{{ $doc->jenis_belanja }}</div>
                        </td>
                        <td>
                            <div style="font-weight: 700; color: #0f172a; font-size: 13.5px; line-height: 1.35;">
                                {{ $doc->nama_dokumen }}
                            </div>
                        </td>
                        <td style="text-align: center;">
                            @if($doc->assignee === 'SM')
                                <span class="badge-assignee sm" title="Wajib disediakan oleh pengaju kegiatan / SM">
                                    <i class="bi bi-person-check-fill"></i> Disiapkan Teknis (SM)
                                </span>
                            @elseif($doc->assignee === 'Bendahara')
                                <span class="badge-assignee bendahara" title="Diverifikasi dan dilengkapi oleh Bendahara Pengeluaran">
                                    <i class="bi bi-cash-coin"></i> Dilengkapi Bendahara
                                </span>
                            @else
                                <span class="badge-assignee ppk" title="Diverifikasi dan dilengkapi oleh PPK">
                                    <i class="bi bi-shield-check"></i> Dilengkapi PPK
                                </span>
                            @endif
                        </td>
                        <td style="text-align: center; font-weight: 700; color: var(--dash-slate-600); font-size: 12px;">
                            {{ $doc->urutan }}
                        </td>
                        <td style="text-align: center; white-space: nowrap;">
                            <button type="button" class="btn-action-icon edit" title="Edit Dokumen" onclick="openEditDocModal({{ json_encode($doc) }})">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <form action="{{ route('admin.syarat-dokumen.destroy', $doc->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen persyaratan: \'{{ addslashes($doc->nama_dokumen) }}\'?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action-icon delete" title="Hapus Dokumen">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 48px 16px; color: var(--dash-slate-500);">
                            <i class="bi bi-inbox" style="font-size: 36px; display: block; margin-bottom: 8px; opacity: 0.5;"></i>
                            Tidak ada dokumen persyaratan yang cocok dengan filter.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($syaratDokumens->hasPages())
            {{ $syaratDokumens->links('vendor.pagination.admin') }}
        @endif
    </div>

</div>

<!-- 5. MODAL TAMBAH DOKUMEN -->
<div class="modal-backdrop-custom" id="modalAddDoc">
    <div class="modal-box-custom">
        <form action="{{ route('admin.syarat-dokumen.store') }}" method="POST">
            @csrf
            <div class="modal-header-custom">
                <h3><i class="bi bi-plus-circle-fill"></i> Tambah Dokumen Persyaratan Baru</h3>
                <button type="button" class="btn-close-modal" onclick="closeAddDocModal()">&times;</button>
            </div>
            <div class="modal-body-custom">
                <!-- Nama Belanja -->
                <div class="form-group-modal">
                    <label class="form-label-modal">Nama Belanja (Kategori Akun) <span style="color: #ef4444;">*</span></label>
                    <select id="add_select_nama_belanja" class="form-control-doc" onchange="handleAddNamaBelanjaChange(this.value)">
                        <option value="">-- Pilih dari Kategori yang Ada --</option>
                        @foreach($daftarNamaBelanja as $nb)
                            <option value="{{ $nb }}">{{ $nb }}</option>
                        @endforeach
                        <option value="__NEW__">+ Tambah Kategori Belanja Baru...</option>
                    </select>
                    <input type="text" name="nama_belanja" id="add_input_nama_belanja" class="form-control-doc" style="margin-top: 8px;" placeholder="Ketik Nama Belanja (contoh: Belanja barang persediaan konsumsi (521811))" required>
                    <div class="form-help-modal">Pilih kategori yang sudah ada atau ketik nama belanja baru beserta kode akunnya.</div>
                </div>

                <!-- Jenis Belanja -->
                <div class="form-group-modal">
                    <label class="form-label-modal">Jenis Belanja <span style="color: #ef4444;">*</span></label>
                    <select id="add_select_jenis_belanja" class="form-control-doc" onchange="handleAddJenisBelanjaChange(this.value)">
                        <option value="">-- Pilih dari Jenis Belanja yang Ada --</option>
                        @foreach($daftarJenisBelanja as $jb)
                            <option value="{{ $jb }}">{{ $jb }}</option>
                        @endforeach
                        <option value="__NEW__">+ Tambah Jenis Belanja Baru...</option>
                    </select>
                    <input type="text" name="jenis_belanja" id="add_input_jenis_belanja" class="form-control-doc" style="margin-top: 8px;" placeholder="Ketik Jenis Belanja (contoh: Belanja ATK CS / percetakan dokumen)" required>
                    <div class="form-help-modal">Pilih jenis belanja yang sudah terdaftar atau masukkan jenis baru.</div>
                </div>

                <!-- Nama Dokumen -->
                <div class="form-group-modal">
                    <label class="form-label-modal">Nama Dokumen Persyaratan <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="nama_dokumen" class="form-control-doc" placeholder="Contoh: KAK, BAST, Faktur Pajak, dll" required>
                </div>

                <!-- Pihak Penyiap (Assignee) -->
                <div class="form-group-modal">
                    <label class="form-label-modal">Pihak yang Menyiapkan / Bertanggung Jawab <span style="color: #ef4444;">*</span></label>
                    <select name="assignee" class="form-control-doc" required>
                        <option value="SM">SM (Wajib Disiapkan Teknis / Pelaksana Kegiatan)</option>
                        <option value="Bendahara">Bendahara (Dilengkapi Bendahara - Pajak/Transfer)</option>
                        <option value="PPK">PPK (Verifikasi Komitmen Anggaran)</option>
                    </select>
                    <div class="form-help-modal">Jika dipilih "Bendahara", form sisi Teknis akan otomatis mengunci checkbox dan menandainya dengan badge 'Dilengkapi Bendahara'.</div>
                </div>

                <!-- Urutan -->
                <div class="form-group-modal">
                    <label class="form-label-modal">Urutan Tampil (Opsional)</label>
                    <input type="number" name="urutan" class="form-control-doc" placeholder="Biarkan kosong untuk otomatis di urutan terakhir" min="0">
                </div>
            </div>
            <div class="modal-footer-custom">
                <button type="button" class="btn-cancel-modal" onclick="closeAddDocModal()">Batal</button>
                <button type="submit" class="btn-save-modal">Simpan Dokumen</button>
            </div>
        </form>
    </div>
</div>

<!-- 6. MODAL EDIT DOKUMEN -->
<div class="modal-backdrop-custom" id="modalEditDoc">
    <div class="modal-box-custom">
        <form id="formEditDoc" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header-custom">
                <h3><i class="bi bi-pencil-square"></i> Edit Dokumen Persyaratan</h3>
                <button type="button" class="btn-close-modal" onclick="closeEditDocModal()">&times;</button>
            </div>
            <div class="modal-body-custom">
                <!-- Nama Belanja -->
                <div class="form-group-modal">
                    <label class="form-label-modal">Nama Belanja (Kategori Akun) <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="nama_belanja" id="edit_nama_belanja" class="form-control-doc" required>
                </div>

                <!-- Jenis Belanja -->
                <div class="form-group-modal">
                    <label class="form-label-modal">Jenis Belanja <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="jenis_belanja" id="edit_jenis_belanja" class="form-control-doc" required>
                </div>

                <!-- Nama Dokumen -->
                <div class="form-group-modal">
                    <label class="form-label-modal">Nama Dokumen Persyaratan <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="nama_dokumen" id="edit_nama_dokumen" class="form-control-doc" required>
                </div>

                <!-- Pihak Penyiap (Assignee) -->
                <div class="form-group-modal">
                    <label class="form-label-modal">Pihak yang Menyiapkan / Bertanggung Jawab <span style="color: #ef4444;">*</span></label>
                    <select name="assignee" id="edit_assignee" class="form-control-doc" required>
                        <option value="SM">SM (Wajib Disiapkan Teknis / Pelaksana Kegiatan)</option>
                        <option value="Bendahara">Bendahara (Dilengkapi Bendahara - Pajak/Transfer)</option>
                        <option value="PPK">PPK (Verifikasi Komitmen Anggaran)</option>
                    </select>
                </div>

                <!-- Urutan -->
                <div class="form-group-modal">
                    <label class="form-label-modal">Urutan Tampil</label>
                    <input type="number" name="urutan" id="edit_urutan" class="form-control-doc" min="0">
                </div>
            </div>
            <div class="modal-footer-custom">
                <button type="button" class="btn-cancel-modal" onclick="closeEditDocModal()">Batal</button>
                <button type="submit" class="btn-save-modal">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openAddDocModal() {
        document.getElementById('modalAddDoc').style.display = 'flex';
    }

    function closeAddDocModal() {
        document.getElementById('modalAddDoc').style.display = 'none';
    }

    function handleAddNamaBelanjaChange(val) {
        const inp = document.getElementById('add_input_nama_belanja');
        if (val === '__NEW__') {
            inp.value = '';
            inp.focus();
        } else if (val) {
            inp.value = val;
        }
    }

    function handleAddJenisBelanjaChange(val) {
        const inp = document.getElementById('add_input_jenis_belanja');
        if (val === '__NEW__') {
            inp.value = '';
            inp.focus();
        } else if (val) {
            inp.value = val;
        }
    }

    function openEditDocModal(doc) {
        const form = document.getElementById('formEditDoc');
        form.action = "{{ url('admin/syarat-dokumen') }}/" + doc.id;
        document.getElementById('edit_nama_belanja').value = doc.nama_belanja || '';
        document.getElementById('edit_jenis_belanja').value = doc.jenis_belanja || '';
        document.getElementById('edit_nama_dokumen').value = doc.nama_dokumen || '';
        document.getElementById('edit_assignee').value = doc.assignee || 'SM';
        document.getElementById('edit_urutan').value = doc.urutan || 1;
        document.getElementById('modalEditDoc').style.display = 'flex';
    }

    function closeEditDocModal() {
        document.getElementById('modalEditDoc').style.display = 'none';
    }

    // Close modal when clicking backdrop
    window.addEventListener('click', function(e) {
        if (e.target.id === 'modalAddDoc') closeAddDocModal();
        if (e.target.id === 'modalEditDoc') closeEditDocModal();
    });
</script>
@endpush

@endsection

