@extends('layouts.bendahara')

@php
$title = 'Detail Pencairan SPJ - Bendahara';
@endphp

@push('styles')
    @vite(['resources/css/bendahara/spj.css'])
@endpush

@section('content')
<div class="bnd-header-wrapper">
    <!-- BREADCRUMB -->
    <div class="bnd-breadcrumb">
        <a href="{{ route('bendahara.dashboard') }}">
            <ion-icon name="home-outline"></ion-icon> Dashboard
        </a>
        <ion-icon name="chevron-forward-outline"></ion-icon>
        <a href="{{ route('bendahara.spj.index') }}">Kotak Masuk SPJ</a>
        <ion-icon name="chevron-forward-outline"></ion-icon>
        <span>Detail Pencairan</span>
    </div>

    <!-- MAIN PAGE HEADER -->
    <div class="bnd-page-header">
        <div class="bnd-header-title-box">
            <h1>
                <span>Pencairan SPJ: {{ Str::limit($spj->kegiatan, 45) }}</span>
                @php
                    $badgeClass = 'bnd-badge-cyan';
                    $badgeText = str_replace('_', ' ', strtoupper($spj->status));
                    if ($spj->status === 'menunggu_otp_kpa') {
                        $badgeClass = 'bnd-badge-cyan';
                        $badgeText = 'Menunggu OTP QLOLA KPA';
                    } elseif ($spj->status === 'proses_ls') {
                        $badgeClass = 'bnd-badge-amber';
                        $badgeText = $spj->status_label;
                    } elseif ($spj->status === 'selesai') {
                        $badgeClass = 'bnd-badge-mint';
                        $badgeText = 'Pembayaran Selesai';
                    } elseif ($spj->status === 'disetujui_ppspm') {
                        $badgeClass = 'bnd-badge-blue';
                        $badgeText = 'Siap Diproses Bendahara (SPM Terbit)';
                    } elseif (str_contains($spj->status, 'revisi') || $spj->status === 'dikembalikan') {
                        $badgeClass = 'bnd-badge-rose';
                        $badgeText = 'Perlu Revisi';
                    }
                @endphp
                <span class="bnd-badge {{ $badgeClass }}">
                    <span class="bnd-badge-dot"></span>
                    {{ $badgeText }}
                </span>
            </h1>
            <div class="bnd-header-meta">
                <div class="bnd-header-meta-item">
                    <ion-icon name="calendar-outline"></ion-icon>
                    <span>Diajukan: <strong>{{ $spj->created_at->format('d M Y, H:i') }}</strong></span>
                </div>
                <div class="bnd-header-meta-item">
                    <ion-icon name="person-outline"></ion-icon>
                    <span>Pengaju: <strong>{{ $spj->user->name ?? 'Staf Teknis' }}</strong></span>
                </div>
                <div class="bnd-header-meta-item">
                    <ion-icon name="document-text-outline"></ion-icon>
                    <span>No. SPJ: <strong style="color: #0284c7;">{{ $spj->nomor_spj }}</strong></span>
                </div>
            </div>
        </div>

        <div class="bnd-header-actions">
            <a href="{{ route('bendahara.berkas.show', $spj->id) }}" class="bnd-btn" style="background: #0284c7; color: #ffffff;">
                <ion-icon name="checkbox-outline" style="font-size: 17px;"></ion-icon>
                <span>Halaman Verifikasi Berkas</span>
            </a>
            <button type="button" onclick="openWaModal()" class="bnd-btn bnd-btn-wa">
                <ion-icon name="logo-whatsapp" style="font-size: 17px;"></ion-icon>
                <span>Kirim WhatsApp</span>
            </button>
            <a href="{{ route('bendahara.spj.index') }}" class="bnd-btn bnd-btn-back">
                <ion-icon name="arrow-back-outline"></ion-icon>
                <span>Kembali</span>
            </a>
        </div>
    </div>
</div>

{{-- TRIGGER WA NOTIFICATION AFTER ACTION --}}
@if(session('trigger_wa'))
<div class="bnd-alert-banner wa">
    <div class="bnd-alert-content">
        <div class="bnd-alert-icon">
            <ion-icon name="logo-whatsapp"></ion-icon>
        </div>
        <div>
            <h4 class="bnd-alert-title">Langkah Selanjutnya: Beritahu Pengaju via WhatsApp</h4>
            <p class="bnd-alert-text">Status SPJ berhasil diperbarui. Kirimkan pesan konfirmasi WhatsApp ke staf pengaju agar langsung mengetahui status pencairan atau kendala.</p>
        </div>
    </div>
    <button type="button" onclick="openWaModal('{{ session('trigger_wa') }}')" class="bnd-btn bnd-btn-wa">
        <ion-icon name="logo-whatsapp" style="font-size: 18px;"></ion-icon>
        <span>Buka Pesan WhatsApp</span>
    </button>
</div>
@endif

{{-- BANNER MENUNGGU OTP KPA --}}
@if($spj->status === 'menunggu_otp_kpa')
<div class="bnd-alert-banner otp">
    <div class="bnd-alert-content">
        <div class="bnd-alert-icon">
            <ion-icon name="key-outline"></ion-icon>
        </div>
        <div>
            <h4 class="bnd-alert-title">Status Saat Ini: Menunggu OTP QLOLA KPA</h4>
            <p class="bnd-alert-text">
                Transaksi pembayaran telah berhasil diinput ke portal perbankan BRI QLOLA. Setelah Kuasa Pengguna Anggaran (KPA) memasukkan kode OTP dan transaksi tereksekusi, silakan klik tombol <strong>"Proses Pembayaran Telah Selesai, Silahkan Cek Rekening"</strong> di bawah.
            </p>
        </div>
    </div>
    <button type="button" onclick="openWaModal('otp_kpa')" class="bnd-btn" style="background: #2563eb; color: #ffffff; box-shadow: 0 4px 12px rgba(37,99,235,0.25);">
        <ion-icon name="notifications-outline" style="font-size: 16px;"></ion-icon>
        <span>Ingatkan KPA / Teknis</span>
    </button>
