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

    <!-- MAIN TWO COLUMN GRID -->
    <div style="display: grid; grid-template-columns: 2.3fr 1fr; gap: 24px; align-items: start;">
        
        <!-- KOLOM KIRI: ANTREAN SPJ TERBARU -->
        <div class="bnd-table-card" style="margin-bottom: 0;">
            <div class="bnd-table-toolbar">
                <div class="bnd-toolbar-left">
                    <h3 class="bnd-toolbar-title">Antrean SPJ Terbaru</h3>
                    <span class="bnd-toolbar-counter">{{ $antreanSpjs->count() }} Berkas Perlu Diproses</span>
                </div>
                <a href="{{ route('bendahara.spj.index') }}" style="font-size: 13px; font-weight: 700; color: #0284c7; text-decoration: none; display: flex; align-items: center; gap: 4px;">
                    <span>Lihat Semua Antrean</span>
                    <ion-icon name="arrow-forward-outline"></ion-icon>
                </a>
            </div>

            <div style="overflow-x: auto;">
                <table class="bnd-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>No. SPJ / SPM</th>
                            <th>Uraian Kegiatan</th>
                            <th>Pengaju</th>
                            <th style="text-align: right;">Nilai Tagihan (Rp)</th>
                            <th style="text-align: center;">Status</th>
                            <th style="text-align: center; width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($antreanSpjs as $spj)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <ion-icon name="calendar-outline" style="color: #94a3b8; font-size: 14px;"></ion-icon>
                                    <div>
                                        <div style="font-weight: 700; color: #1e293b; font-size: 12.5px;">{{ $spj->updated_at->format('d M Y') }}</div>
                                        <div style="font-size: 11px; color: #94a3b8;">{{ $spj->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <span style="display: inline-block; background: #eff6ff; color: #1d4ed8; padding: 2px 7px; border-radius: 5px; font-size: 11px; font-weight: 700; border: 1px solid #bfdbfe;">
                                        {{ $spj->nomor_spj ?: '-' }}
                                    </span>
                                    @if($spj->nomor_spm)
                                    <div style="font-size: 10.5px; color: #64748b; margin-top: 2px;">
                                        SPM: <strong>{{ $spj->nomor_spm }}</strong>
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td style="max-width: 240px;">
                                <div style="font-weight: 700; color: #0f172a; line-height: 1.35; font-size: 13px;" title="{{ $spj->kegiatan }}">
                                    {{ \Illuminate\Support\Str::limit($spj->kegiatan, 38) }}
                                </div>
                            </td>
                            <td>
                                <div class="bnd-cell-user">
                                    <div class="bnd-avatar" style="width: 28px; height: 28px; font-size: 11px;">
                                        {{ strtoupper(substr($spj->user->name ?? 'T', 0, 1)) }}
                                    </div>
                                    <div style="font-size: 12.5px; font-weight: 600; color: #334155;">
                                        {{ $spj->user->name ?? 'Teknis' }}
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: right;">
                                <div style="font-size: 13.5px; font-weight: 800; color: #059669;">
                                    Rp {{ number_format($spj->nilai, 0, ',', '.') }}
                                </div>
                            </td>
                            <td style="text-align: center;">
                                @if($spj->status === 'menunggu_otp_kpa')
                                    <span class="bnd-badge bnd-badge-cyan" style="font-size: 10px; padding: 4px 10px;">
                                        <span class="bnd-badge-dot"></span>
                                        Menunggu OTP KPA
                                    </span>
                                @elseif($spj->status === 'disetujui_ppspm')
                                    <span class="bnd-badge bnd-badge-amber" style="font-size: 10px; padding: 4px 10px;">
                                        <span class="bnd-badge-dot"></span>
                                        Siap Dicairkan
                                    </span>
                                @else
                                    <span class="bnd-badge bnd-badge-mint" style="font-size: 10px; padding: 4px 10px;">
                                        {{ strtoupper(str_replace('_', ' ', $spj->status)) }}
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ route('bendahara.spj.show', $spj->id) }}" class="bnd-btn-action-view" style="padding: 6px 12px; font-size: 11.5px;" title="Proses Pembayaran">
                                    <ion-icon name="wallet-outline"></ion-icon>
                                    <span>Proses</span>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div style="text-align: center; padding: 48px 20px; color: #64748b;">
                                    <ion-icon name="checkmark-done-circle-outline" style="font-size: 44px; color: #10b981; margin-bottom: 8px; display: block; margin-left: auto; margin-right: auto;"></ion-icon>
                                    <strong style="display: block; font-size: 14px; color: #0f172a;">Semua Antrean Sudah Selesai</strong>
                                    <p style="font-size: 12.5px; color: #94a3b8; margin: 0;">Tidak ada dokumen SPJ yang mengantre saat ini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bnd-table-footer">
                <div>
                    Menampilkan <strong>{{ $antreanSpjs->count() }}</strong> antrean terbaru
                </div>
                <a href="{{ route('bendahara.spj.index') }}" style="color: #0284c7; text-decoration: none; font-weight: 700;">
                    Buka Kotak Masuk Lengkap &rarr;
                </a>
            </div>
        </div>

        <!-- KOLOM KANAN: PANDUAN BRI QLOLA & SHORTCUTS -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
            
            <!-- KARTU PANDUAN ALUR QLOLA -->
            <div class="bnd-card" style="margin-bottom: 0;">
                <div class="bnd-card-header" style="background: #f8fafc; padding: 16px 20px;">
                    <div class="bnd-card-header-left">
                        <div class="bnd-card-header-icon blue" style="width: 32px; height: 32px; font-size: 16px;">
                            <ion-icon name="git-network-outline"></ion-icon>
                        </div>
                        <div>
                            <h4 style="font-size: 14px; font-weight: 700; color: #0f172a; margin: 0;">Alur Pembayaran BRI QLOLA</h4>
                            <p style="font-size: 11px; color: #64748b; margin: 2px 0 0 0;">Mekanisme Maker &amp; Approver</p>
                        </div>
                    </div>
                </div>
                <div class="bnd-card-body" style="padding: 18px 20px;">
                    
                    <div style="display: flex; gap: 12px; margin-bottom: 16px;">
                        <div style="width: 24px; height: 24px; border-radius: 50%; background: #e0f2fe; color: #0369a1; font-size: 11px; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">1</div>
                        <div>
                            <strong style="display: block; font-size: 12.5px; color: #0f172a;">Input Maker di QLOLA</strong>
                            <p style="font-size: 11.5px; color: #64748b; margin: 2px 0 0 0; line-height: 1.4;">
                                Staf bendahara menginput transfer di portal QLOLA, lalu klik tombol <em>"Menunggu OTP QLOLA KPA"</em>.
                            </p>
                        </div>
                    </div>

                    <div style="display: flex; gap: 12px; margin-bottom: 16px;">
                        <div style="width: 24px; height: 24px; border-radius: 50%; background: #dbeafe; color: #1d4ed8; font-size: 11px; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">2</div>
                        <div>
                            <strong style="display: block; font-size: 12.5px; color: #0f172a;">Otorisasi OTP oleh KPA</strong>
                            <p style="font-size: 11.5px; color: #64748b; margin: 2px 0 0 0; line-height: 1.4;">
                                KPA memasukkan kode OTP pada menu Approver untuk mengesahkan transaksi bank.
                            </p>
                        </div>
                    </div>

                    <div style="display: flex; gap: 12px;">
                        <div style="width: 24px; height: 24px; border-radius: 50%; background: #dcfce7; color: #15803d; font-size: 11px; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">3</div>
                        <div>
                            <strong style="display: block; font-size: 12.5px; color: #0f172a;">Konfirmasi &amp; Cek Rekening</strong>
                            <p style="font-size: 11.5px; color: #64748b; margin: 2px 0 0 0; line-height: 1.4;">
                                Bendahara klik <em>"Proses Pembayaran Selesai, Silahkan Cek Rekening"</em> &amp; info WA otomatis terkirim.
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- KARTU STATUS KENDALA / REVISI -->
            <div class="bnd-card" style="margin-bottom: 0; border: 1px solid {{ $totalRevisi > 0 ? '#fecdd3' : '#e2e8f0' }}; background: {{ $totalRevisi > 0 ? '#fff1f2' : '#ffffff' }};">
                <div style="padding: 18px 20px; display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 36px; height: 36px; border-radius: 10px; background: {{ $totalRevisi > 0 ? '#ffe4e6' : '#f1f5f9' }}; color: {{ $totalRevisi > 0 ? '#e11d48' : '#64748b' }}; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                            <ion-icon name="alert-circle-outline"></ion-icon>
                        </div>
                        <div>
                            <h4 style="font-size: 13.5px; font-weight: 700; color: #0f172a; margin: 0;">Berkas Dikembalikan</h4>
                            <p style="font-size: 11.5px; color: #64748b; margin: 2px 0 0 0;">{{ $totalRevisi }} berkas dalam revisi teknis</p>
                        </div>
                    </div>
                    <a href="{{ route('bendahara.riwayat.index') }}" style="font-size: 12px; font-weight: 700; color: #e11d48; text-decoration: none;">
                        Periksa &rarr;
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
