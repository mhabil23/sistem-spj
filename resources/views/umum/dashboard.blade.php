@extends('layouts.umum')

@php
$title = 'Dashboard';
@endphp

@push('styles')
    @vite(['resources/css/umum/dashboard.css'])
@endpush

@section('content')
<div class="dashboard-main-container">

    <!-- 1. OVER VIEW CONTAINER (4 KARTU SPJ) -->
    <div class="overview-container">
        <div class="overview-title">Ringkasan SPJ (Over View)</div>
        <div class="overview-cards-grid">
            <!-- Card 1: Total SPJ Masuk -->
            <div class="overview-card">
                <div class="ov-icon-badge bg-mint">
                    <ion-icon name="document-text-outline"></ion-icon>
                </div>
                <div class="ov-info">
                    <div class="ov-value">{{ $totalSpj }}</div>
                    <div class="ov-label">Total SPJ Masuk</div>
                </div>
            </div>

            <!-- Card 2: Menunggu Pemeriksaan -->
            <div class="overview-card">
                <div class="ov-icon-badge bg-cyan">
                    <ion-icon name="time-outline"></ion-icon>
                </div>
                <div class="ov-info">
                    <div class="ov-value">{{ $totalAntrean }}</div>
                    <div class="ov-label">Antrean Pemeriksaan</div>
                    @if(isset($totalMenungguFisik) && $totalMenungguFisik > 0)
                        <div style="font-size: 11px; color: #ea580c; font-weight: 700; margin-top: 3px;">
                            +{{ $totalMenungguFisik }} Menunggu Fisik
                        </div>
                    @endif
                </div>
            </div>

            <!-- Card 3: Telah Disetujui Umum -->
            <div class="overview-card">
                <div class="ov-icon-badge bg-mint">
                    <ion-icon name="checkmark-done-circle-outline"></ion-icon>
                </div>
                <div class="ov-info">
                    <div class="ov-value">{{ $totalSelesai }}</div>
                    <div class="ov-label">Disetujui Umum</div>
                </div>
            </div>

            <!-- Card 4: Perlu Revisi (Peach Highlight) -->
            <div class="overview-card card-peach-highlight">
                <div class="ov-icon-badge bg-peach">
                    <ion-icon name="alert-circle-outline"></ion-icon>
                </div>
                <div class="ov-info">
                    <div class="ov-value">{{ $totalRevisi }}</div>
                    <div class="ov-label">Perlu Revisi</div>
                </div>
                <span class="ov-info-circle" title="Berkas yang dikembalikan ke staf teknis untuk perbaikan">i</span>
            </div>
        </div>
    </div>

    <!-- 2. INTERACTIVE GRID SECTION (USERS, DONUT, STORES/SEKSI, SPLINE CHART) -->
    <div class="dash-interactive-grid">
        
        <!-- Col 1, Row 1: Pengaju Teknis -->
        <div class="grid-card card-users">
            <div class="card-header-clean">
                <h3 class="card-title">Pengaju Teknis</h3>
                <button type="button" class="btn-more-dots" title="Menu">⋮</button>
            </div>
            <div class="card-users-body">
                <div class="users-icon-badge">
                    <ion-icon name="people-outline"></ion-icon>
                </div>
                <div class="users-data">
                    <div class="users-count">{{ $totalUsers }} <span style="font-size: 16px; font-weight: 500;">User</span></div>
                    <div class="users-sublabel">Staf Teknis Terdaftar</div>
                </div>
            </div>
        </div>

        <!-- Col 2, Row 1: Proporsi Status SPJ (Donut Chart) -->
        <div class="grid-card card-donut">
            <div class="card-header-clean">
                <h3 class="card-title">Proporsi Status SPJ</h3>
            </div>
            <div class="donut-chart-layout">
                <div class="donut-visual-wrapper">
                    @php
                        // Hitung stroke offset berdasarkan $pctSelesai
                        $circumference = 377;
                        $selesaiOffset = $circumference - ($circumference * ($pctSelesai / 100));
                    @endphp
                    <svg viewBox="0 0 160 160" class="donut-svg">
                        <!-- Dark Teal: Disetujui Umum -->
                        <circle cx="80" cy="80" r="60" fill="none" stroke="#23495d" stroke-width="26" stroke-dasharray="377" stroke-dashoffset="{{ $selesaiOffset }}" stroke-linecap="round"/>
                        <!-- Light Blue: Antrean / Revisi -->
                        <circle cx="80" cy="80" r="60" fill="none" stroke="#9ac1d6" stroke-width="26" stroke-dasharray="377" stroke-dashoffset="{{ $circumference - ($circumference * ($pctProses / 100)) }}" transform="rotate(245 80 80)" stroke-linecap="round"/>
                        <!-- Inner cutout hole -->
                        <circle cx="80" cy="80" r="47" class="donut-inner-hole"/>
                        <!-- Center Percentage Labels -->
                        <text x="80" y="74" text-anchor="middle" class="donut-pct-text" fill="#6790a6" transform="rotate(90 80 80)">{{ $pctProses }}%</text>
                        <text x="80" y="96" text-anchor="middle" class="donut-pct-text" fill="#23495d" transform="rotate(90 80 80)">{{ $pctSelesai }}%</text>
                    </svg>
                </div>
                <div class="donut-legend-stack">
                    <div class="donut-legend-item">
                        <span class="legend-box bg-dark-teal"></span>
                        <span class="legend-name">Disetujui ({{ $pctSelesai }}%)</span>
                    </div>
                    <div class="donut-legend-item">
                        <span class="legend-box bg-light-blue"></span>
                        <span class="legend-name">Proses & Revisi ({{ $pctProses }}%)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Col 3, Row 1-2: Pengajuan per Seksi / Staf Teknis (Tall Card) -->
        <div class="grid-card card-stores-tall">
            <div class="card-header-clean">
                <h3 class="card-title">Top Pengaju per Seksi/Tim</h3>
            </div>
            <div class="stores-ranking-list">
                @foreach($topPengaju as $pengaju)
                <div class="store-row-item">
                    <span class="store-label" title="{{ $pengaju['name'] }}">{{ $pengaju['name'] }}</span>
                    <div class="store-bar-track">
                        <div class="store-bar-fill" style="width: {{ $pengaju['bar'] }};"></div>
                    </div>
                    <span class="store-sales">{{ $pengaju['count'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Col 1-2, Row 2: Tren Pengajuan & Verifikasi SPJ (Spline Area Chart) -->
        <div class="grid-card card-expense-profit">
            <div class="card-header-clean">
                <h3 class="card-title">Tren Pengajuan & Verifikasi SPJ</h3>
                <div class="dropdown-pill-badge">
                    <span>6 Bulan Terakhir</span>
                </div>
            </div>
            <div class="spline-chart-wrapper">
                <svg viewBox="0 0 620 200" class="spline-svg">
                    <defs>
                        <linearGradient id="profitGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#4fa57d" stop-opacity="0.28"/>
                            <stop offset="100%" stop-color="#4fa57d" stop-opacity="0.0"/>
                        </linearGradient>
                        <linearGradient id="expenseGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#e09273" stop-opacity="0.25"/>
                            <stop offset="100%" stop-color="#e09273" stop-opacity="0.0"/>
                        </linearGradient>
                    </defs>

                    <!-- Dashed horizontal grid lines and Y axis ticks -->
                    <g class="chart-grid">
                        <line x1="45" y1="20" x2="615" y2="20" stroke="currentColor" opacity="0.15" stroke-dasharray="3 3"/>
                        <text x="20" y="24" class="chart-tick-y">40</text>

                        <line x1="45" y1="65" x2="615" y2="65" stroke="currentColor" opacity="0.15" stroke-dasharray="3 3"/>
                        <text x="20" y="69" class="chart-tick-y">30</text>

                        <line x1="45" y1="110" x2="615" y2="110" stroke="currentColor" opacity="0.15" stroke-dasharray="3 3"/>
                        <text x="20" y="114" class="chart-tick-y">20</text>

                        <line x1="45" y1="155" x2="615" y2="155" stroke="currentColor" opacity="0.15" stroke-dasharray="3 3"/>
                        <text x="20" y="159" class="chart-tick-y">10</text>
                    </g>

                    <!-- Area Fills -->
                    <!-- Orange: Diajukan Area -->
                    <path d="M 45 130 C 90 120, 130 148, 180 145 C 230 140, 260 125, 310 105 C 360 85, 410 115, 460 135 C 510 155, 560 148, 610 140 L 610 155 L 45 155 Z" fill="url(#expenseGrad)"/>
                    <!-- Green: Disetujui Area -->
                    <path d="M 45 120 C 95 145, 145 140, 195 130 C 255 120, 295 118, 355 105 C 415 95, 475 75, 535 50 C 565 38, 595 44, 610 46 L 610 155 L 45 155 Z" fill="url(#profitGrad)"/>

                    <!-- Spline Stroke Lines -->
                    <path d="M 45 130 C 90 120, 130 148, 180 145 C 230 140, 260 125, 310 105 C 360 85, 410 115, 460 135 C 510 155, 560 148, 610 140" fill="none" stroke="#e09273" stroke-width="2.5" stroke-linecap="round"/>
                    <path d="M 45 120 C 95 145, 145 140, 195 130 C 255 120, 295 118, 355 105 C 415 95, 475 75, 535 50 C 565 38, 595 44, 610 46" fill="none" stroke="#4fa57d" stroke-width="2.5" stroke-linecap="round"/>

                    <!-- Highest Expense Marker & Tag (Puncak Pengajuan) -->
                    <circle cx="370" cy="95" r="3.5" fill="#de7856"/>
                    <g transform="translate(320, 72)">
                        <rect x="0" y="0" width="102" height="20" rx="4" fill="#d97753"/>
                        <text x="51" y="13" class="chart-badge-text" text-anchor="middle">Puncak Pengajuan</text>
                    </g>

                    <!-- Highest Profit Marker & Tag (Puncak Verifikasi) -->
                    <circle cx="535" cy="50" r="3.5" fill="#2d7c57"/>
                    <g transform="translate(490, 24)">
                        <rect x="0" y="0" width="98" height="20" rx="4" fill="#2b7552"/>
                        <text x="49" y="13" class="chart-badge-text" text-anchor="middle">Puncak Verifikasi</text>
                    </g>

                    <!-- X Axis Months (Dinamis dari 6 bulan terakhir) -->
                    <g class="chart-ticks-x">
                        @foreach($trenBulanan as $idx => $tren)
                            @php
                                $posX = 75 + ($idx * 102);
                            @endphp
                            <text x="{{ $posX }}" y="182" text-anchor="middle">{{ $tren['bulan'] }}</text>
                        @endforeach
                    </g>
                </svg>
            </div>
        </div>
    </div>

    <!-- 3. ANTREAN PEMERIKSAAN SPJ TABLE SECTION (LIVE DATA) -->
    <div class="recent-table-card">
        <div class="table-header-row">
            <h2 class="table-header-title">Antrean Pemeriksaan SPJ</h2>
            <a href="{{ route('umum.spj.index') }}" class="table-view-all">Lihat Semua Kotak Masuk &rarr;</a>
        </div>
        
        <div style="overflow-x: auto;">
            <table class="modern-dash-table">
                <thead>
                    <tr>
                        <th>Tanggal Pengajuan</th>
                        <th>Nomor SPJ</th>
                        <th>Pengaju (Teknis)</th>
                        <th>Kegiatan</th>
                        <th>Nilai (Rp)</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($antreanSpjs as $spj)
                    <tr>
                        <td style="color: var(--dash-text-muted);">{{ ($spj->diajukan_at ? \Carbon\Carbon::parse($spj->diajukan_at) : $spj->created_at)->format('d M Y') }}</td>
                        <td style="font-weight: 600; color: var(--dash-text-dark);">{{ $spj->nomor_spj ?? '-' }}</td>
                        <td style="font-weight: 600; color: var(--dash-text-dark);">{{ $spj->user->name ?? 'Staf Teknis' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($spj->kegiatan, 40) }}</td>
                        <td style="font-weight: 600; color: #10b981;">Rp {{ number_format($spj->nilai ?? 0, 0, ',', '.') }}</td>
                        <td>
                            @if(!$spj->berkas_fisik_diterima_at)
                                <span class="badge-status-pending" style="background: rgba(245, 158, 11, 0.15); color: #d97706; border: 1px solid #fde68a;">
                                    Menunggu Fisik Disetor
                                </span>
                            @elseif(!$spj->pemeriksaan_dimulai_at)
                                <span class="badge-status-pending" style="background: rgba(2, 132, 199, 0.15); color: #0284c7; border: 1px solid #bae6fd;">
                                    Fisik Diterima (Siap Cek)
                                </span>
                            @else
                                <span class="badge-status-pending" style="background: rgba(16, 185, 129, 0.15); color: #059669; border: 1px solid #a7f3d0;">
                                    Sedang Diperiksa
                                </span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('umum.spj.show', $spj->id) }}" 
                               class="btn-action-view" 
                               title="Periksa Berkas SPJ">
                                <ion-icon name="eye-outline"></ion-icon>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div style="text-align: center; padding: 36px 20px; color: var(--dash-text-muted); font-size: 13px;">
                                <ion-icon name="checkmark-done-circle-outline" style="font-size: 40px; color: #10b981; margin-bottom: 8px; display: block; margin-left: auto; margin-right: auto;"></ion-icon>
                                Semua berkas SPJ telah selesai diperiksa! Tidak ada antrean baru.
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