</div>
@endif

{{-- BANNER PROSES PEMBAYARAN LS BANK --}}
@if($spj->status === 'proses_ls')
<div class="bnd-alert-banner otp" style="background: #fff7ed; border-color: #fed7aa; color: #9a3412;">
    <div class="bnd-alert-content">
        <div class="bnd-alert-icon" style="background: #ea580c; color: #ffffff;">
            <ion-icon name="business-outline"></ion-icon>
        </div>
        <div>
            <h4 class="bnd-alert-title" style="color: #9a3412;">Status Saat Ini: {{ $spj->status_label }}</h4>
            <p class="bnd-alert-text" style="color: #7c2d12;">
                {{ $spj->keterangan_ls }}
                Setelah transfer bank masuk ke rekening penerima, silakan klik tombol <strong>"Konfirmasi Selesai / Telah Dicairkan"</strong> di bawah.
            </p>
        </div>
    </div>
    <button type="button" onclick="openWaModal('bayar_ls')" class="bnd-btn" style="background: #ea580c; color: #ffffff; box-shadow: 0 4px 12px rgba(234, 88, 12, 0.25);">
        <ion-icon name="logo-whatsapp" style="font-size: 16px;"></ion-icon>
        <span>Info Status LS via WA</span>
    </button>
</div>
@endif

<!-- KEY FINANCIAL STATS GRID -->
<div class="bnd-stats-grid">
    <!-- STAT 1: NILAI PENCAIRAN -->
    <div class="bnd-stat-card success">
        <div class="bnd-stat-icon emerald">
            <ion-icon name="wallet-outline"></ion-icon>
        </div>
        <div class="bnd-stat-info">
            <div class="bnd-stat-label">Nilai Tagihan SPJ</div>
            <div class="bnd-stat-value" style="color: #059669;">
                Rp {{ number_format($spj->nilai, 0, ',', '.') }}
            </div>
            <div class="bnd-stat-sub">
                <ion-icon name="checkmark-circle-outline" style="color: #059669;"></ion-icon>
                <span>Total nominal pencairan dana</span>
            </div>
        </div>
    </div>

    <!-- STAT 2: NOMOR DOKUMEN -->
    <div class="bnd-stat-card primary">
        <div class="bnd-stat-icon blue">
            <ion-icon name="receipt-outline"></ion-icon>
        </div>
        <div class="bnd-stat-info">
            <div class="bnd-stat-label">Nomor SPJ / SPM</div>
            <div class="bnd-stat-value" style="font-size: 15px;" title="{{ $spj->nomor_spj ?: '-' }}">
                {{ $spj->nomor_spj ?: '-' }}
            </div>
            <div class="bnd-stat-sub">
                <ion-icon name="pricetag-outline"></ion-icon>
                <span>No. SPM: <strong>{{ $spj->nomor_spm ?: '-' }}</strong></span>
            </div>
        </div>
    </div>

    <!-- STAT 3: PIC / PENGAJU -->
    <div class="bnd-stat-card">
        <div class="bnd-stat-icon violet">
            <ion-icon name="person-circle-outline"></ion-icon>
        </div>
        <div class="bnd-stat-info">
            <div class="bnd-stat-label">Pelaksana / PIC</div>
            <div class="bnd-stat-value" style="font-size: 15px;">
                {{ $spj->user->name ?? 'Staf Teknis' }}
            </div>
            <div class="bnd-stat-sub">
                @if($spj->no_wa)
                    <ion-icon name="logo-whatsapp" style="color: #25d366;"></ion-icon>
                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $spj->no_wa)) }}" target="_blank" style="color: #0284c7; text-decoration: none; font-weight: 600;">
                        {{ $spj->no_wa }}
                    </a>
                @else
                    <span>Nomor WA belum dicantumkan</span>
                @endif
            </div>
        </div>
    </div>

    <!-- STAT 4: TANGGAL KEGIATAN -->
    <div class="bnd-stat-card">
        <div class="bnd-stat-icon amber">
            <ion-icon name="calendar-outline"></ion-icon>
        </div>
        <div class="bnd-stat-info">
            <div class="bnd-stat-label">Tanggal Pelaksanaan</div>
            <div class="bnd-stat-value" style="font-size: 15px;">
                {{ $spj->tanggal->format('d F Y') }}
            </div>
            <div class="bnd-stat-sub">
                <ion-icon name="time-outline"></ion-icon>
                <span>Kegiatan BPS Kolaka Utara</span>
            </div>
        </div>
    </div>
</div>

