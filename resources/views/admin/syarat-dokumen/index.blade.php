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
        --dash-slate-700: #334155;
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
        max-width: 720px;
        line-height: 1.45;
    }

    .btn-add-doc {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, var(--bps-blue) 0%, #0369a1 100%);
        color: #ffffff;
        border: none;
        padding: 11px 20px;
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
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
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
    .doc-stat-icon.purple { background: #f3e8ff; color: #7e22ce; }

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
        grid-template-columns: 1.4fr 1.4fr 1.1fr 1.1fr 1.6fr auto;
        gap: 12px;
        align-items: flex-end;
    }

    @media (max-width: 1200px) {
        .filter-grid-doc {
            grid-template-columns: 1fr 1fr 1fr;
        }
    }

    @media (max-width: 640px) {
        .filter-grid-doc {
            grid-template-columns: 1fr;
        }
    }

    .filter-label-doc {
        display: block;
        font-size: 11px;
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

    /* GROUPED CARDS (CUKUP SATU NAMA JENIS BELANJA, TIDAK DOBLE-DOBLE) */
    .belanja-groups-wrapper {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .belanja-group-card {
        background: #ffffff;
        border-radius: var(--dash-radius-xl);
        border: 1px solid var(--dash-slate-200);
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(8, 32, 82, 0.04);
        transition: border-color 0.2s ease;
    }

    .belanja-group-card:hover {
        border-color: #cbd5e1;
    }

    .belanja-group-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 1px solid var(--dash-slate-200);
        padding: 14px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .belanja-header-left {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .badge-akun-kode {
        font-size: 11px;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        letter-spacing: 0.2px;
    }

    .badge-akun-kode.akun-51 {
        background: #dbeafe;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }

    .badge-akun-kode.akun-52 {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    .badge-akun-kode.akun-53 {
        background: #f3e8ff;
        color: #7e22ce;
        border: 1px solid #e9d5ff;
    }

    .jenis-belanja-title {
        margin: 0;
        font-size: 15px;
        font-weight: 800;
        color: var(--bps-navy);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .badge-peruntukan {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2.5px 8px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .badge-peruntukan.semua {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }

    .badge-peruntukan.umum {
        background: #f5f3ff;
        color: #6d28d9;
        border: 1px solid #ddd6fe;
    }

    .badge-peruntukan.teknis {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }

    .badge-doc-count {
        background: #ffffff;
        color: var(--dash-slate-600);
        border: 1px solid var(--dash-slate-200);
        padding: 2px 8px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 700;
    }

    .btn-quick-add {
        background: #ffffff;
        color: var(--bps-blue);
        border: 1.5px solid #bae6fd;
        padding: 6px 14px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }

    .btn-quick-add:hover {
        background: #0284c7;
        color: #ffffff;
        border-color: #0284c7;
        box-shadow: 0 2px 6px rgba(2, 132, 199, 0.25);
    }

    /* INNER TABLE */
    .doc-table-inner {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .doc-table-inner th {
        background: #ffffff;
        padding: 10px 16px;
        font-size: 11.5px;
        font-weight: 700;
        color: var(--dash-slate-500);
        border-bottom: 1px solid var(--dash-slate-200);
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .doc-table-inner td {
        padding: 11px 16px;
        font-size: 13px;
        color: var(--dash-slate-700);
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .doc-table-inner tr:last-child td {
        border-bottom: none;
    }

    .doc-table-inner tr:hover td {
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
        max-width: 680px;
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

    /* DYNAMIC DOKUMEN ROW IN MODAL */
    .doc-input-rows-container {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: 8px;
    }

    .doc-input-row {
        display: grid;
        grid-template-columns: 1fr 170px 75px 36px;
        gap: 8px;
        align-items: center;
        background: #f8fafc;
        padding: 8px 10px;
        border-radius: 8px;
        border: 1px solid var(--dash-slate-200);
        animation: fadeInRow 0.2s ease-out;
    }

    @keyframes fadeInRow {
        from { opacity: 0; transform: translateY(-4px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .btn-remove-row {
        background: transparent;
        border: none;
        color: #94a3b8;
        font-size: 16px;
        cursor: pointer;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
    }

    .btn-remove-row:hover:not(:disabled) {
        color: #dc2626;
        background: #fee2e2;
    }

    .btn-remove-row:disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }

    .btn-add-more-row {
        background: #e0f2fe;
        color: #0369a1;
        border: 1.5px dashed #7dd3fc;
        padding: 9px 16px;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        width: 100%;
        justify-content: center;
        transition: all 0.2s ease;
        margin-top: 6px;
    }

    .btn-add-more-row:hover {
        background: #0284c7;
        color: #ffffff;
        border-style: solid;
        border-color: #0284c7;
        box-shadow: 0 2px 6px rgba(2, 132, 199, 0.25);
    }

    .modal-footer-custom {
        padding: 14px 22px;
        background: #f8fafc;
        border-top: 1px solid var(--dash-slate-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
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
        box-shadow: 0 2px 6px rgba(2, 75, 134, 0.25);
    }

    .btn-save-modal:hover {
        background: #0369a1;
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
                Konfigurasi daftar checklist dokumen wajib untuk setiap <strong>Nama Belanja</strong> dan <strong>Jenis Belanja</strong>. Setiap penambahan atau perubahan dokumen di sini otomatis sinkron ke formulir pengajuan SPJ Staf Teknis dan Subbag Umum.
            </p>
        </div>
        <div>
            <button type="button" class="btn-add-doc" onclick="openAddDocModal()">
                <i class="bi bi-plus-circle-fill"></i>
                Tambah Dokumen / Jenis Belanja Baru
            </button>
        </div>
    </div>

    <!-- 2. STATS CARDS -->
    <div class="doc-stats-grid">
        <div class="doc-stat-card">
            <div class="doc-stat-icon purple">
                <i class="bi bi-tags-fill"></i>
            </div>
            <div>
                <div class="doc-stat-val">{{ $totalJenisBelanja }}</div>
                <div class="doc-stat-lbl">Jenis Belanja Terdaftar</div>
            </div>
        </div>
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
                <div class="doc-stat-lbl">Dilengkapi Bendahara / PPK</div>
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
                <label class="filter-label-doc">Peruntukan</label>
                <select name="peruntukan" class="form-control-doc" onchange="this.form.submit()">
                    <option value="">-- Semua Peruntukan --</option>
                    <option value="semua" {{ request('peruntukan') === 'semua' ? 'selected' : '' }}>Dua-duanya (Teknis &amp; Umum)</option>
                    <option value="umum" {{ request('peruntukan') === 'umum' ? 'selected' : '' }}>Khusus Subbag Umum</option>
                    <option value="teknis" {{ request('peruntukan') === 'teknis' ? 'selected' : '' }}>Khusus Staf Teknis</option>
                </select>
            </div>
            <div>
                <label class="filter-label-doc">Cari Dokumen / Belanja</label>
                <input type="text" name="search" class="form-control-doc" placeholder="Ketik kata kunci pencarian..." value="{{ request('search') }}">
            </div>
            <div>
                <button type="submit" class="btn-save-modal" style="height: 38px; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="bi bi-search"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- 4. TABEL PERSYARATAN DIKELOMPOKKAN PER JENIS BELANJA (TIDAK DOBLE-DOBLE) -->
    <div class="belanja-groups-wrapper">
        @forelse($groupedDokumens as $groupKey => $docs)
            @php
                $first = $docs->first();
                $namaBelanja = $first->nama_belanja;
                $jenisBelanja = $first->jenis_belanja;
                $peruntukanVal = $first->peruntukan ?: \App\Models\SyaratDokumenSpj::determinePeruntukanByKode($namaBelanja);

                $akunKode = '52';
                if (str_contains($namaBelanja, '51')) $akunKode = '51';
                elseif (str_contains($namaBelanja, '53')) $akunKode = '53';
            @endphp
            <div class="belanja-group-card">
                <div class="belanja-group-header">
                    <div class="belanja-header-left">
                        <span class="badge-akun-kode akun-{{ $akunKode }}">{{ $namaBelanja }}</span>
                        <h3 class="jenis-belanja-title">{{ $jenisBelanja }}</h3>
                        @if($peruntukanVal === 'umum')
                            <span class="badge-peruntukan umum" title="Khusus Form Pengajuan Subbagian Umum (Akun 51 & 53)">
                                <i class="bi bi-building"></i> Khusus Umum
                            </span>
                        @elseif($peruntukanVal === 'teknis')
                            <span class="badge-peruntukan teknis" title="Khusus Form Pengajuan Staf Teknis">
                                <i class="bi bi-laptop"></i> Khusus Teknis
                            </span>
                        @else
                            <span class="badge-peruntukan semua" title="Dua-duanya: Berlaku untuk Teknis dan Subbag Umum">
                                <i class="bi bi-people-fill"></i> Dua-duanya
                            </span>
                        @endif
                        <span class="badge-doc-count"><i class="bi bi-file-earmark-text"></i> {{ $docs->count() }} Dokumen</span>
                    </div>
                    <div>
                        <button type="button" class="btn-quick-add" onclick="openAddDocForGroup('{{ addslashes($namaBelanja) }}', '{{ addslashes($jenisBelanja) }}', '{{ $peruntukanVal }}')">
                            <i class="bi bi-plus-lg"></i> Tambah Dokumen (+)
                        </button>
                    </div>
                </div>

                <div class="belanja-group-body">
                    <table class="doc-table-inner">
                        <thead>
                            <tr>
                                <th style="width: 50px; text-align: center;">No</th>
                                <th>Nama Dokumen Persyaratan</th>
                                <th style="width: 240px; text-align: center;">Pihak Penyiap (Assignee)</th>
                                <th style="width: 80px; text-align: center;">Urutan</th>
                                <th style="width: 100px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($docs as $dIndex => $doc)
                            <tr>
                                <td style="text-align: center; color: var(--dash-slate-500); font-weight: 600;">
                                    {{ $dIndex + 1 }}
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: #0f172a; font-size: 13.5px;">
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div style="background: #ffffff; border-radius: var(--dash-radius-xl); border: 1px solid var(--dash-slate-200); padding: 48px 16px; text-align: center; color: var(--dash-slate-500);">
                <i class="bi bi-inbox" style="font-size: 40px; display: block; margin-bottom: 10px; opacity: 0.4;"></i>
                <h4 style="margin: 0 0 6px 0; color: var(--bps-navy); font-size: 16px;">Tidak ada dokumen persyaratan ditemukan</h4>
                <p style="margin: 0; font-size: 13px;">Silakan sesuaikan kata kunci filter atau klik tombol "Tambah Dokumen / Jenis Belanja Baru" di atas.</p>
            </div>
        @endforelse
    </div>

</div>

<!-- 5. MODAL TAMBAH DOKUMEN / JENIS BELANJA (MENDUKUNG INPUT BANYAK DENGAN TOMBOL +) -->
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

                <!-- Peruntukan / Berlaku Untuk Siapa -->
                <div class="form-group-modal">
                    <label class="form-label-modal">Peruntukan / Berlaku Untuk Siapa <span style="color: #ef4444;">*</span></label>
                    <select name="peruntukan" id="add_peruntukan" class="form-control-doc" required>
                        <option value="semua">Dua-duanya (Teknis &amp; Subbag Umum)</option>
                        <option value="umum">Khusus Subbag Umum (Contoh: Akun 51 &amp; Akun 53)</option>
                        <option value="teknis">Khusus Staf Teknis</option>
                    </select>
                    <div class="form-help-modal">Akun 51 (Pegawai) &amp; Akun 53 (Modal) otomatis disarankan khusus Umum, Akun 52 untuk dua-duanya.</div>
                </div>

                <!-- DAFTAR DOKUMEN MULTI-INPUT (BISA BANYAK DENGAN TANDA PLUS) -->
                <div class="form-group-modal" style="margin-top: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label class="form-label-modal" style="margin: 0;">
                            Daftar Dokumen yang Ditambahkan <span style="color: #ef4444;">*</span>
                        </label>
                        <span style="font-size: 11px; color: var(--dash-slate-500);">Bisa tambah banyak sekaligus via tombol (+)</span>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 170px 75px 36px; gap: 8px; padding: 0 10px 4px 10px; font-size: 11px; font-weight: 700; color: var(--dash-slate-500); text-transform: uppercase;">
                        <span>Nama Dokumen</span>
                        <span>Pihak Penyiap</span>
                        <span style="text-align: center;">Urutan</span>
                        <span></span>
                    </div>

                    <div class="doc-input-rows-container" id="doc_input_rows_container">
                        <!-- Baris Pertama -->
                        <div class="doc-input-row" data-row-index="0">
                            <input type="text" name="dokumen[0][nama_dokumen]" class="form-control-doc" placeholder="Contoh: KAK / Laporan / BAST" required>
                            <select name="dokumen[0][assignee]" class="form-control-doc" required>
                                <option value="SM" selected>SM (Teknis)</option>
                                <option value="Bendahara">Bendahara</option>
                                <option value="PPK">PPK</option>
                            </select>
                            <input type="number" name="dokumen[0][urutan]" class="form-control-doc" value="1" min="1" style="text-align: center;" placeholder="1">
                            <button type="button" class="btn-remove-row" onclick="removeDocRow(this)" disabled title="Baris pertama tidak dapat dihapus">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Tombol Plus Tambah Dokumen Lain -->
                    <button type="button" class="btn-add-more-row" onclick="addDocRow()">
                        <i class="bi bi-plus-circle-fill"></i> Tambah Dokumen Lain (+)
                    </button>
                </div>
            </div>
            <div class="modal-footer-custom">
                <span style="font-size: 12px; color: var(--dash-slate-500);" id="doc_rows_counter_hint">1 dokumen siap disimpan</span>
                <div style="display: flex; gap: 8px;">
                    <button type="button" class="btn-cancel-modal" onclick="closeAddDocModal()">Batal</button>
                    <button type="submit" class="btn-save-modal">Simpan Semua Dokumen</button>
                </div>
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

                <!-- Peruntukan -->
                <div class="form-group-modal">
                    <label class="form-label-modal">Peruntukan <span style="color: #ef4444;">*</span></label>
                    <select name="peruntukan" id="edit_peruntukan" class="form-control-doc" required>
                        <option value="semua">Dua-duanya (Teknis &amp; Subbag Umum)</option>
                        <option value="umum">Khusus Subbag Umum</option>
                        <option value="teknis">Khusus Staf Teknis</option>
                    </select>
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
                <div></div>
                <div style="display: flex; gap: 8px;">
                    <button type="button" class="btn-cancel-modal" onclick="closeEditDocModal()">Batal</button>
                    <button type="submit" class="btn-save-modal">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let nextRowIndex = 1;

    function detectPeruntukanFromText(text) {
        if (!text) return null;
        if (/\(51\d*\)/i.test(text) || /\(53\d*\)/i.test(text) || /\b51\d{4}\b/.test(text) || /\b53\d{4}\b/.test(text)) {
            return 'umum';
        }
        if (/\(52\d*\)/i.test(text) || /\b52\d{4}\b/.test(text)) {
            return 'semua';
        }
        return null;
    }

    function updateRowCounters() {
        const rows = document.querySelectorAll('#doc_input_rows_container .doc-input-row');
        const count = rows.length;
        const hint = document.getElementById('doc_rows_counter_hint');
        if (hint) {
            hint.textContent = `${count} dokumen siap disimpan`;
        }

        rows.forEach((row, idx) => {
            const removeBtn = row.querySelector('.btn-remove-row');
            if (removeBtn) {
                removeBtn.disabled = (count <= 1);
            }
        });
    }

    function addDocRow() {
        const container = document.getElementById('doc_input_rows_container');
        const rows = container.querySelectorAll('.doc-input-row');
        const newUrutan = rows.length + 1;

        const rowDiv = document.createElement('div');
        rowDiv.className = 'doc-input-row';
        rowDiv.setAttribute('data-row-index', nextRowIndex);
        rowDiv.innerHTML = `
            <input type="text" name="dokumen[${nextRowIndex}][nama_dokumen]" class="form-control-doc" placeholder="Nama dokumen persyaratan" required>
            <select name="dokumen[${nextRowIndex}][assignee]" class="form-control-doc" required>
                <option value="SM" selected>SM (Teknis)</option>
                <option value="Bendahara">Bendahara</option>
                <option value="PPK">PPK</option>
            </select>
            <input type="number" name="dokumen[${nextRowIndex}][urutan]" class="form-control-doc" value="${newUrutan}" min="1" style="text-align: center;">
            <button type="button" class="btn-remove-row" onclick="removeDocRow(this)" title="Hapus baris dokumen ini">
                <i class="bi bi-x-lg"></i>
            </button>
        `;
        container.appendChild(rowDiv);
        nextRowIndex++;
        updateRowCounters();

        // Focus input baru
        const firstInput = rowDiv.querySelector('input[type="text"]');
        if (firstInput) firstInput.focus();
    }

    function removeDocRow(button) {
        const row = button.closest('.doc-input-row');
        const container = document.getElementById('doc_input_rows_container');
        if (container.querySelectorAll('.doc-input-row').length > 1) {
            row.remove();
            updateRowCounters();
        }
    }

    function openAddDocModal() {
        document.getElementById('modalAddDoc').style.display = 'flex';
    }

    function closeAddDocModal() {
        document.getElementById('modalAddDoc').style.display = 'none';
    }

    function openAddDocForGroup(namaBelanja, jenisBelanja, peruntukan) {
        const selNama = document.getElementById('add_select_nama_belanja');
        const inpNama = document.getElementById('add_input_nama_belanja');
        const selJenis = document.getElementById('add_select_jenis_belanja');
        const inpJenis = document.getElementById('add_input_jenis_belanja');
        const selPeruntukan = document.getElementById('add_peruntukan');

        if (selNama) selNama.value = namaBelanja || '';
        if (inpNama) inpNama.value = namaBelanja || '';
        if (selJenis) selJenis.value = jenisBelanja || '';
        if (inpJenis) inpJenis.value = jenisBelanja || '';
        if (selPeruntukan && peruntukan) selPeruntukan.value = peruntukan;

        openAddDocModal();
    }

    function handleAddNamaBelanjaChange(val) {
        const inp = document.getElementById('add_input_nama_belanja');
        if (val === '__NEW__') {
            inp.value = '';
            inp.focus();
        } else if (val) {
            inp.value = val;
            const detected = detectPeruntukanFromText(val);
            if (detected) {
                document.getElementById('add_peruntukan').value = detected;
            }
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
        document.getElementById('edit_peruntukan').value = doc.peruntukan || (detectPeruntukanFromText(doc.nama_belanja) || 'semua');
        document.getElementById('edit_assignee').value = doc.assignee || 'SM';
        document.getElementById('edit_urutan').value = doc.urutan || 1;
        document.getElementById('modalEditDoc').style.display = 'flex';
    }

    function closeEditDocModal() {
        document.getElementById('modalEditDoc').style.display = 'none';
    }

    // Auto-detect peruntukan saat mengetik nama belanja baru
    document.addEventListener('DOMContentLoaded', function() {
        const addInput = document.getElementById('add_input_nama_belanja');
        if (addInput) {
            addInput.addEventListener('input', function() {
                const detected = detectPeruntukanFromText(this.value);
                if (detected) {
                    document.getElementById('add_peruntukan').value = detected;
                }
            });
        }

        const editInput = document.getElementById('edit_nama_belanja');
        if (editInput) {
            editInput.addEventListener('input', function() {
                const detected = detectPeruntukanFromText(this.value);
                if (detected) {
                    document.getElementById('edit_peruntukan').value = detected;
                }
            });
        }

        updateRowCounters();
    });

    // Close modal when clicking backdrop
    window.addEventListener('click', function(e) {
        if (e.target.id === 'modalAddDoc') closeAddDocModal();
        if (e.target.id === 'modalEditDoc') closeEditDocModal();
    });
</script>
@endpush

@endsection
