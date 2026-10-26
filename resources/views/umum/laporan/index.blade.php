@extends('layouts.umum')

@php
$title = 'Laporan Rekapitulasi SPJ';
$subtitle = 'Rekapitulasi dan cetak laporan SPJ untuk Pimpinan dan Arsip Umum.';
@endphp

@push('styles')
    @vite(['resources/css/umum/laporan.css'])
@endpush

@section('content')
<div class="laporan-container">

    {{-- ======================================================== --}}
    {{-- ELEMEN KHUSUS CETAK BROWSER (@media print)               --}}
    {{-- ======================================================== --}}
    <div class="print-only">
        <table class="print-kop-table">
            <tr>
                <td style="width: 40%;">
                    @php
                        $logoPath = public_path('images/logo-bps.png');
                        $logoSrc = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : '';
                    @endphp
                    @if($logoSrc)
                        <img src="{{ $logoSrc }}" style="width: 140px; height: auto;" alt="Logo BPS">
                    @else
                        <strong style="font-size: 13pt; color: #1e4253;">BADAN PUSAT STATISTIK</strong>
                    @endif
                </td>
                <td style="width: 60%; text-align: right;">
                    <div style="font-size: 13pt; font-weight: bold; color: #1e4253; text-transform: uppercase;">Badan Pusat Statistik</div>
                    <div style="font-size: 11pt; font-weight: 600; color: #334155;">Kabupaten Kolaka Utara</div>
                    <div style="font-size: 8.5pt; color: #64748b; margin-top: 2px;">Jl. Trans Sulawesi Kompleks Perkantoran Pemda Kolaka Utara, Lasusua</div>
                    <div style="font-size: 8pt; color: #64748b;">Email: bps7408@bps.go.id | Website: kolakautarakab.bps.go.id</div>
                </td>
            </tr>
        </table>

        <div class="print-doc-title">
            <h2>Laporan Rekapitulasi Surat Pertanggungjawaban (SPJ)</h2>
            <p><strong>{{ $periodeLabel }}</strong> &bull; Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WITA</p>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- BREADCRUMB & HEADER SECTION                              --}}
    {{-- ======================================================== --}}
    <div class="laporan-breadcrumb">
        <a href="{{ route('umum.dashboard') }}">Dashboard</a>
        <ion-icon name="chevron-forward-outline"></ion-icon>
        <span style="color: var(--dash-text-dark); font-weight: 600;">Laporan & Rekapitulasi SPJ</span>
    </div>

    <div class="laporan-header-wrap">
        <div class="laporan-title-group">
            <h2>Rekapitulasi & Laporan SPJ</h2>
            <p>Penyaringan data komprehensif untuk pelaporan pimpinan, audit internal, dan pencetakan dokumen dinas.</p>
            <div class="laporan-badge-periode">
                <ion-icon name="calendar-outline"></ion-icon>
                <span>{{ $periodeLabel }}</span>
            </div>
        </div>

        <div class="laporan-actions">
            <!-- Tombol Cetak Browser -->
            <button type="button" class="btn-print-browser" onclick="window.print()" title="Cetak langsung melalui jendela browser">
                <ion-icon name="print-outline" style="font-size: 17px; color: #0284c7;"></ion-icon>
                <span>Cetak Cepat</span>
            </button>

            <!-- Tombol Unduh PDF Resmi -->
            <a href="{{ route('umum.laporan.print', request()->query()) }}" target="_blank" class="btn-download-pdf" title="Cetak dokumen resmi PDF Landscape A4 dengan Kop BPS dan Tanda Tangan Pimpinan">
                <ion-icon name="document-text-outline" style="font-size: 17px;"></ion-icon>
                <span>Ekspor PDF Resmi (Pimpinan)</span>
            </a>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- RINGKASAN EKSEKUTIF (STAT KPI CARDS)                     --}}
    {{-- ======================================================== --}}
    <div class="laporan-stats-grid">
        <!-- Card 1: Total Berkas -->
        <div class="laporan-stat-card stat-blue">
            <div>
                <div class="stat-card-header">
                    <span class="stat-label-text">Total Berkas SPJ</span>
                    <div class="stat-icon-capsule">
                        <ion-icon name="documents-outline"></ion-icon>
                    </div>
                </div>
                <div class="stat-value-text">{{ number_format($stats['totalSpj'], 0, ',', '.') }}</div>
            </div>
            <div class="stat-footer-text">
                <ion-icon name="checkmark-circle-outline" style="color: #0284c7;"></ion-icon>
                <span>Tercatat dalam periode terpilih</span>
            </div>
        </div>

        <!-- Card 2: Total Anggaran -->
        <div class="laporan-stat-card stat-green">
            <div>
                <div class="stat-card-header">
                    <span class="stat-label-text">Total Nilai Anggaran</span>
                    <div class="stat-icon-capsule">
                        <ion-icon name="wallet-outline"></ion-icon>
                    </div>
                </div>
                <div class="stat-value-text">Rp {{ number_format($stats['totalNilai'], 0, ',', '.') }}</div>
            </div>
            <div class="stat-footer-text">
                <ion-icon name="trending-up-outline" style="color: #10b981;"></ion-icon>
                <span>Akumulasi nilai seluruh berkas</span>
            </div>
        </div>

        <!-- Card 3: Telah Cair -->
        <div class="laporan-stat-card stat-orange">
            <div>
                <div class="stat-card-header">
                    <span class="stat-label-text">Telah Cair / Selesai</span>
                    <div class="stat-icon-capsule">
                        <ion-icon name="checkmark-done-circle-outline"></ion-icon>
                    </div>
                </div>
                <div class="stat-value-text">Rp {{ number_format($stats['totalNilaiSelesai'], 0, ',', '.') }}</div>
            </div>
            <div class="stat-footer-text">
                <strong style="color: #10b981;">{{ $stats['totalSelesai'] }} Berkas</strong> telah dicairkan
            </div>
        </div>

        <!-- Card 4: Dalam Proses & Revisi -->
        <div class="laporan-stat-card stat-purple">
            <div>
                <div class="stat-card-header">
                    <span class="stat-label-text">Proses & Kendala</span>
                    <div class="stat-icon-capsule">
                        <ion-icon name="hourglass-outline"></ion-icon>
                    </div>
                </div>
                <div class="stat-value-text">{{ $stats['totalDiproses'] + $stats['totalRevisi'] }} Berkas</div>
            </div>
            <div class="stat-footer-text">
                <span>{{ $stats['totalDiproses'] }} verifikasi &bull; {{ $stats['totalRevisi'] }} revisi</span>
            </div>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- PANEL KONTROL FILTER (TWO-TIER STRUCTURE)                --}}
    {{-- ======================================================== --}}
    <div class="filter-card">
        <form action="{{ route('umum.laporan.index') }}" method="GET" id="filterForm">
            <input type="hidden" name="filter_type" id="filterTypeInput" value="{{ $filters['filter_type'] }}">

            <!-- TIER 1: PEMILIHAN PERIODE (SEGMENTED SWITCHER & ANIMATED PANELS) -->
            <div class="filter-section-title">
                <ion-icon name="options-outline" style="color: #0284c7; font-size: 15px;"></ion-icon>
                <span>1. Tentukan Periode Waktu SPJ</span>
            </div>

            <!-- Segmented Pill Switcher Track -->
            <div class="segmented-control-track" role="tablist">
                <button type="button" 
                        class="segment-pill-btn {{ $filters['filter_type'] === 'bulan' ? 'active' : '' }}" 
                        data-mode="bulan"
                        onclick="switchFilterMode('bulan')">
                    <ion-icon name="calendar-outline"></ion-icon>
                    <span>Filter Bulan Tertentu</span>
                </button>
                <button type="button" 
                        class="segment-pill-btn {{ $filters['filter_type'] === 'tanggal' ? 'active' : '' }}" 
                        data-mode="tanggal"
                        onclick="switchFilterMode('tanggal')">
                    <ion-icon name="today-outline"></ion-icon>
                    <span>Filter Rentang / Tanggal Tertentu</span>
                </button>
                <button type="button" 
                        class="segment-pill-btn {{ $filters['filter_type'] === 'semua' ? 'active' : '' }}" 
                        data-mode="semua"
                        onclick="switchFilterMode('semua')">
                    <ion-icon name="infinite-outline"></ion-icon>
                    <span>Semua Waktu (Seluruh SPJ)</span>
                </button>
            </div>

            <!-- Dynamic Animated Period Panels Container -->
            <div class="period-panels-wrapper">
                <!-- Panel 1: Filter Bulan & Tahun -->
                <div id="panel-bulan" class="period-panel {{ $filters['filter_type'] === 'bulan' ? 'active' : '' }}">
                    <div class="period-panel-grid">
                        <div class="filter-input-group">
                            <label>Pilih Bulan</label>
                            <select name="bulan" class="dash-select">
                                @foreach($daftarBulan as $num => $nama)
                                    <option value="{{ $num }}" {{ ($filters['bulan'] ?? (int)date('m')) == $num ? 'selected' : '' }}>
                                        {{ $nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-input-group">
                            <label>Tahun Anggaran</label>
                            <select name="tahun" class="dash-select">
                                @foreach($daftarTahun as $thn)
                                    <option value="{{ $thn }}" {{ ($filters['tahun'] ?? (int)date('Y')) == $thn ? 'selected' : '' }}>
                                        Tahun {{ $thn }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="period-hint-box">
                        <ion-icon name="information-circle-outline"></ion-icon>
                        <span>Menyaring seluruh berkas SPJ yang tercatat pada bulan dan tahun anggaran yang dipilih.</span>
                    </div>
                </div>

                <!-- Panel 2: Filter Rentang / Tanggal Spesifik -->
                <div id="panel-tanggal" class="period-panel {{ $filters['filter_type'] === 'tanggal' ? 'active' : '' }}">
                    <div class="period-panel-grid">
                        <div class="filter-input-group">
                            <label>Dari Tanggal (atau Tanggal Spesifik)</label>
                            <input type="date" name="tanggal_mulai" id="inputTanggalMulai" value="{{ $filters['tanggal_mulai'] }}" class="dash-input">
                        </div>
                        <div class="filter-input-group">
                            <label>Sampai Tanggal</label>
                            <input type="date" name="tanggal_selesai" id="inputTanggalSelesai" value="{{ $filters['tanggal_selesai'] }}" class="dash-input" placeholder="Opsional jika hanya 1 hari">
                        </div>
                    </div>
                    <div class="period-hint-box">
                        <ion-icon name="information-circle-outline"></ion-icon>
                        <span>Untuk 1 tanggal tertentu, cukup isi "Dari Tanggal" atau samakan kedua tanggal.</span>
                    </div>
                </div>

                <!-- Panel 3: Semua Waktu -->
                <div id="panel-semua" class="period-panel {{ $filters['filter_type'] === 'semua' ? 'active' : '' }}">
                    <div class="period-all-info">
                        <ion-icon name="globe-outline"></ion-icon>
                        <div>
                            <strong>Mode Seluruh Periode Aktif.</strong> Sistem akan menampilkan rekapitulasi data SPJ secara komprehensif tanpa batasan waktu pelaksanaan kegiatan.
                        </div>
                    </div>
                </div>
            </div>

            <!-- DIVIDER PEMISAH ANTAR-ZONA -->
            <div class="filter-divider"></div>

            <!-- TIER 2: KRITERIA FILTER TAMBAHAN & EKSEKUSI (FIXED STABLE GRID) -->
            <div class="filter-section-title">
                <ion-icon name="filter-outline" style="color: #0284c7; font-size: 15px;"></ion-icon>
                <span>2. Kriteria Penyaringan Tambahan</span>
            </div>

            <div class="filter-criteria-grid">
                <!-- Status Berkas -->
                <div class="filter-input-group">
                    <label>Status Berkas</label>
                    <select name="status" class="dash-select">
                        <option value="semua">Semua Status</option>
                        <option value="selesai" {{ $filters['status'] === 'selesai' ? 'selected' : '' }}>Selesai (Cair / SPM)</option>
                        <option value="diproses" {{ $filters['status'] === 'diproses' ? 'selected' : '' }}>Sedang Diproses (Verifikasi)</option>
                        <option value="diajukan" {{ $filters['status'] === 'diajukan' ? 'selected' : '' }}>Baru Diajukan (Di Meja Umum)</option>
                        <option value="disetujui_umum" {{ $filters['status'] === 'disetujui_umum' ? 'selected' : '' }}>Disetujui Umum (Di PPK)</option>
                        <option value="disetujui_ppk" {{ $filters['status'] === 'disetujui_ppk' ? 'selected' : '' }}>Disetujui PPK (Di PPSPM)</option>
                        <option value="disetujui_ppspm" {{ $filters['status'] === 'disetujui_ppspm' ? 'selected' : '' }}>Disetujui PPSPM (Di Bendahara)</option>
                        <option value="revisi" {{ $filters['status'] === 'revisi' ? 'selected' : '' }}>Dikembalikan / Perlu Revisi</option>
                    </select>
                </div>

                <!-- Pengaju Teknis -->
                <div class="filter-input-group">
                    <label>Pengaju (Teknis)</label>
                    <select name="user_id" class="dash-select">
                        <option value="semua">Semua Pengaju</option>
                        @foreach($pengajus as $pengaju)
                            <option value="{{ $pengaju->id }}" {{ $filters['user_id'] == $pengaju->id ? 'selected' : '' }}>
                                {{ $pengaju->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Pencarian Kata Kunci -->
                <div class="filter-input-group">
                    <label>Cari Nomor SPJ / Nama Kegiatan</label>
                    <input type="text" name="search" value="{{ $filters['search'] }}" placeholder="Ketik kata kunci pencarian..." class="dash-input">
                </div>

                <!-- Tombol Aksi -->
                <div class="filter-actions-group">
                    <button type="submit" class="btn-dash-primary" title="Terapkan seluruh kriteria penyaringan">
                        <ion-icon name="funnel-outline"></ion-icon> Terapkan Filter
                    </button>
                    @if(request()->hasAny(['filter_type', 'bulan', 'tahun', 'tanggal_mulai', 'tanggal_selesai', 'status', 'user_id', 'search']))
                        <a href="{{ route('umum.laporan.index') }}" class="btn-dash-reset" title="Kembalikan ke tampilan default">
                            <ion-icon name="refresh-outline"></ion-icon> Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- ======================================================== --}}
    {{-- TABEL REKAPITULASI DATA SPJ                              --}}
    {{-- ======================================================== --}}
    <div class="panel-card">
        <div class="panel-header">
            <h3 class="panel-header-title">
                <ion-icon name="list-outline" style="font-size: 19px; color: #0284c7;"></ion-icon>
                <span>Daftar SPJ Sesuai Hasil Filter</span>
            </h3>
            <span style="font-size: 12.5px; font-weight: 600; color: var(--dash-text-muted);">
                Ditemukan <strong>{{ $spjs->count() }}</strong> Berkas Dokumen
            </span>
        </div>

        <div class="modern-table-wrapper">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th style="width: 45px; text-align: center;">No</th>
                        <th style="width: 140px;">Nomor SPJ</th>
                        <th style="width: 110px;">Tanggal SPJ</th>
                        <th style="width: 160px;">Pengaju (Teknis)</th>
                        <th>Nama Kegiatan</th>
                        <th style="width: 140px; text-align: right;">Nilai (Rp)</th>
                        <th style="width: 130px; text-align: center;">Status Terkini</th>
                        <th style="width: 130px;">Tgl Cair / Update</th>
                        <th style="width: 80px; text-align: center;" class="no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($spjs as $index => $spj)
                    <tr>
                        <td style="text-align: center; font-weight: 600; color: var(--dash-text-muted);">{{ $index + 1 }}</td>
                        <td>
                            <strong style="color: var(--dash-text-dark); font-size: 13px; font-family: monospace;">{{ $spj->nomor_spj ?: 'SPJ-'.$spj->id }}</strong>
                            @if($spj->nomor_spm)
                                <div style="font-size: 11px; color: #0284c7; margin-top: 2px; font-weight: 600;">
                                    SPM: {{ $spj->nomor_spm }}
                                </div>
                            @endif
                        </td>
                        <td style="white-space: nowrap; font-size: 12.5px;">
                            {{ $spj->tanggal ? $spj->tanggal->translatedFormat('d M Y') : '-' }}
                        </td>
                        <td>
                            <div style="font-weight: 600; color: var(--dash-text-dark);">{{ $spj->user->name ?? 'Staf Teknis' }}</div>
                            <div style="font-size: 11px; color: var(--dash-text-muted);">{{ $spj->user->email ?? '-' }}</div>
                        </td>
                        <td>
                            <div style="max-width: 280px; line-height: 1.4; color: var(--dash-text-dark); font-weight: 500;">
                                {{ $spj->kegiatan }}
                            </div>
                        </td>
                        <td style="text-align: right; font-weight: 700; color: var(--dash-text-dark); font-variant-numeric: tabular-nums; white-space: nowrap;">
                            Rp {{ number_format($spj->nilai, 0, ',', '.') }}
                        </td>
                        <td style="text-align: center;">
                            @php
                                $badgeClass = 'diajukan';
                                $statusLabel = 'Diajukan';

                                if ($spj->status === 'selesai') {
                                    $badgeClass = 'selesai';
                                    $statusLabel = 'Selesai (Cair)';
                                } elseif ($spj->status === 'disetujui_ppspm') {
                                    $badgeClass = 'disetujui_ppspm';
                                    $statusLabel = 'Di Bendahara';
                                } elseif ($spj->status === 'disetujui_ppk') {
                                    $badgeClass = 'disetujui_ppk';
                                    $statusLabel = 'Di PPSPM';
                                } elseif ($spj->status === 'disetujui_umum') {
                                    $badgeClass = 'disetujui_umum';
                                    $statusLabel = 'Di PPK';
                                } elseif (str_contains($spj->status, 'revisi')) {
                                    $badgeClass = 'revisi';
                                    $statusLabel = 'Revisi (' . strtoupper(str_replace('revisi_', '', $spj->status)) . ')';
                                }
                            @endphp
                            <span class="badge-status {{ $badgeClass }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td style="font-size: 12px; color: var(--dash-text-muted); white-space: nowrap;">
                            @if($spj->diselesaikan_at)
                                <div style="color: #10b981; font-weight: 600;">{{ $spj->diselesaikan_at->translatedFormat('d M Y') }}</div>
                                <div style="font-size: 10.5px;">Cair / Tuntas</div>
                            @else
                                <div>{{ $spj->updated_at->translatedFormat('d M Y, H:i') }}</div>
                            @endif
                        </td>
                        <td style="text-align: center; white-space: nowrap;" class="no-print">
                            <div style="display: inline-flex; gap: 6px; align-items: center; justify-content: center;">
                                <a href="{{ route('umum.spj.show', $spj->id) }}" 
                                   class="btn-dash-reset" 
                                   style="padding: 6px 10px; font-size: 12px;" 
                                   title="Lihat Detail & Riwayat SPJ">
                                    <ion-icon name="eye-outline"></ion-icon>
                                </a>
                                <a href="{{ route('umum.spj.print', $spj->id) }}" 
                                   target="_blank"
                                   class="btn-dash-reset" 
                                   style="padding: 6px 10px; font-size: 12px; color: #0284c7;" 
                                   title="Cetak Lembar Kendali & Checklist SPJ">
                                    <ion-icon name="print-outline"></ion-icon>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <ion-icon name="file-tray-outline"></ion-icon>
                                <p style="font-size: 15px; font-weight: 600; color: var(--dash-text-dark); margin: 0 0 6px 0;">
                                    Tidak ada data SPJ pada periode ini.
                                </p>
                                <p style="font-size: 13px; margin: 0;">
                                    Silakan sesuaikan filter bulan, rentang tanggal, atau kriteria status di atas.
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($spjs->isNotEmpty())
                <tfoot>
                    <tr>
                        <td colspan="5" style="text-align: right; text-transform: uppercase;">Total Keseluruhan ({{ $spjs->count() }} Berkas SPJ):</td>
                        <td style="text-align: right; font-variant-numeric: tabular-nums; font-size: 14px; font-weight: 800; color: #0284c7;">
                            Rp {{ number_format($stats['totalNilai'], 0, ',', '.') }}
                        </td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- LEMBAR TANDA TANGAN KHUSUS PRINT (@media print)          --}}
    {{-- ======================================================== --}}
    <div class="print-only">
        <table class="print-ttd-table">
            <tr>
                <td style="width: 50%;">
                    <p>Mengetahui,</p>
                    <p><strong>Kepala Badan Pusat Statistik<br>Kabupaten Kolaka Utara</strong></p>
                    <div class="print-ttd-space"></div>
                    <p style="font-weight: bold; text-decoration: underline;">( ............................................................ )</p>
                    <p style="font-size: 8pt; color: #555555; margin-top: 2px;">NIP. ......................................................</p>
                </td>
                <td style="width: 50%;">
                    <p>Lasusua, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                    <p><strong>Pengadministrasi / Verifikator Umum<br>Subbagian Umum</strong></p>
                    <div class="print-ttd-space"></div>
                    <p style="font-weight: bold; text-decoration: underline;">( {{ auth()->user()->name ?? 'Petugas Verifikasi Umum' }} )</p>
                    <p style="font-size: 8pt; color: #555555; margin-top: 2px;">NIP. {{ auth()->user()->nip ?? '......................................................' }}</p>
                </td>
            </tr>
        </table>
    </div>

</div>

{{-- SCRIPT INTERAKTIF SWITCHER DENGAN TRANSISI HALUS --}}
<script>
function switchFilterMode(mode) {
    const inputType = document.getElementById('filterTypeInput');
    if (inputType.value === mode) return;
    inputType.value = mode;

    // 1. Update status active pada segmented pill switcher
    const buttons = document.querySelectorAll('.segment-pill-btn');
    buttons.forEach(btn => {
        if (btn.getAttribute('data-mode') === mode) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });

    // 2. Transisi halus antar-panel
    const allPanels = document.querySelectorAll('.period-panel');
    const targetPanel = document.getElementById('panel-' + mode);

    allPanels.forEach(panel => {
        if (panel.classList.contains('active')) {
            // Fade-out panel aktif saat ini
            panel.style.opacity = '0';
            panel.style.transform = 'translateY(-6px)';
            setTimeout(() => {
                panel.classList.remove('active');
                panel.style.display = 'none';

                // Tampilkan target panel dengan slide-down & fade-in
                if (targetPanel) {
                    targetPanel.style.display = 'block';
                    // Paksa reflow browser agar transisi CSS berjalan
                    void targetPanel.offsetHeight;
                    targetPanel.classList.add('active');
                    targetPanel.style.opacity = '1';
                    targetPanel.style.transform = 'translateY(0)';
                }
            }, 180);
        }
    });

    // Jika belum ada panel aktif sama sekali
    const activePanels = document.querySelectorAll('.period-panel.active');
    if (activePanels.length === 0 && targetPanel) {
        targetPanel.style.display = 'block';
        void targetPanel.offsetHeight;
        targetPanel.classList.add('active');
        targetPanel.style.opacity = '1';
        targetPanel.style.transform = 'translateY(0)';
    }
}
</script>
@endsection