<!-- DETAIL KEGIATAN & BERKAS DOKUMEN -->
<div class="bnd-card">
    <div class="bnd-card-header">
        <div class="bnd-card-header-left">
            <div class="bnd-card-header-icon blue">
                <ion-icon name="document-text-outline"></ion-icon>
            </div>
            <div>
                <h3 class="bnd-card-header-title">Detail Kegiatan &amp; Berkas Lampiran</h3>
                <p class="bnd-card-header-desc">Uraian kegiatan pertanggungjawaban dan kelengkapan dokumen pengajuan.</p>
            </div>
        </div>
    </div>
    <div class="bnd-card-body">
        <!-- URAIAN KEGIATAN -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 12px; font-weight: 700; color: var(--bnd-slate-500); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                Uraian Kegiatan
            </label>
            <div style="background: var(--bnd-slate-50); border: 1px solid var(--bnd-slate-200); border-radius: 10px; padding: 14px 18px; font-size: 14px; font-weight: 600; color: var(--bnd-slate-900); line-height: 1.6;">
                {{ $spj->kegiatan }}
            </div>
        </div>

        @if($spj->keterangan)
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 12px; font-weight: 700; color: var(--bnd-slate-500); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                Catatan Teknis / Keterangan Pengaju
            </label>
            <div style="background: var(--bnd-slate-50); border: 1px solid var(--bnd-slate-200); border-radius: 10px; padding: 12px 16px; font-size: 13px; color: var(--bnd-slate-600); line-height: 1.6; white-space: pre-line;">
                {{ $spj->keterangan }}
            </div>
        </div>
        @endif

        @if($spj->bukti_transfer)
        <div style="margin-bottom: 20px; background: #ecfdf5; border: 1px solid #86efac; border-radius: 10px; padding: 12px 18px; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <ion-icon name="checkmark-circle" style="color: #059669; font-size: 22px;"></ion-icon>
                <div>
                    <span style="font-size: 12px; font-weight: 600; color: #065f46; display: block;">Referensi Bukti Transfer / Resi QLOLA:</span>
                    <strong style="font-size: 14px; color: #047857;">{{ $spj->bukti_transfer }}</strong>
                </div>
            </div>
            <span style="font-size: 12px; background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 6px; font-weight: 700;">
                Telah Dicairkan
            </span>
        </div>
        @endif

        <!-- KELENGKAPAN BERKAS & CHECKLIST VERIFIKASI (BENDAHARA) -->
        <div style="margin-top: 10px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 10px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 800; color: var(--bnd-slate-900); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">
                        Pemeriksaan Kelengkapan Berkas Fisik &amp; Syarat Perpajakan
                    </label>
                    <span style="font-size: 12.5px; color: var(--bnd-slate-500);">
                        Berkas yang dicentang di awal oleh Teknis dan berkas khusus yang harus dicentang oleh Bendahara.
                    </span>
                </div>
                <a href="{{ route('bendahara.berkas.show', $spj->id) }}" class="bnd-btn" style="background: #0284c7; color: #ffffff; padding: 6px 14px; font-size: 12px;">
                    <ion-icon name="open-outline"></ion-icon>
                    <span>Buka Halaman Verifikasi Berkas Penuh</span>
                </a>
            </div>

            <!-- GRID 2 KOLOM: BERKAS AWAL TEKNIS & BERKAS BENDAHARA -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 20px;">
                
                <!-- KOLOM 1: BERKAS DICENTANG DI AWAL (TEKNIS) -->
                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 18px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <ion-icon name="checkmark-circle" style="color: #16a34a; font-size: 20px;"></ion-icon>
                            <h4 style="margin: 0; font-size: 13.5px; font-weight: 700; color: #14532d;">Dicentang di Awal (Staf Teknis)</h4>
                        </div>
                        <span style="font-size: 11px; font-weight: 700; background: #dcfce7; color: #15803d; padding: 2px 8px; border-radius: 999px;">
                            {{ count(array_filter($berkasTeknis ?? [], fn($d) => !empty($d['disiapkan']))) }}/{{ count($berkasTeknis ?? []) }} Siap
                        </span>
                    </div>

                    @if(!empty($berkasTeknis) && count($berkasTeknis) > 0)
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            @foreach($berkasTeknis as $d)
                                <div style="display: flex; align-items: center; justify-content: space-between; background: #ffffff; border: 1px solid #dcfce7; border-radius: 8px; padding: 8px 12px; font-size: 12.5px;">
                                    <div style="display: flex; align-items: center; gap: 8px; color: #14532d;">
                                        <ion-icon name="checkmark-circle" style="color: #16a34a; font-size: 16px;"></ion-icon>
                                        <span style="font-weight: 600;">{{ $d['nama'] }}</span>
                                    </div>
                                    <span style="font-size: 11px; color: #16a34a; font-weight: 700;">✔ Ada</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p style="font-size: 12.5px; color: #166534; font-style: italic; margin: 0;">Tidak ada checklist berkas teknis khusus.</p>
                    @endif
                </div>

                <!-- KOLOM 2: BERKAS BAGIAN BENDAHARA (DAPAT DICENTANG) -->
                <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 18px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <ion-icon name="checkbox-outline" style="color: #2563eb; font-size: 20px;"></ion-icon>
                            <h4 style="margin: 0; font-size: 13.5px; font-weight: 700; color: #1e3a8a;">Bagian Bendahara (Pajak &amp; Bayar)</h4>
                        </div>
                        <span style="font-size: 11px; font-weight: 700; background: #dbeafe; color: #1d4ed8; padding: 2px 8px; border-radius: 999px;">
                            {{ count(array_filter($berkasBendahara ?? [], fn($d) => !empty($d['disiapkan']))) }}/{{ count($berkasBendahara ?? []) }} Dicentang
                        </span>
                    </div>

                    <form action="{{ route('bendahara.spj.checklist', $spj->id) }}" method="POST">
                        @csrf
                        @if(!empty($berkasBendahara) && count($berkasBendahara) > 0)
                            <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 12px;">
                                @foreach($berkasBendahara as $idx => $d)
                                    @php
                                        $isReady = !empty($d['disiapkan']);
                                        $inputKey = $d['index'];
                                        $isVirtual = !empty($d['is_virtual']);
                                    @endphp
                                    <label style="display: flex; align-items: center; justify-content: space-between; background: #ffffff; border: 1px solid {{ $isReady ? '#93c5fd' : '#cbd5e1' }}; border-radius: 8px; padding: 8px 12px; font-size: 12.5px; cursor: pointer;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <input type="checkbox" name="berkas_bendahara[{{ $inputKey }}]" value="1" style="width: 17px; height: 17px; accent-color: #0284c7; cursor: pointer;" {{ $isReady ? 'checked' : '' }}>
                                            <span style="font-weight: 600; color: #0f172a;">{{ $d['nama'] }}</span>
                                        </div>
                                        @if($isVirtual)
                                            <input type="hidden" name="berkas_virtual[{{ $loop->index }}]" value="{{ $d['nama'] }}">
                                        @endif
                                        <span style="font-size: 11px; color: {{ $isReady ? '#2563eb' : '#d97706' }}; font-weight: 700;">
                                            {{ $isReady ? '✔ Dicentang' : 'Belum' }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>

                            <button type="submit" class="bnd-btn" style="width: 100%; justify-content: center; background: #0284c7; color: #ffffff; padding: 7px 12px; font-size: 12px; font-weight: 700;">
                                <ion-icon name="save-outline"></ion-icon> Simpan Centang Berkas Bendahara
                            </button>
                        @else
                            <p style="font-size: 12.5px; color: #1e40af; font-style: italic; margin: 0;">Tidak ada berkas khusus Bendahara.</p>
                        @endif
                    </form>
                </div>

            </div>

            @if($spj->dokumen_file && !is_array(json_decode($spj->dokumen_file, true)))
                <!-- LAMPIRAN FILE UPLOAD LAMA JIKA ADA -->
                <div class="bnd-doc-item" style="margin-top: 14px;">
                    <div class="bnd-doc-left">
                        <div class="bnd-doc-icon">
                            <ion-icon name="document-attach-outline"></ion-icon>
                        </div>
                        <div>
                            <h4 class="bnd-doc-title">Lampiran File Digital</h4>
                            <div class="bnd-doc-meta">
                                <span>File bukti digital terunggah</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . $spj->dokumen_file) }}" target="_blank" class="bnd-btn" style="background: #2563eb; color: #ffffff;">
                        <ion-icon name="open-outline"></ion-icon>
                        <span>Lihat Dokumen Asli</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- PANEL EKSEKUSI PENCAIRAN DANA (BENDAHARA) -->
