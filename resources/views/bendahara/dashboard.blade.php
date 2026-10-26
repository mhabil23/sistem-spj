@extends('layouts.bendahara')

@php
$title = 'Dashboard Bendahara';
$subtitle = 'Ringkasan antrean pencairan SPJ dan status transaksi BRI QLOLA.';
@endphp

@push('styles')
    @vite(['resources/css/bendahara/spj.css'])
@endpush

@section('content')
<div style="padding: 28px 36px; box-sizing: border-box; width: 100%;">

    <!-- HERO WELCOME BANNER -->
    <div style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 50%, #0f172a 100%); border-radius: 16px; padding: 24px 32px; color: #ffffff; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; gap: 20px; box-shadow: 0 10px 25px -5px rgba(2, 132, 199, 0.3); flex-wrap: wrap;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 6px; background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.25); padding: 4px 12px; border-radius: 999px; font-size: 11.5px; font-weight: 700; margin-bottom: 10px; letter-spacing: 0.03em;">
                <ion-icon name="shield-checkmark" style="color: #38bdf8;"></ion-icon>
                <span>PANEL BENDAHARA PENGELUARAN &bull; BPS KOLAKA UTARA</span>
            </div>
            <h1 style="font-size: 24px; font-weight: 800; margin: 0 0 6px 0; letter-spacing: -0.02em;">
                Selamat Datang, {{ auth()->user()->name ?? 'Bendahara' }}!
            </h1>
            <p style="margin: 0; font-size: 13.5px; opacity: 0.9; max-width: 620px; line-height: 1.5;">
                Pantau antrean pencairan dana SPJ, catat input maker QLOLA, dan pantau status otorisasi kode OTP dari Kuasa Pengguna Anggaran (KPA).
            </p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="{{ route('bendahara.spj.index') }}" class="bnd-btn" style="background: #ffffff; color: #0369a1; font-weight: 700; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                <ion-icon name="file-tray-full-outline" style="font-size: 18px; color: #0284c7;"></ion-icon>
                <span>Buka Kotak Masuk ({{ $totalAntrean }})</span>
            </a>
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
                    <ion-icon name="wallet-outline"></ion-icon>
                    <span>Rp {{ number_format($nilaiAntrean, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- STAT 2: MENUNGGU OTP QLOLA KPA -->
        <div class="bnd-stat-card">
            <div class="bnd-stat-icon blue">
                <ion-icon name="key-outline"></ion-icon>
            </div>
            <div class="bnd-stat-info">
                <div class="bnd-stat-label">Menunggu OTP QLOLA KPA</div>
                <div class="bnd-stat-value" style="color: #2563eb;">
                    {{ $totalMenungguOtp }} Berkas
                </div>
                <div class="bnd-stat-sub">
                    <ion-icon name="time-outline"></ion-icon>
                    <span>Rp {{ number_format($nilaiMenungguOtp, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- STAT 3: SIAP DICAIRKAN (PPSPM) -->
        <div class="bnd-stat-card">
            <div class="bnd-stat-icon amber">
                <ion-icon name="hourglass-outline"></ion-icon>
            </div>
            <div class="bnd-stat-info">
                <div class="bnd-stat-label">Siap Diproses (PPSPM)</div>
                <div class="bnd-stat-value" style="color: #d97706;">
                    {{ $siapDicairkanCount }} Berkas
                </div>
                <div class="bnd-stat-sub">
                    <ion-icon name="pricetag-outline"></ion-icon>
                    <span>Rp {{ number_format($nilaiSiapDicairkan, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- STAT 4: PEMBAYARAN SELESAI -->
        <div class="bnd-stat-card success">
            <div class="bnd-stat-icon emerald">
                <ion-icon name="checkmark-done-circle-outline"></ion-icon>
            </div>
            <div class="bnd-stat-info">
                <div class="bnd-stat-label">Pembayaran Selesai</div>
                <div class="bnd-stat-value" style="color: #059669;">
                    {{ $totalSelesai }} SPJ
                </div>
                <div class="bnd-stat-sub">
                    <ion-icon name="checkmark-circle-outline" style="color: #059669;"></ion-icon>
                    <span>Rp {{ number_format($nilaiSelesai, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 1: TABEL ANTREAN SPJ TERBARU (FULL WIDTH) -->
    <div class="bnd-table-card">
        <div class="bnd-table-toolbar">
            <div class="bnd-toolbar-left">
                <div style="width: 34px; height: 34px; border-radius: 10px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                    <ion-icon name="list-outline"></ion-icon>
                </div>
                <div>
                    <h3 class="bnd-toolbar-title" style="font-size: 15px;">Antrean SPJ Terbaru</h3>
                    <p style="font-size: 11.5px; color: #64748b; margin: 2px 0 0 0;">Daftar pengajuan SPJ siap proses dan menunggu otorisasi OTP</p>
                </div>
                <span class="bnd-toolbar-counter" style="margin-left: 6px;">{{ $antreanSpjs->count() }} Berkas Perlu Diproses</span>
            </div>
            <a href="{{ route('bendahara.spj.index') }}" class="bnd-btn" style="background: #eff6ff; color: #0284c7; border: 1px solid #bfdbfe; font-size: 12.5px; padding: 7px 14px;">
                <span>Buka Seluruh Kotak Masuk</span>
                <ion-icon name="arrow-forward-outline"></ion-icon>
            </a>
        </div>

        <div class="bnd-table-responsive">
            <table class="bnd-table">
                <thead>
                    <tr>
                        <th style="width: 140px;">Tanggal</th>
                        <th style="width: 190px;">No. SPJ / SPM</th>
                        <th>Uraian Kegiatan</th>
                        <th style="width: 140px;">Pengaju</th>
                        <th style="text-align: right; width: 140px;">Nilai Tagihan</th>
                        <th style="text-align: center; width: 160px;">Status</th>
                        <th style="text-align: center; width: 110px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($antreanSpjs as $spj)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 32px; height: 32px; border-radius: 8px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #64748b; font-size: 16px; flex-shrink: 0;">
                                    <ion-icon name="calendar-outline"></ion-icon>
                                </div>
                                <div>
                                    <div style="font-weight: 700; color: #0f172a; font-size: 12.5px;">{{ $spj->updated_at->format('d M Y') }}</div>
                                    <div style="font-size: 11px; color: #94a3b8;">{{ $spj->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <span style="display: inline-block; background: #eff6ff; color: #1d4ed8; padding: 3px 8px; border-radius: 6px; font-size: 11.5px; font-weight: 700; border: 1px solid #bfdbfe; letter-spacing: 0.01em;">
                                    {{ $spj->nomor_spj ?: '-' }}
                                </span>
                                @if($spj->nomor_spm)
                                <div style="font-size: 11px; color: #64748b; margin-top: 3px; display: flex; align-items: center; gap: 4px;">
                                    <span style="color: #94a3b8;">SPM:</span>
                                    <strong style="color: #334155;">{{ $spj->nomor_spm }}</strong>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="bnd-cell-kegiatan" title="{{ $spj->kegiatan }}">
                                <div style="font-weight: 700; color: #0f172a; font-size: 13px; line-height: 1.4;">
                                    {{ \Illuminate\Support\Str::limit($spj->kegiatan, 65) }}
                                </div>
                                @if($spj->keterangan)
                                <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                                    {{ \Illuminate\Support\Str::limit($spj->keterangan, 55) }}
                                </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="bnd-cell-user">
                                <div class="bnd-avatar" style="width: 30px; height: 30px; font-size: 11px;">
                                    {{ strtoupper(substr($spj->user->name ?? 'T', 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-size: 12.5px; font-weight: 700; color: #1e293b; line-height: 1.2;">
                                        {{ $spj->user->name ?? 'Teknis' }}
                                    </div>
                                    <div style="font-size: 10.5px; color: #94a3b8;">Staf Teknis</div>
                                </div>
                            </div>
                        </td>
                        <td style="text-align: right;">
                            <div style="font-size: 14px; font-weight: 800; color: #059669; letter-spacing: -0.01em;">
                                Rp {{ number_format($spj->nilai, 0, ',', '.') }}
                            </div>
                        </td>
                        <td style="text-align: center;">
                            @if($spj->status === 'menunggu_otp_kpa')
                                <span class="bnd-badge bnd-badge-cyan" style="font-size: 10.5px; padding: 4px 10px;">
                                    <span class="bnd-badge-dot"></span>
                                    Menunggu OTP KPA
                                </span>
                            @elseif($spj->status === 'disetujui_ppspm')
                                <span class="bnd-badge bnd-badge-amber" style="font-size: 10.5px; padding: 4px 10px;">
                                    <span class="bnd-badge-dot"></span>
                                    Siap Diproses
                                </span>
                            @else
                                <span class="bnd-badge bnd-badge-mint" style="font-size: 10.5px; padding: 4px 10px;">
                                    {{ strtoupper(str_replace('_', ' ', $spj->status)) }}
                                </span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <a href="{{ route('bendahara.spj.show', $spj->id) }}" class="bnd-btn-detail" title="Buka dan Proses Pembayaran">
                                <ion-icon name="wallet-outline"></ion-icon>
                                <span>Proses</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div style="text-align: center; padding: 48px 20px; color: #64748b;">
                                <ion-icon name="checkmark-done-circle-outline" style="font-size: 48px; color: #10b981; margin-bottom: 8px; display: block; margin-left: auto; margin-right: auto;"></ion-icon>
                                <strong style="display: block; font-size: 15px; color: #0f172a; margin-bottom: 4px;">Semua Antrean Telah Selesai Diproses</strong>
                                <p style="font-size: 12.5px; color: #94a3b8; margin: 0; max-width: 420px; margin-left: auto; margin-right: auto;">
                                    Tidak ada dokumen SPJ yang mengantre saat ini. Berkas baru yang telah disetujui PPSPM akan otomatis muncul di sini.
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bnd-table-footer">
            <div style="display: flex; align-items: center; gap: 8px;">
                <ion-icon name="information-circle-outline" style="font-size: 16px; color: #94a3b8;"></ion-icon>
                <span>Menampilkan <strong>{{ $antreanSpjs->count() }}</strong> berkas antrean terbaru</span>
            </div>
            <a href="{{ route('bendahara.spj.index') }}" style="color: #0284c7; text-decoration: none; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                <span>Buka Kotak Masuk Lengkap</span>
                <ion-icon name="arrow-forward-outline"></ion-icon>
            </a>
        </div>
    </div>

    <!-- SECTION 2: ALUR BRI QLOLA & STATUS / PINTASAN OPERASIONAL -->
    <div class="bnd-dashboard-bottom-grid">
        
        <!-- KOLOM 1: PANDUAN MEKANISME QLOLA -->
        <div class="bnd-card" style="margin-bottom: 0;">
            <div class="bnd-card-header" style="background: #f8fafc; padding: 18px 24px;">
                <div class="bnd-card-header-left">
                    <div class="bnd-card-header-icon blue">
                        <ion-icon name="git-network-outline"></ion-icon>
                    </div>
                    <div>
                        <h4 style="font-size: 14.5px; font-weight: 800; color: #0f172a; margin: 0;">Mekanisme Pembayaran Bank (BRI QLOLA)</h4>
                        <p style="font-size: 11.5px; color: #64748b; margin: 2px 0 0 0;">Standar Operasional Prosedur Pembagian Peran Maker &amp; Approver</p>
                    </div>
                </div>
                <span style="font-size: 11px; font-weight: 700; color: #0369a1; background: #e0f2fe; padding: 4px 10px; border-radius: 999px; border: 1px solid #bae6fd;">
                    SOP Perbankan
                </span>
            </div>
            
            <div class="bnd-card-body" style="padding: 22px 24px;">
                <div class="bnd-qlola-steps-wrap">
                    
                    <!-- STEP 1 -->
                    <div class="bnd-qlola-step-item">
                        <div class="bnd-qlola-step-num step-1">1</div>
                        <div class="bnd-qlola-step-content">
                            <div class="bnd-qlola-step-header">
                                <span class="bnd-qlola-step-title">Input Maker di Portal BRI QLOLA</span>
                                <span class="bnd-qlola-step-role role-bendahara">Staf Bendahara</span>
                            </div>
                            <p class="bnd-qlola-step-desc">
                                Staf bendahara menginput transfer rekening penerima di portal QLOLA BRI, lalu menandai status SPJ di sistem ini menjadi <strong>"Menunggu OTP QLOLA KPA"</strong>.
                            </p>
                        </div>
                    </div>

                    <!-- STEP 2 -->
                    <div class="bnd-qlola-step-item">
                        <div class="bnd-qlola-step-num step-2">2</div>
                        <div class="bnd-qlola-step-content">
                            <div class="bnd-qlola-step-header">
                                <span class="bnd-qlola-step-title">Otorisasi &amp; Approval OTP oleh KPA</span>
                                <span class="bnd-qlola-step-role role-kpa">Kuasa Pengguna Anggaran</span>
                            </div>
                            <p class="bnd-qlola-step-desc">
                                Kuasa Pengguna Anggaran (KPA) masuk ke menu Approver QLOLA dan mengesahkan transfer perbankan dengan memasukkan kode otorisasi OTP SMS/token bank.
                            </p>
                        </div>
                    </div>

                    <!-- STEP 3 -->
                    <div class="bnd-qlola-step-item">
                        <div class="bnd-qlola-step-num step-3">3</div>
                        <div class="bnd-qlola-step-content">
                            <div class="bnd-qlola-step-header">
                                <span class="bnd-qlola-step-title">Konfirmasi Selesai &amp; Notifikasi WA</span>
                                <span class="bnd-qlola-step-role role-success">Selesai &bull; Notifikasi</span>
                            </div>
                            <p class="bnd-qlola-step-desc">
                                Bendahara menekan tombol <strong>"Proses Pembayaran Telah Selesai"</strong>. Berkas resmi diarsipkan dan notifikasi WhatsApp otomatis dikirim ke staf pengaju.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- KOLOM 2: STATUS BERKAS REVISI & PINTASAN OPERASIONAL -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
            
            <!-- KARTU STATUS KENDALA / REVISI -->
            <div class="bnd-card" style="margin-bottom: 0; border: 1px solid {{ $totalRevisi > 0 ? '#fecdd3' : '#e2e8f0' }}; background: {{ $totalRevisi > 0 ? '#fff1f2' : '#ffffff' }};">
                <div style="padding: 20px 22px; display: flex; align-items: center; justify-content: space-between; gap: 14px;">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div style="width: 42px; height: 42px; border-radius: 12px; background: {{ $totalRevisi > 0 ? '#ffe4e6' : '#ecfdf5' }}; color: {{ $totalRevisi > 0 ? '#e11d48' : '#059669' }}; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">
                            <ion-icon name="{{ $totalRevisi > 0 ? 'alert-circle' : 'checkmark-circle' }}"></ion-icon>
                        </div>
                        <div>
                            <h4 style="font-size: 14px; font-weight: 800; color: #0f172a; margin: 0;">
                                {{ $totalRevisi > 0 ? 'Berkas Dikembalikan (Revisi)' : 'Status Pengajuan Lancar' }}
                            </h4>
                            <p style="font-size: 12px; color: {{ $totalRevisi > 0 ? '#be123c' : '#64748b' }}; margin: 3px 0 0 0;">
                                {{ $totalRevisi > 0 ? $totalRevisi . ' berkas membutuhkan perbaikan staf teknis' : 'Tidak ada berkas yang dikembalikan saat ini' }}
                            </p>
                        </div>
                    </div>
                    @if($totalRevisi > 0)
                    <a href="{{ route('bendahara.riwayat.index') }}" class="bnd-btn" style="background: #e11d48; color: #ffffff; padding: 7px 14px; font-size: 12px;">
                        <span>Periksa</span>
                        <ion-icon name="arrow-forward-outline"></ion-icon>
                    </a>
                    @else
                    <span style="font-size: 11px; font-weight: 700; color: #059669; background: #dcfce7; padding: 4px 10px; border-radius: 999px; border: 1px solid #86efac;">
                        ✓ Normal
                    </span>
                    @endif
                </div>
            </div>

            <!-- KARTU PINTASAN OPERASIONAL -->
            <div class="bnd-card" style="margin-bottom: 0; flex: 1;">
                <div class="bnd-card-header" style="background: #f8fafc; padding: 16px 22px;">
                    <div class="bnd-card-header-left">
                        <div class="bnd-card-header-icon blue" style="width: 32px; height: 32px; font-size: 16px;">
                            <ion-icon name="flash-outline"></ion-icon>
                        </div>
                        <div>
                            <h4 style="font-size: 14px; font-weight: 800; color: #0f172a; margin: 0;">Akses Cepat &amp; Dokumen SOP</h4>
                            <p style="font-size: 11px; color: #64748b; margin: 2px 0 0 0;">Pintasan modul pencairan dan regulasi SPJ</p>
                        </div>
                    </div>
                </div>
                <div class="bnd-card-body" style="padding: 16px 22px;">
                    <div class="bnd-quick-links-list">
                        
                        <a href="{{ route('bendahara.spj.index') }}" class="bnd-quick-link-item">
                            <div class="bnd-quick-link-left">
                                <div class="bnd-quick-link-icon" style="background: #e0f2fe; color: #0284c7;">
                                    <ion-icon name="file-tray-full-outline"></ion-icon>
                                </div>
                                <div>
                                    <div class="bnd-quick-link-title">Kotak Masuk Pencairan Dana</div>
                                    <div class="bnd-quick-link-sub">{{ $totalAntrean }} berkas menunggu eksekusi</div>
                                </div>
                            </div>
                            <ion-icon name="chevron-forward-outline" style="color: #94a3b8; font-size: 16px;"></ion-icon>
                        </a>

                        <a href="{{ route('bendahara.riwayat.index') }}" class="bnd-quick-link-item">
                            <div class="bnd-quick-link-left">
                                <div class="bnd-quick-link-icon" style="background: #ede9fe; color: #7c3aed;">
                                    <ion-icon name="time-outline"></ion-icon>
                                </div>
                                <div>
                                    <div class="bnd-quick-link-title">Arsip Riwayat &amp; Ekspor Rekap</div>
                                    <div class="bnd-quick-link-sub">{{ $totalSelesai }} transaksi berhasil dibayarkan</div>
                                </div>
                            </div>
                            <ion-icon name="chevron-forward-outline" style="color: #94a3b8; font-size: 16px;"></ion-icon>
                        </a>

                        <a href="{{ route('flowchart.preview') }}" target="_blank" class="bnd-quick-link-item">
                            <div class="bnd-quick-link-left">
                                <div class="bnd-quick-link-icon" style="background: #dcfce7; color: #059669;">
                                    <ion-icon name="document-attach-outline"></ion-icon>
                                </div>
                                <div>
                                    <div class="bnd-quick-link-title">Bagan Alur SOP SPJ BPS (PDF)</div>
                                    <div class="bnd-quick-link-sub">Panduan resmi alur penyelesaian SPJ</div>
                                </div>
                            </div>
                            <ion-icon name="open-outline" style="color: #94a3b8; font-size: 16px;"></ion-icon>
                        </a>

                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