@if(in_array($spj->status, ['disetujui_ppspm', 'menunggu_otp_kpa', 'proses_ls']))
<div class="bnd-card" style="border: 2px solid #cbd5e1; box-shadow: var(--bnd-shadow-lg);">
    <div class="bnd-card-header" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
        <div class="bnd-card-header-left">
            <div class="bnd-card-header-icon emerald">
                <ion-icon name="cash-outline"></ion-icon>
            </div>
            <div>
                <h3 class="bnd-card-header-title">Eksekusi Pembayaran &amp; Pencairan Dana (Bendahara)</h3>
                <p class="bnd-card-header-desc">
                    Pilih metode pembayaran (CMS BRI QLOLA / Pembayaran LS Bank) sesuai ketentuan SPJ.
                </p>
            </div>
        </div>
    </div>
    <div class="bnd-card-body">
        
        {{-- KONDISI 1: STATUS DISETUJUI PPSPM (PILIH METODE PEMBAYARAN) --}}
        @if($spj->status === 'disetujui_ppspm')
        <form action="{{ route('bendahara.spj.verify', $spj->id) }}" method="POST">
            @csrf

            <!-- 2 PILIHAN METODE UTAMA -->
            <div class="bnd-execution-grid" style="grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                
                <!-- PILIHAN 1: MENUNGGU OTP QLOLA KPA -->
                <div class="bnd-choice-card card-otp" style="border: 2px solid #93c5fd; background: #ffffff;">
                    <div class="bnd-choice-ribbon" style="background: #2563eb; color: #ffffff;">Metode QLOLA BRI</div>
                    <div>
                        <div class="bnd-choice-head">
                            <div class="bnd-choice-icon" style="background: #eff6ff; color: #2563eb;">
                                <ion-icon name="key-outline"></ion-icon>
                            </div>
                            <h3 class="bnd-choice-title">Transaksi CMS BRI QLOLA</h3>
                        </div>
                        <p class="bnd-choice-desc">
                            Pilih opsi ini jika transaksi telah diinput ke portal <strong>BRI QLOLA</strong> oleh Bendahara dan saat ini menunggu otorisasi OTP dari <strong>Kuasa Pengguna Anggaran (KPA)</strong>.
                        </p>

                        <label class="bnd-choice-input-label">Catatan / Referensi QLOLA Maker (Opsional):</label>
                        <input type="text" name="keterangan_otp" class="bnd-choice-input" placeholder="Contoh: Batch Maker #{{ date('Ymd') }}-01 Menunggu KPA">
                    </div>

                    <div class="bnd-choice-actions" style="margin-top: 18px;">
                        <button type="submit" name="action" value="otp_kpa" class="bnd-btn-submit-otp" style="width: 100%; justify-content: center;">
                            <ion-icon name="key-outline" style="font-size: 18px;"></ion-icon>
                            <span>Set Status: Menunggu OTP QLOLA KPA</span>
                        </button>
                    </div>
                </div>

                <!-- PILIHAN 2: PEMBAYARAN LANGSUNG (LS BANK) -->
                <div class="bnd-choice-card" style="border: 2px solid #fed7aa; background: #ffffff;">
                    <div class="bnd-choice-ribbon" style="background: #ea580c; color: #ffffff;">Metode LS KPPN / Bank</div>
                    <div>
                        <div class="bnd-choice-head">
                            <div class="bnd-choice-icon" style="background: #fff7ed; color: #ea580c;">
                                <ion-icon name="business-outline"></ion-icon>
                            </div>
                            <h3 class="bnd-choice-title">Pembayaran Langsung (LS)</h3>
                        </div>
                        
                        @if($spj->isHonor())
                            <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 10px 14px; margin-bottom: 12px; font-size: 12.5px; color: #92400e; line-height: 1.5;">
                                <strong>Kategori Honorarium:</strong> Status akan diset <em>"Menunggu Kliring Bank LS (Maks. 3 Hari Kerja)"</em> karena menunggu kliring transfer antar-bank.
                            </div>
                        @else
                            <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 10px 14px; margin-bottom: 12px; font-size: 12.5px; color: #1e40af; line-height: 1.5;">
                                <strong>Kategori Non-Honor:</strong> Status akan diset <em>"Menunggu Transfer Bank Langsung (LS)"</em> menunggu SP2D dan transfer bank ke rekening rekanan.
                            </div>
                        @endif

                        <label class="bnd-choice-input-label">Nomor SP2D / Catatan LS Bank (Opsional):</label>
                        <input type="text" name="nomor_sp2d_ls" class="bnd-choice-input" placeholder="Contoh: SP2D-LS-{{ date('Ymd') }}-BPS">
                    </div>

                    <div class="bnd-choice-actions" style="margin-top: 18px;">
                        <button type="submit" name="action" value="bayar_ls" class="bnd-btn" style="width: 100%; justify-content: center; background: #ea580c; color: #ffffff; padding: 12px; font-weight: 700; border-radius: 10px;">
                            <ion-icon name="paper-plane-outline" style="font-size: 18px;"></ion-icon>
                            <span>Set Status: Pembayaran LS Diajukan ke Bank</span>
                        </button>
                    </div>
                </div>

            </div>

            <!-- OPSI KEMBALIKAN / REVISI (COLLAPSIBLE) -->
            <div class="bnd-revisi-box">
                <div class="bnd-revisi-toggle" onclick="toggleRevisiBox()">
                    <div class="bnd-revisi-toggle-left">
                        <ion-icon name="alert-circle-outline"></ion-icon>
                        <span>Opsi Kendala: Kembalikan Berkas SPJ (Revisi)</span>
                    </div>
                    <div class="bnd-revisi-toggle-right">
                        <span id="revisi_toggle_text">Buka Form Revisi</span>
                        <ion-icon name="chevron-down-outline" id="revisi_toggle_icon" style="transition: transform 0.2s;"></ion-icon>
                    </div>
                </div>

                <div id="revisi_collapse" class="bnd-revisi-content" style="display: none;">
                    <p style="color: #9f1239; margin: 0 0 10px 0; font-size: 12.5px; line-height: 1.5;">
                        Pilih opsi ini jika ada kendala rekening bank tidak aktif, nama penerima berbeda dari SK penugasan, atau ketidaksesuaian administrasi lainnya:
                    </p>
                    <label style="display: block; font-weight: 700; margin-bottom: 6px; color: #9f1239; font-size: 12px;">
                        Alasan Pengembalian (Wajib diisi jika dikembalikan):
                    </label>
                    <textarea name="catatan_revisi" rows="3" class="bnd-revisi-textarea" placeholder="Tuliskan kendala pencairan dana secara detail...">{{ $spj->catatan_revisi ?? '' }}</textarea>

                    <div style="display: flex; gap: 10px; justify-content: flex-end;">
                        <button type="submit" name="action" value="tolak" class="bnd-btn" style="background: #e11d48; color: #ffffff; box-shadow: 0 4px 12px rgba(225, 29, 72, 0.25);">
                            <ion-icon name="close-circle-outline" style="font-size: 17px;"></ion-icon>
                            <span>Kembalikan SPJ ke Teknis</span>
                        </button>
                        <button type="button" onclick="openWaModalWithNote('revisi')" class="bnd-btn" style="background: #25d366; color: #ffffff; box-shadow: 0 4px 12px rgba(37, 211, 102, 0.25);">
                            <ion-icon name="logo-whatsapp" style="font-size: 17px;"></ion-icon>
                            <span>Info via WA</span>
                        </button>
                    </div>
                </div>
            </div>

        </form>

        {{-- KONDISI 2: STATUS MENUNGGU OTP QLOLA KPA (MUNCUL TOMBOL SELESAI AKHIR SETELAH OTP KPA) --}}
        @elseif($spj->status === 'menunggu_otp_kpa')
        <div style="background: #eff6ff; border: 1.5px solid #93c5fd; border-radius: 12px; padding: 22px;">
            <div style="display: flex; align-items: flex-start; gap: 14px; margin-bottom: 16px;">
                <div style="background: #2563eb; color: #ffffff; width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                    <ion-icon name="key-outline"></ion-icon>
                </div>
                <div>
                    <h3 style="margin: 0 0 4px 0; font-size: 16px; font-weight: 800; color: #1e3a8a;">
                        Tahap Terakhir QLOLA: Otorisasi OTP KPA Telah Selesai
                    </h3>
                    <p style="margin: 0; font-size: 13.5px; color: #1e40af; line-height: 1.5;">
                        Kode OTP dari KPA telah dimasukkan &amp; dana berhasil ditransfer via BRI QLOLA. Masukkan nomor bukti/resi transfer dan konfirmasi bahwa <strong>proses pembayaran telah selesai, silakan cek rekening</strong>.
                    </p>
                </div>
            </div>

            <form action="{{ route('bendahara.spj.verify', $spj->id) }}" method="POST" onsubmit="return bukaModalKonfirmasiCair(event, this, 'Transaksi CMS BRI QLOLA (OTP KPA)');">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr auto; gap: 14px; align-items: flex-end;">
                    <div>
                        <label class="bnd-choice-input-label">Bukti Transfer / No. Referensi Transaksi QLOLA (Opsional):</label>
                        <input type="text" name="bukti_transfer" class="bnd-choice-input" placeholder="Contoh: REF-QLOLA-{{ date('Ymd') }}-9283719" value="{{ $spj->bukti_transfer ?: '' }}">
                    </div>
                    <button type="submit" name="action" value="setujui" class="bnd-btn-submit-cair" style="height: 44px; padding: 0 24px;">
                        <ion-icon name="checkmark-done-circle" style="font-size: 20px;"></ion-icon>
                        <span>Konfirmasi Selesai / Telah Dicairkan</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- KONDISI 3: STATUS PROSES LS (MUNCUL TOMBOL SELESAI AKHIR SETELAH BANK TRANSFER) --}}
        @elseif($spj->status === 'proses_ls')
        <div style="background: #fff7ed; border: 1.5px solid #fed7aa; border-radius: 12px; padding: 22px;">
            <div style="display: flex; align-items: flex-start; gap: 14px; margin-bottom: 16px;">
                <div style="background: #ea580c; color: #ffffff; width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                    <ion-icon name="business-outline"></ion-icon>
                </div>
                <div>
                    <h3 style="margin: 0 0 4px 0; font-size: 16px; font-weight: 800; color: #9a3412;">
                        Tahap Terakhir LS: Konfirmasi Transfer Bank Telah Masuk Rekening
                    </h3>
                    <p style="margin: 0; font-size: 13.5px; color: #7c2d12; line-height: 1.5;">
                        {{ $spj->keterangan_ls }}
                        Setelah dana terdistribusi ke rekening penerima/rekanan, konfirmasi penyelesaian pembayaran di bawah.
                    </p>
                </div>
            </div>

            <form action="{{ route('bendahara.spj.verify', $spj->id) }}" method="POST" onsubmit="return bukaModalKonfirmasiCair(event, this, 'Pembayaran Langsung (LS Bank)');">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr auto; gap: 14px; align-items: flex-end;">
                    <div>
                        <label class="bnd-choice-input-label">Nomor SP2D / Bukti Transfer Bank LS:</label>
                        <input type="text" name="bukti_transfer" class="bnd-choice-input" placeholder="Contoh: SP2D-LS-{{ date('Ymd') }}-BPS" value="{{ $spj->bukti_transfer ?: '' }}">
                    </div>
                    <button type="submit" name="action" value="setujui" class="bnd-btn" style="background: #059669; color: #ffffff; padding: 0 24px; height: 44px; font-weight: 700; font-size: 14px; border-radius: 10px; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);">
                        <ion-icon name="checkmark-done-circle" style="font-size: 20px;"></ion-icon>
                        <span>Konfirmasi Selesai / Dana Telah Masuk Rekening</span>
                    </button>
                </div>
            </form>
        </div>
        @endif

    </div>
</div>
@endif

<!-- MODAL WHATSAPP INTERAKTIF BENDAHARA -->
<div id="waModal" class="bnd-modal-overlay" style="display: none;">
    <div class="bnd-modal-card">
        <div class="bnd-modal-head">
            <div class="bnd-modal-head-left">
                <div class="bnd-modal-head-icon">
                    <ion-icon name="logo-whatsapp"></ion-icon>
                </div>
                <div>
                    <h3 class="bnd-modal-head-title">Kirim Notifikasi WhatsApp (Bendahara)</h3>
                    <p class="bnd-modal-head-sub">Template pesan terisi otomatis sesuai status laporan SPJ ini</p>
                </div>
            </div>
            <button type="button" onclick="closeWaModal()" class="bnd-modal-close">
                <ion-icon name="close" style="font-size: 20px;"></ion-icon>
            </button>
        </div>

        <div class="bnd-modal-body">
            @php
                $recipientWa = $spj->no_wa ?: ($spj->user->no_wa ?? '');
            @endphp
            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 13px; font-weight: 700; color: var(--bnd-slate-700); margin-bottom: 6px;">
                    Nomor WhatsApp Pengaju ({{ $spj->user->name ?? 'Staf Teknis' }}):
                </label>
                <input type="text" id="waRecipient" value="{{ $recipientWa }}" placeholder="Contoh: 081234567890 (atau biarkan kosong untuk pilih kontak di WA)" style="width: 100%; box-sizing: border-box; padding: 10px 14px; border: 1px solid var(--bnd-slate-200); border-radius: 8px; font-size: 14px; outline: none; font-family: inherit; transition: var(--bnd-transition);">
                @if(empty($recipientWa))
                <small style="color: #d97706; font-size: 11px; display: flex; align-items: center; gap: 4px; margin-top: 6px;">
                    <ion-icon name="information-circle-outline"></ion-icon> Pengaju belum mencantumkan nomor WhatsApp pada SPJ ini. Anda bisa ketik nomor HP manual di atas atau langsung klik "Buka di WhatsApp".
                </small>
                @endif
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 13px; font-weight: 700; color: var(--bnd-slate-700); margin-bottom: 8px;">Pilih Template Pesan:</label>
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <button type="button" id="btnTplOtp" onclick="setWaTemplate('otp_kpa')" style="padding: 7px 16px; font-size: 12px; font-weight: 700; border-radius: 999px; border: 1px solid #0284c7; background: #f0f9ff; color: #0369a1; cursor: pointer; transition: 0.2s;">
                        🔑 Menunggu OTP QLOLA KPA
                    </button>
                    <button type="button" id="btnTplCair" onclick="setWaTemplate('cair')" style="padding: 7px 16px; font-size: 12px; font-weight: 700; border-radius: 999px; border: 1px solid var(--bnd-slate-200); background: #ffffff; color: var(--bnd-slate-600); cursor: pointer; transition: 0.2s;">
                        🎉 Pembayaran Telah Selesai (Cek Rekening)
                    </button>
                    <button type="button" id="btnTplRevisi" onclick="setWaTemplate('revisi')" style="padding: 7px 16px; font-size: 12px; font-weight: 700; border-radius: 999px; border: 1px solid var(--bnd-slate-200); background: #ffffff; color: var(--bnd-slate-600); cursor: pointer; transition: 0.2s;">
                        ⚠️ Kendala / Revisi
                    </button>
                </div>
            </div>

            <div style="margin-bottom: 22px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <label style="font-size: 13px; font-weight: 700; color: var(--bnd-slate-700);">Isi Pesan (Bisa Diedit):</label>
                    <button type="button" onclick="copyWaText()" id="btnCopyWa" style="background: none; border: none; color: #0284c7; font-size: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 4px;">
                        <ion-icon name="copy-outline"></ion-icon> <span>Salin Teks</span>
                    </button>
                </div>
                <textarea id="waMessageText" rows="9" style="width: 100%; box-sizing: border-box; padding: 12px 14px; border: 1px solid var(--bnd-slate-200); border-radius: 8px; font-size: 13px; line-height: 1.55; font-family: inherit; outline: none; background: var(--bnd-slate-50); color: var(--bnd-slate-900); resize: vertical;"></textarea>
            </div>

            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" onclick="closeWaModal()" class="bnd-btn bnd-btn-back">
                    Tutup
                </button>
                <button type="button" onclick="sendWhatsAppNow()" class="bnd-btn bnd-btn-wa">
                    <ion-icon name="logo-whatsapp" style="font-size: 18px;"></ion-icon>
                    <span>Buka di WhatsApp</span>
                </button>
            </div>
        </div>
    </div>
</div>

<x-bendahara.modal-konfirmasi-cair :spj="$spj" />

@php
    $spjJsData = [
        'pengaju' => $spj->user->name ?? 'Staf Teknis',
        'kegiatan' => $spj->kegiatan,
        'nilai' => 'Rp ' . number_format($spj->nilai, 0, ',', '.'),
        'catatanRevisi' => $spj->catatan_revisi ?? '',
        'buktiTransfer' => $spj->bukti_transfer ?? '',
        'linkDetail' => route('teknis.spj.show', $spj->id),
    ];
@endphp

@push('scripts')
<script>
    const spjData = @json($spjJsData);

    function toggleRevisiBox() {
        const box = document.getElementById('revisi_collapse');
        const icon = document.getElementById('revisi_toggle_icon');
        const text = document.getElementById('revisi_toggle_text');
        if (box.style.display === 'none') {
            box.style.display = 'block';
            icon.style.transform = 'rotate(180deg)';
            text.textContent = 'Tutup Form Revisi';
        } else {
            box.style.display = 'none';
            icon.style.transform = 'rotate(0deg)';
            text.textContent = 'Buka Form Revisi';
        }
    }

    function openWaModal(defaultTpl = 'otp_kpa') {
        document.getElementById('waModal').style.display = 'flex';
        setWaTemplate(defaultTpl);
    }

    function openWaModalWithBukti(defaultTpl = 'cair') {
        const liveBuktiInput = document.querySelector('input[name="bukti_transfer"]');
        if (liveBuktiInput && liveBuktiInput.value.trim()) {
            spjData.activeBukti = liveBuktiInput.value.trim();
        } else {
            spjData.activeBukti = '';
        }
        openWaModal(defaultTpl);
    }

    function openWaModalWithNote(defaultTpl = 'revisi') {
        const liveCatatanInput = document.querySelector('textarea[name="catatan_revisi"]');
        if (liveCatatanInput && liveCatatanInput.value.trim()) {
            spjData.activeNote = liveCatatanInput.value.trim();
        } else {
            spjData.activeNote = '';
        }
        openWaModal(defaultTpl);
    }

    function closeWaModal() {
        document.getElementById('waModal').style.display = 'none';
    }

    window.addEventListener('click', function(e) {
        const modal = document.getElementById('waModal');
        if (e.target === modal) closeWaModal();
    });

    function setWaTemplate(tpl) {
        const btnOtp = document.getElementById('btnTplOtp');
        const btnCair = document.getElementById('btnTplCair');
        const btnRevisi = document.getElementById('btnTplRevisi');
        const textarea = document.getElementById('waMessageText');

        [btnOtp, btnCair, btnRevisi].forEach(btn => {
            if (btn) {
                btn.style.background = '#ffffff';
                btn.style.color = '#475569';
                btn.style.borderColor = '#cbd5e1';
            }
        });

        const liveBuktiInput = document.querySelector('input[name="bukti_transfer"]');
        const bukti = (spjData.activeBukti) || (liveBuktiInput && liveBuktiInput.value.trim() ? liveBuktiInput.value.trim() : (spjData.buktiTransfer || 'Telah diproses transfer BRI QLOLA'));

        const liveCatatanInput = document.querySelector('textarea[name="catatan_revisi"]');
        const catatan = (spjData.activeNote) || (liveCatatanInput && liveCatatanInput.value.trim() ? liveCatatanInput.value.trim() : (spjData.catatanRevisi || 'Kendala rekening / nomor rekening perlu dikonfirmasi.'));

        let message = '';
        if (tpl === 'otp_kpa') {
            if (btnOtp) {
                btnOtp.style.background = '#f0f9ff';
                btnOtp.style.color = '#0369a1';
                btnOtp.style.borderColor = '#0284c7';
            }

            message = `*INFORMASI PROSES PEMBAYARAN SPJ (BRI QLOLA)*\n`
                    + `Bendahara Pengeluaran BPS Kab. Kolaka Utara\n\n`
                    + `Halo Sdr/i *${spjData.pengaju}*,\n\n`
                    + `Transaksi pembayaran untuk pengajuan SPJ Anda telah diinput oleh Bendahara ke sistem perbankan BRI QLOLA:\n\n`
                    + `• *Kegiatan* : ${spjData.kegiatan}\n`
                    + `• *Nilai*    : ${spjData.nilai}\n`
                    + `• *Status*   : *Menunggu OTP QLOLA KPA*\n\n`
                    + `Saat ini transaksi sedang menunggu otorisasi kode OTP dari Kuasa Pengguna Anggaran (KPA). Dana akan segera cair ke rekening setelah persetujuan KPA selesai.\n\n`
                    + `Pantau detailnya di:\n`
                    + `${spjData.linkDetail}\n\n`
                    + `Terima kasih.\n`
                    + `_Bendahara BPS Kolaka Utara_`;
        } else if (tpl === 'cair') {
            if (btnCair) {
                btnCair.style.background = '#ecfdf5';
                btnCair.style.color = '#047857';
                btnCair.style.borderColor = '#059669';
            }

            message = `*PEMBERITAHUAN PENCAIRAN DANA SPJ*\n`
                    + `Bendahara Pengeluaran BPS Kab. Kolaka Utara\n\n`
                    + `Halo Sdr/i *${spjData.pengaju}*,\n\n`
                    + `Kabar baik! *Proses pembayaran telah selesai, silahkan cek rekening* Anda masing-masing:\n\n`
                    + `• *Kegiatan*  : ${spjData.kegiatan}\n`
                    + `• *Nilai*     : ${spjData.nilai}\n`
                    + `• *Ref/Bukti* : ${bukti}\n\n`
                    + `Silakan periksa mutasi rekening penerima. Detail SPJ:\n`
                    + `${spjData.linkDetail}\n\n`
                    + `Terima kasih.\n`
                    + `_Bendahara BPS Kolaka Utara_`;
        } else {
            if (btnRevisi) {
                btnRevisi.style.background = '#fff1f2';
                btnRevisi.style.color = '#be123c';
                btnRevisi.style.borderColor = '#e11d48';
            }

            message = `*KENDALA PENCAIRAN DANA SPJ*\n`
                    + `Bendahara Pengeluaran BPS Kab. Kolaka Utara\n\n`
                    + `Halo Sdr/i *${spjData.pengaju}*,\n\n`
                    + `Terdapat kendala dalam proses pencairan SPJ Anda:\n\n`
                    + `• *Kegiatan* : ${spjData.kegiatan}\n`
                    + `• *Kendala / Catatan*:\n"${catatan}"\n\n`
                    + `Mohon segera konfirmasi ke Bendahara Pengeluaran.\n\n`
                    + `Terima kasih.\n`
                    + `_Bendahara BPS Kolaka Utara_`;
        }
        textarea.value = message;
    }

    function copyWaText() {
        const textarea = document.getElementById('waMessageText');
        textarea.select();
        navigator.clipboard.writeText(textarea.value).then(() => {
            const btnCopy = document.getElementById('btnCopyWa');
            const span = btnCopy.querySelector('span');
            span.textContent = 'Tersalin! ✔';
            btnCopy.style.color = '#059669';
            setTimeout(() => {
                span.textContent = 'Salin Teks';
                btnCopy.style.color = '#0284c7';
            }, 2000);
        });
    }

    function sendWhatsAppNow() {
        let phone = document.getElementById('waRecipient').value.trim();
        const msg = document.getElementById('waMessageText').value.trim();
        if (!msg) return alert('Pesan tidak boleh kosong!');

        phone = phone.replace(/[^0-9]/g, '');
        if (phone.startsWith('0')) phone = '62' + phone.substring(1);
        else if (phone.startsWith('8')) phone = '62' + phone;

        let url = 'https://api.whatsapp.com/send?text=' + encodeURIComponent(msg);
        if (phone) url = 'https://api.whatsapp.com/send?phone=' + phone + '&text=' + encodeURIComponent(msg);
        window.open(url, '_blank');
    }
</script>

@if(session('trigger_wa'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            openWaModal('{{ session('trigger_wa') }}');
        }, 350);
    });
</script>
@endif
@endpush
@endsection
