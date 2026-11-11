@extends('layouts.bendahara')

@php
$title = 'Verifikasi Berkas SPJ #' . $spj->id . ' - Bendahara';
@endphp

@push('styles')
    @vite(['resources/css/bendahara/spj.css'])
    <style>
        .check-card {
            background: #ffffff;
            border: 1.5px solid var(--bnd-slate-200);
            border-radius: 12px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            transition: all 0.2s ease;
        }

        .check-card:hover {
            border-color: #0284c7;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.08);
        }

        .check-card.checked {
            background: #f0fdf4;
            border-color: #86efac;
        }

        .check-card.bendahara-target {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        .check-card.bendahara-target:hover {
            background: #ffffff;
            border-color: #0284c7;
        }

        .check-card.bendahara-checked {
            background: #eff6ff;
            border-color: #93c5fd;
        }

        .custom-checkbox-label {
            display: flex;
            align-items: center;
            gap: 14px;
            cursor: pointer;
            width: 100%;
        }

        .custom-checkbox-input {
            width: 20px;
            height: 20px;
            accent-color: #0284c7;
            cursor: pointer;
            flex-shrink: 0;
        }

        .check-badge-ready {
            background: #dcfce7;
            color: #15803d;
            font-size: 11.5px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }

        .check-badge-pending {
            background: #fef3c7;
            color: #b45309;
            font-size: 11.5px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }
    </style>
@endpush

@section('content')
<div class="bnd-header-wrapper">
    <!-- BREADCRUMB -->
    <div class="bnd-breadcrumb">
        <a href="{{ route('bendahara.dashboard') }}">
            <ion-icon name="home-outline"></ion-icon> Dashboard
        </a>
        <ion-icon name="chevron-forward-outline"></ion-icon>
        <a href="{{ route('bendahara.berkas.index') }}">Verifikasi Berkas</a>
        <ion-icon name="chevron-forward-outline"></ion-icon>
        <span>Detail Pemeriksaan Berkas #{{ $spj->id }}</span>
    </div>

    <!-- MAIN PAGE HEADER -->
    <div class="bnd-page-header">
        <div class="bnd-header-title-box">
            <h1>
                <span>Verifikasi Berkas: {{ Str::limit($spj->kegiatan, 40) }}</span>
                @php
                    $badgeClass = 'bnd-badge-cyan';
                    if ($spj->status === 'disetujui_ppspm') $badgeClass = 'bnd-badge-blue';
                    elseif ($spj->status === 'menunggu_otp_kpa') $badgeClass = 'bnd-badge-cyan';
                    elseif ($spj->status === 'proses_ls') $badgeClass = 'bnd-badge-amber';
                    elseif ($spj->status === 'selesai') $badgeClass = 'bnd-badge-mint';
                    elseif (str_contains($spj->status, 'revisi')) $badgeClass = 'bnd-badge-rose';
                @endphp
                <span class="bnd-badge {{ $badgeClass }}">
                    <span class="bnd-badge-dot"></span>
                    {{ $spj->status_label }}
                </span>
            </h1>
            <div class="bnd-header-meta">
                <div class="bnd-header-meta-item">
                    <ion-icon name="calendar-outline"></ion-icon>
                    <span>Diajukan: <strong>{{ ($spj->diajukan_at ?? $spj->created_at)->format('d M Y, H:i') }} WITA</strong></span>
                </div>
                <div class="bnd-header-meta-item">
                    <ion-icon name="person-outline"></ion-icon>
                    <span>Pengaju: <strong>{{ $spj->user->name ?? 'Staf Teknis' }}</strong></span>
                </div>
                <div class="bnd-header-meta-item">
                    <ion-icon name="document-text-outline"></ion-icon>
                    <span>No. SPJ: <strong style="color: #0284c7;">{{ $spj->nomor_spj }}</strong></span>
                </div>
                @if($spj->nomor_spm)
                <div class="bnd-header-meta-item">
                    <ion-icon name="pricetag-outline"></ion-icon>
                    <span>No. SPM: <strong>{{ $spj->nomor_spm }}</strong></span>
                </div>
                @endif
            </div>
        </div>

        <div class="bnd-header-actions">
            <a href="{{ route('bendahara.spj.show', $spj->id) }}" class="bnd-btn" style="background: #2563eb; color: #ffffff; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);">
                <ion-icon name="cash-outline" style="font-size: 17px;"></ion-icon>
                <span>Halaman Eksekusi Pembayaran</span>
            </a>
            <a href="{{ route('bendahara.berkas.index') }}" class="bnd-btn bnd-btn-back">
                <ion-icon name="arrow-back-outline"></ion-icon>
                <span>Kembali</span>
            </a>
        </div>
    </div>
</div>

<!-- KEY FINANCIAL STATS GRID -->
<div class="bnd-stats-grid" style="margin-bottom: 24px;">
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
                <span>{{ $spj->isHonor() ? 'Kategori: Belanja Honorarium' : 'Kategori: Belanja Barang/Jasa' }}</span>
            </div>
        </div>
    </div>

    <!-- STAT 2: STATUS BERKAS TEKNIS (AWAL) -->
    <div class="bnd-stat-card primary">
        <div class="bnd-stat-icon blue">
            <ion-icon name="checkmark-done-circle-outline"></ion-icon>
        </div>
        <div class="bnd-stat-info">
            <div class="bnd-stat-label">Berkas Teknis (Di Awal)</div>
            <div class="bnd-stat-value" style="color: #0284c7;">
                {{ $teknisSiap }}/{{ $teknisTotal }} Berkas
            </div>
            <div class="bnd-stat-sub">
                <span style="color: #0284c7;">✔ Telah dicentang di awal oleh Teknis</span>
            </div>
        </div>
    </div>

    <!-- STAT 3: STATUS BERKAS BENDAHARA -->
    <div class="bnd-stat-card">
        <div class="bnd-stat-icon amber">
            <ion-icon name="receipt-outline"></ion-icon>
        </div>
        <div class="bnd-stat-info">
            <div class="bnd-stat-label">Berkas Pajak &amp; Bendahara</div>
            <div class="bnd-stat-value" style="color: {{ $bendaharaSiap >= $bendaharaTotal ? '#059669' : '#d97706' }};">
                {{ $bendaharaSiap }}/{{ $bendaharaTotal }} Dicentang
            </div>
            <div class="bnd-stat-sub">
                @if($bendaharaSiap >= $bendaharaTotal && $bendaharaTotal > 0)
                    <span style="color: #059669;">✔ Seluruh syarat Bendahara lengkap</span>
                @else
                    <span style="color: #d97706;">⏳ Centang berkas perpajakan di bawah</span>
                @endif
            </div>
        </div>
    </div>

    <!-- STAT 4: METODE & STATUS PEMBAYARAN -->
    <div class="bnd-stat-card">
        <div class="bnd-stat-icon violet">
            <ion-icon name="card-outline"></ion-icon>
        </div>
        <div class="bnd-stat-info">
            <div class="bnd-stat-label">Metode Pembayaran</div>
            <div class="bnd-stat-value" style="font-size: 15px; color: #7c3aed;">
                @if($spj->status === 'menunggu_otp_kpa')
                    CMS BRI QLOLA
                @elseif($spj->status === 'proses_ls')
                    Pembayaran LS (Bank)
                @elseif($spj->status === 'selesai')
                    Selesai Dicairkan
                @else
                    Pilih QLOLA / LS
                @endif
            </div>
            <div class="bnd-stat-sub">
                <span>{{ $spj->status_label }}</span>
            </div>
        </div>
    </div>
</div>

<!-- ====================================================================
     BERKAS DIGITAL TERLAMPIR JIKA DIUNGGAH
     ==================================================================== -->
@if($spj->file_lampiran_url)
<div class="bnd-card" style="margin-bottom: 24px; border: 1px solid #bfdbfe; background: #f8fafc;">
    <div style="padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
        <div style="display: flex; align-items: center; gap: 14px;">
            <div style="width: 44px; height: 44px; border-radius: 10px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                <ion-icon name="document-attach-outline"></ion-icon>
            </div>
            <div>
                <h4 style="margin: 0 0 2px 0; font-size: 14.5px; font-weight: 700; color: var(--bnd-slate-900);">Lampiran Berkas Bukti Digital (Scan / Foto)</h4>
                <span style="font-size: 12.5px; color: var(--bnd-slate-500);">{{ basename($spj->file_lampiran ?: $spj->dokumen_file) }} &bull; File digital pendukung yang diunggah staf pengaju</span>
            </div>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ $spj->file_lampiran_url }}" target="_blank" class="bnd-btn" style="background: #0284c7; color: #ffffff;">
                <ion-icon name="eye-outline"></ion-icon>
                <span>Buka Berkas Digital</span>
            </a>
        </div>
    </div>
</div>
@endif

<!-- ====================================================================
     BAGIAN 1: BERKAS YANG SUDAH DICENTANG DI AWAL (STAF TEKNIS)
     ==================================================================== -->
<div class="bnd-card" style="margin-bottom: 24px;">
    <div class="bnd-card-header" style="background: #f0fdf4; border-bottom: 1px solid #bbf7d0;">
        <div class="bnd-card-header-left">
            <div class="bnd-card-header-icon emerald" style="background: #dcfce7; color: #15803d;">
                <ion-icon name="checkmark-done-circle"></ion-icon>
            </div>
            <div>
                <h3 class="bnd-card-header-title" style="color: #14532d;">
                    1. Berkas yang Sudah Dicentang di Awal (Staf Teknis / Pengaju)
                </h3>
                <p class="bnd-card-header-desc" style="color: #166534;">
                    Daftar dokumen fisik yang telah disiapkan dan dicentang oleh staf teknis pada saat pengajuan SPJ.
                </p>
            </div>
        </div>
        <span class="check-badge-ready" style="padding: 6px 14px; font-size: 13px;">
            <ion-icon name="shield-checkmark"></ion-icon>
            <span>{{ $teknisSiap }}/{{ $teknisTotal }} Berkas Terverifikasi Fisik</span>
        </span>
    </div>

    <div class="bnd-card-body">
        @if(count($berkasTeknis) > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 14px;">
                @foreach($berkasTeknis as $doc)
                    @php $isReady = !empty($doc['disiapkan']); @endphp
                    <div class="check-card {{ $isReady ? 'checked' : '' }}">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="font-size: 22px; color: {{ $isReady ? '#16a34a' : '#94a3b8' }}; display: flex; align-items: center;">
                                @if($isReady)
                                    <ion-icon name="checkmark-circle"></ion-icon>
                                @else
                                    <ion-icon name="remove-circle-outline"></ion-icon>
                                @endif
                            </div>
                            <div>
                                <h4 style="margin: 0 0 2px 0; font-size: 13.5px; font-weight: 700; color: var(--bnd-slate-900);">
                                    {{ $doc['nama'] }}
                                </h4>
                                <span style="font-size: 11.5px; color: var(--bnd-slate-500);">
                                    {{ $isReady ? 'Dicentang oleh Staf Teknis di awal' : 'Belum disiapkan' }}
                                </span>
                            </div>
                        </div>

                        <span class="check-badge-ready">
                            ✔ Ada (Fisik)
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <p style="color: var(--bnd-slate-400); font-style: italic; margin: 0; font-size: 13.5px;">
                Tidak ada daftar berkas khusus staf teknis yang terdata pada SPJ ini.
            </p>
        @endif
    </div>
</div>

<!-- ====================================================================
     BAGIAN 2: BERKAS & SYARAT KHUSUS BAGIAN BENDAHARA (PAJAK & PEMBAYARAN)
     ==================================================================== -->
<div class="bnd-card" style="margin-bottom: 24px; border: 2px solid #93c5fd; box-shadow: var(--bnd-shadow-lg);">
    <div class="bnd-card-header" style="background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 100%); border-bottom: 1px solid #bfdbfe;">
        <div class="bnd-card-header-left">
            <div class="bnd-card-header-icon blue" style="background: #dbeafe; color: #1d4ed8;">
                <ion-icon name="checkbox-outline"></ion-icon>
            </div>
            <div>
                <h3 class="bnd-card-header-title" style="color: #1e3a8a;">
                    2. Berkas &amp; Syarat Khusus Bagian Bendahara
                </h3>
                <p class="bnd-card-header-desc" style="color: #1e40af;">
                    Centang berkas perpajakan (PPh/PPN/SSP/Faktur) dan bukti pembayaran yang menjadi kewenangan Bendahara untuk SPJ ini.
                </p>
            </div>
        </div>
        <div style="display: flex; gap: 8px; align-items: center;">
            <button type="button" onclick="centangSemuaBendahara(true)" class="bnd-btn" style="background: #ffffff; color: #0284c7; border: 1px solid #bae6fd; padding: 6px 12px; font-size: 12px;">
                <ion-icon name="checkmark-done-outline"></ion-icon> Centang Semua
            </button>
            <button type="button" onclick="centangSemuaBendahara(false)" class="bnd-btn" style="background: #ffffff; color: #64748b; border: 1px solid #e2e8f0; padding: 6px 12px; font-size: 12px;">
                Hapus Centang
            </button>
        </div>
    </div>

    <div class="bnd-card-body">
        <form action="{{ route('bendahara.berkas.update', $spj->id) }}" method="POST" id="formChecklistBendahara">
            @csrf

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 14px; margin-bottom: 20px;">
                @foreach($berkasBendahara as $idx => $doc)
                    @php
                        $isReady = !empty($doc['disiapkan']);
                        $isVirtual = !empty($doc['is_virtual']);
                        $inputKey = $doc['index'];
                    @endphp
                    <div class="check-card {{ $isReady ? 'bendahara-checked' : 'bendahara-target' }}" id="card_bendahara_{{ $loop->index }}">
                        <label class="custom-checkbox-label">
                            <input type="checkbox" 
                                   name="berkas_bendahara[{{ $inputKey }}]" 
                                   value="1" 
                                   class="custom-checkbox-input bendahara-checkbox"
                                   id="cb_bendahara_{{ $loop->index }}"
                                   onchange="updateCardState({{ $loop->index }})"
                                   {{ $isReady ? 'checked' : '' }}>
                            
                            @if($isVirtual)
                                <input type="hidden" name="berkas_virtual[{{ $loop->index }}]" value="{{ $doc['nama'] }}">
                            @endif

                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <h4 style="margin: 0; font-size: 13.5px; font-weight: 700; color: var(--bnd-slate-900);">
                                        {{ $doc['nama'] }}
                                    </h4>
                                    <span style="font-size: 10px; background: #e0f2fe; color: #0369a1; padding: 2px 6px; border-radius: 4px; font-weight: 700;">
                                        Bendahara
                                    </span>
                                </div>
                                <span style="font-size: 11.5px; color: var(--bnd-slate-500); display: block; margin-top: 3px;" id="desc_bendahara_{{ $loop->index }}">
                                    @if($isReady)
                                        <span style="color: #2563eb; font-weight: 600;">✔ Telah dicentang oleh Bendahara</span>
                                        @if(!empty($doc['checked_at']))
                                            ({{ \Carbon\Carbon::parse($doc['checked_at'])->format('d/m/Y H:i') }})
                                        @endif
                                    @else
                                        <span style="color: #d97706;">Klik kotak untuk mencentang berkas ini</span>
                                    @endif
                                </span>
                            </div>
                        </label>
                    </div>
                @endforeach
            </div>

            <!-- CATATAN INTERNAL BENDAHARA (OPSIONAL) -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: var(--bnd-slate-600); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                    Catatan Pemeriksaan Berkas Bendahara (Opsional)
                </label>
                <input type="text" name="catatan_internal" class="bnd-choice-input" placeholder="Contoh: Bukti potong PPh 21 sudah terbit, NTPN telah dikonfirmasi valid..." value="{{ old('catatan_internal') }}">
            </div>

            <!-- TOMBOL SIMPAN CENTANG -->
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; padding-top: 14px; border-top: 1px solid var(--bnd-slate-200);">
                <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--bnd-slate-600);">
                    <ion-icon name="information-circle-outline" style="font-size: 18px; color: #0284c7;"></ion-icon>
                    <span>Perubahan centang akan tersimpan langsung ke data kelengkapan SPJ.</span>
                </div>
                <button type="submit" class="bnd-btn" style="background: #0284c7; color: #ffffff; padding: 10px 22px; font-weight: 700; font-size: 13.5px; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);">
                    <ion-icon name="save-outline" style="font-size: 18px;"></ion-icon>
                    <span>Simpan Centang Berkas Bendahara</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ====================================================================
     BAGIAN 3: ALUR TINDAKAN LANJUTAN PEMBAYARAN (BENDAHARA)
     ==================================================================== -->
<div class="bnd-card" style="border: 2px solid #cbd5e1; box-shadow: var(--bnd-shadow-lg);">
    <div class="bnd-card-header" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
        <div class="bnd-card-header-left">
            <div class="bnd-card-header-icon emerald">
                <ion-icon name="cash-outline"></ion-icon>
            </div>
            <div>
                <h3 class="bnd-card-header-title">3. Tindakan Lanjutan Pembayaran &amp; Pencairan Dana</h3>
                <p class="bnd-card-header-desc">
                    Setelah memeriksa dan mencentang berkas, pilih metode pembayaran sesuai alur perbankan:
                </p>
            </div>
        </div>
    </div>

    <div class="bnd-card-body">
        {{-- KONDISI 1: STATUS DISETUJUI PPSPM (PILIH METODE PEMBAYARAN) --}}
        @if($spj->status === 'disetujui_ppspm')
        @php
            $isComplete = $spj->areBendaharaObligationsCompleted();
        @endphp

        <!-- BANNER PERINGATAN KEWAJIBAN BENDAHARA -->
        <div id="kewajibanWarningBannerBerkas" class="bnd-kewajiban-alert" style="{{ $isComplete ? 'display: none;' : 'display: flex;' }} background: #fffbeb; border: 2px solid #f59e0b; border-radius: 12px; padding: 16px 20px; margin-bottom: 22px; align-items: flex-start; gap: 14px;">
            <div style="background: #fef3c7; color: #d97706; width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                <ion-icon name="lock-closed"></ion-icon>
            </div>
            <div style="flex: 1;">
                <h4 style="margin: 0 0 4px 0; font-size: 15px; font-weight: 800; color: #92400e;">
                    Kewajiban Berkas Fisik &amp; Perpajakan Bendahara Belum Lengkap (<span id="warn_ready_count_berkas">{{ $bendaharaSiap }}</span>/{{ $bendaharaTotal }})
                </h4>
                <p style="margin: 0; font-size: 13px; color: #b45309; line-height: 1.5;">
                    Tombol <strong>Menunggu OTP QLOLA KPA</strong> dan <strong>Pembayaran LS</strong> tidak dapat ditekan sebelum Anda mencentang seluruh kewajiban berkas fisik &amp; perpajakan Bendahara pada checklist di atas.
                </p>
            </div>
        </div>

        <div id="kewajibanSuccessBannerBerkas" class="bnd-kewajiban-alert" style="{{ $isComplete ? 'display: flex;' : 'display: none;' }} background: #ecfdf5; border: 1.5px solid #6ee7b7; border-radius: 12px; padding: 14px 18px; margin-bottom: 22px; align-items: center; gap: 12px;">
            <ion-icon name="checkmark-circle" style="color: #059669; font-size: 24px; flex-shrink: 0;"></ion-icon>
            <div style="font-size: 13.5px; color: #065f46; font-weight: 600;">
                Seluruh kewajiban berkas fisik &amp; perpajakan Bendahara telah lengkap dicentang (<span id="succ_ready_count_berkas">{{ $bendaharaSiap }}</span>/{{ $bendaharaTotal }}). Silakan pilih metode pembayaran di bawah.
            </div>
        </div>

        <form action="{{ route('bendahara.spj.verify', $spj->id) }}" method="POST">
            @csrf

            <!-- Sertakan state checkbox bendahara agar tersimpan saat submit pembayaran -->
            @foreach($berkasBendahara as $idx => $doc)
                <input type="hidden" name="berkas_bendahara[{{ $doc['index'] }}]" value="{{ !empty($doc['disiapkan']) ? '1' : '0' }}" class="hidden-cb-mirror-{{ $loop->index }}">
                @if(!empty($doc['is_virtual']))
                    <input type="hidden" name="berkas_virtual[{{ $loop->index }}]" value="{{ $doc['nama'] }}">
                @endif
            @endforeach

            <div class="bnd-execution-grid" style="gap: 20px; margin-bottom: 24px;">
                
                <!-- PILIHAN A: MENUNGGU OTP QLOLA KPA -->
                <div class="bnd-choice-card card-otp" id="card_choice_otp_berkas" style="border: 2px solid {{ $isComplete ? '#93c5fd' : '#cbd5e1' }}; background: #ffffff; {{ ! $isComplete ? 'opacity: 0.85;' : '' }}">
                    <div class="bnd-choice-ribbon" id="ribbon_otp_berkas" style="background: {{ $isComplete ? '#2563eb' : '#94a3b8' }}; color: #ffffff;">Metode QLOLA BRI</div>
                    <div>
                        <div class="bnd-choice-head">
                            <div class="bnd-choice-icon" id="icon_choice_otp_berkas" style="background: {{ $isComplete ? '#eff6ff' : '#f1f5f9' }}; color: {{ $isComplete ? '#2563eb' : '#94a3b8' }};">
                                <ion-icon name="key-outline"></ion-icon>
                            </div>
                            <h3 class="bnd-choice-title">Transaksi CMS BRI QLOLA</h3>
                        </div>
                        <p class="bnd-choice-desc">
                            Pilih opsi ini jika pembayaran diinput ke portal <strong>BRI QLOLA</strong> oleh Bendahara dan menunggu otorisasi OTP dari <strong>Kuasa Pengguna Anggaran (KPA)</strong>.
                        </p>

                        <label class="bnd-choice-input-label">Catatan / Referensi Maker QLOLA (Opsional):</label>
                        <input type="text" name="keterangan_otp" id="input_keterangan_otp_berkas" class="bnd-choice-input" placeholder="Contoh: Batch QLOLA #{{ date('Ymd') }}-01 Menunggu KPA" {{ ! $isComplete ? 'disabled' : '' }}>
                    </div>

                    <div class="bnd-choice-actions" style="margin-top: 18px;">
                        <button type="submit" name="action" value="otp_kpa" id="btnSubmitOtpBerkas" class="bnd-btn-submit-otp" style="width: 100%; justify-content: center; {{ ! $isComplete ? 'opacity: 0.55; cursor: not-allowed; pointer-events: none; background: #94a3b8; border-color: #94a3b8;' : '' }}" {{ ! $isComplete ? 'disabled' : '' }}>
                            <ion-icon name="{{ $isComplete ? 'key-outline' : 'lock-closed-outline' }}" id="btnSubmitOtpIconBerkas" style="font-size: 18px;"></ion-icon>
                            <span id="btnSubmitOtpTextBerkas">{{ $isComplete ? 'Set Status: Menunggu OTP QLOLA KPA' : 'Terkunci: Menunggu OTP QLOLA KPA' }}</span>
                        </button>
                    </div>
                </div>

                <!-- PILIHAN B: PEMBAYARAN LANGSUNG (LS BANK) -->
                <div class="bnd-choice-card" id="card_choice_ls_berkas" style="border: 2px solid {{ $isComplete ? '#fed7aa' : '#cbd5e1' }}; background: #ffffff; {{ ! $isComplete ? 'opacity: 0.85;' : '' }}">
                    <div class="bnd-choice-ribbon" id="ribbon_ls_berkas" style="background: {{ $isComplete ? '#ea580c' : '#94a3b8' }}; color: #ffffff;">Metode LS KPPN / Bank</div>
                    <div>
                        <div class="bnd-choice-head">
                            <div class="bnd-choice-icon" id="icon_choice_ls_berkas" style="background: {{ $isComplete ? '#fff7ed' : '#f1f5f9' }}; color: {{ $isComplete ? '#ea580c' : '#94a3b8' }};">
                                <ion-icon name="business-outline"></ion-icon>
                            </div>
                            <h3 class="bnd-choice-title">Pembayaran Langsung (LS)</h3>
                        </div>
                        
                        @if($spj->isHonor())
                            <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 10px 14px; margin-bottom: 12px; font-size: 12.5px; color: #92400e; line-height: 1.5;">
                                <strong>Kategori Honorarium:</strong> Status akan diset <em>"Menunggu Kliring Bank LS (Maks. 3 Hari Kerja)"</em> karena menunggu proses kliring transfer antar-bank.
                            </div>
                        @else
                            <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 10px 14px; margin-bottom: 12px; font-size: 12.5px; color: #1e40af; line-height: 1.5;">
                                <strong>Kategori Non-Honor:</strong> Status akan diset <em>"Menunggu Transfer Bank Langsung (LS)"</em> menunggu penerbitan SP2D dan transfer bank ke rekening rekanan.
                            </div>
                        @endif

                        <label class="bnd-choice-input-label">Nomor SP2D / Catatan LS Bank (Opsional):</label>
                        <input type="text" name="nomor_sp2d_ls" id="input_nomor_sp2d_ls_berkas" class="bnd-choice-input" placeholder="Contoh: SP2D-LS-{{ date('Ymd') }}-BPS" {{ ! $isComplete ? 'disabled' : '' }}>
                    </div>

                    <div class="bnd-choice-actions" style="margin-top: 18px;">
                        <button type="submit" name="action" value="bayar_ls" id="btnSubmitLsBerkas" class="bnd-btn" style="width: 100%; justify-content: center; background: {{ $isComplete ? '#ea580c' : '#94a3b8' }}; color: #ffffff; padding: 12px; font-weight: 700; border-radius: 10px; {{ ! $isComplete ? 'opacity: 0.55; cursor: not-allowed; pointer-events: none;' : '' }}" {{ ! $isComplete ? 'disabled' : '' }}>
                            <ion-icon name="{{ $isComplete ? 'paper-plane-outline' : 'lock-closed-outline' }}" id="btnSubmitLsIconBerkas" style="font-size: 18px;"></ion-icon>
                            <span id="btnSubmitLsTextBerkas">{{ $isComplete ? 'Set Status: Pembayaran LS Diajukan ke Bank' : 'Terkunci: Pembayaran LS Diajukan ke Bank' }}</span>
                        </button>
                    </div>
                </div>

            </div>

            <!-- OPSI KEMBALIKAN (REVISI BENDAHARA) -->
            <div style="background: #fff1f2; border: 1px solid #fecdd3; border-radius: 12px; padding: 16px 20px;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                    <ion-icon name="alert-circle" style="color: #e11d48; font-size: 20px;"></ion-icon>
                    <h4 style="margin: 0; font-size: 14px; font-weight: 700; color: #9f1239;">Perlu Perbaikan / Berkas Belum Sesuai?</h4>
                </div>
                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    <input type="text" name="catatan_revisi" class="bnd-choice-input" style="flex: 1; min-width: 260px;" placeholder="Tuliskan catatan kekurangan berkas atau pajak untuk staf teknis...">
                    <button type="submit" name="action" value="tolak" class="bnd-btn" style="background: #e11d48; color: #ffffff; padding: 10px 18px; font-weight: 700;">
                        <ion-icon name="arrow-undo-outline"></ion-icon> Kembalikan ke Teknis (Revisi)
                    </button>
                </div>
            </div>
        </form>

        {{-- KONDISI 2: STATUS MENUNGGU OTP QLOLA KPA (MUNCUL TOMBOL SELESAI AKHIR) --}}
        @elseif($spj->status === 'menunggu_otp_kpa')
        <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 10px;">
                <ion-icon name="key" style="font-size: 26px; color: #2563eb;"></ion-icon>
                <div>
                    <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: #1e40af;">Tahap Terakhir: Konfirmasi OTP QLOLA &amp; Selesaikan Pembayaran</h4>
                    <p style="margin: 2px 0 0 0; font-size: 13px; color: #1e3a8a;">
                        Transaksi telah diinput ke portal BRI QLOLA. Jika KPA telah memasukkan kode OTP dan transfer sukses, klik tombol di bawah untuk menyelesaikan pencairan.
                    </p>
                </div>
            </div>

            <form action="{{ route('bendahara.spj.verify', $spj->id) }}" method="POST" onsubmit="return bukaModalKonfirmasiCair(event, this, 'Transaksi CMS BRI QLOLA (OTP KPA)');" style="margin-top: 16px;">
                @csrf
                <div class="bnd-action-submit-row">
                    <div>
                        <label class="bnd-choice-input-label">Nomor Resi / Referensi Transaksi QLOLA (Opsional):</label>
                        <input type="text" name="bukti_transfer" class="bnd-choice-input" placeholder="Contoh: REF-QLOLA-{{ date('Ymd') }}-9827" value="{{ $spj->bukti_transfer ?: '' }}">
                    </div>
                    <button type="submit" name="action" value="setujui" class="bnd-btn" style="background: #059669; color: #ffffff; padding: 12px 24px; font-weight: 700; font-size: 14px; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25); height: 44px;">
                        <ion-icon name="checkmark-done-circle" style="font-size: 20px;"></ion-icon>
                        <span>Konfirmasi Selesai / Telah Dicairkan</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- KONDISI 3: STATUS PROSES LS (MUNCUL TOMBOL SELESAI AKHIR SETELAH BANK TRANSFER) --}}
        @elseif($spj->status === 'proses_ls')
        <div style="background: #fff7ed; border: 1px solid #fed7aa; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 10px;">
                <ion-icon name="business" style="font-size: 26px; color: #ea580c;"></ion-icon>
                <div>
                    <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: #9a3412;">
                        Tahap Terakhir: Konfirmasi Transfer Bank LS &amp; Selesaikan Pencairan
                    </h4>
                    <p style="margin: 2px 0 0 0; font-size: 13px; color: #7c2d12;">
                        {{ $spj->keterangan_ls }}
                    </p>
                </div>
            </div>

            <form action="{{ route('bendahara.spj.verify', $spj->id) }}" method="POST" onsubmit="return bukaModalKonfirmasiCair(event, this, 'Pembayaran Langsung (LS Bank)');" style="margin-top: 16px;">
                @csrf
                <div class="bnd-action-submit-row">
                    <div>
                        <label class="bnd-choice-input-label">Nomor SP2D / Bukti Transfer Bank LS:</label>
                        <input type="text" name="bukti_transfer" class="bnd-choice-input" placeholder="Contoh: SP2D-LS-{{ date('Ymd') }} atau No. Referensi Bank" value="{{ $spj->bukti_transfer ?: '' }}">
                    </div>
                    <button type="submit" name="action" value="setujui" class="bnd-btn" style="background: #059669; color: #ffffff; padding: 12px 24px; font-weight: 700; font-size: 14px; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25); height: 44px;">
                        <ion-icon name="checkmark-done-circle" style="font-size: 20px;"></ion-icon>
                        <span>Konfirmasi Selesai / Dana Telah Masuk Rekening</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- KONDISI 4: STATUS SELESAI --}}
        @elseif($spj->status === 'selesai')
        <div style="background: #ecfdf5; border: 1px solid #86efac; border-radius: 12px; padding: 20px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <ion-icon name="checkmark-circle" style="font-size: 32px; color: #059669;"></ion-icon>
                <div>
                    <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: #065f46;">Proses Pembayaran Telah Selesai</h4>
                    <p style="margin: 2px 0 0 0; font-size: 13px; color: #047857;">
                        Dana telah dicairkan dan seluruh verifikasi berkas telah diselesaikan.
                        @if($spj->bukti_transfer)
                            &bull; Bukti / No. Ref: <strong>{{ $spj->bukti_transfer }}</strong>
                        @endif
                    </p>
                </div>
            </div>
            <a href="{{ route('bendahara.berkas.index') }}" class="bnd-btn bnd-btn-back">
                Kembali ke Daftar Berkas
            </a>
        </div>
        @endif
    </div>
</div>

<x-bendahara.modal-konfirmasi-cair :spj="$spj" />

@push('scripts')
<script>
    function updateCardState(idx) {
        const cb = document.getElementById('cb_bendahara_' + idx);
        const card = document.getElementById('card_bendahara_' + idx);
        const desc = document.getElementById('desc_bendahara_' + idx);
        const hiddenMirror = document.querySelector('.hidden-cb-mirror-' + idx);

        if (cb.checked) {
            card.classList.remove('bendahara-target');
            card.classList.add('bendahara-checked');
            desc.innerHTML = '<span style="color: #2563eb; font-weight: 600;">✔ Telah dicentang (belum disimpan)</span>';
            if (hiddenMirror) hiddenMirror.value = '1';
        } else {
            card.classList.remove('bendahara-checked');
            card.classList.add('bendahara-target');
            desc.innerHTML = '<span style="color: #d97706;">Klik kotak untuk mencentang berkas ini</span>';
            if (hiddenMirror) hiddenMirror.value = '0';
        }

        checkBendaharaCompletionState();
    }

    function checkBendaharaCompletionState() {
        const checkboxes = document.querySelectorAll('.bendahara-checkbox');
        const total = checkboxes.length;
        let checked = 0;
        checkboxes.forEach(cb => {
            if (cb.checked) checked++;
        });

        const isAllChecked = (checked === total && total > 0);

        const warnBanner = document.getElementById('kewajibanWarningBannerBerkas');
        const succBanner = document.getElementById('kewajibanSuccessBannerBerkas');
        const warnCount = document.getElementById('warn_ready_count_berkas');
        const succCount = document.getElementById('succ_ready_count_berkas');

        if (warnCount) warnCount.innerText = checked;
        if (succCount) succCount.innerText = checked;

        const btnOtp = document.getElementById('btnSubmitOtpBerkas');
        const btnLs = document.getElementById('btnSubmitLsBerkas');
        const btnOtpIcon = document.getElementById('btnSubmitOtpIconBerkas');
        const btnLsIcon = document.getElementById('btnSubmitLsIconBerkas');
        const btnOtpText = document.getElementById('btnSubmitOtpTextBerkas');
        const btnLsText = document.getElementById('btnSubmitLsTextBerkas');

        const inputOtp = document.getElementById('input_keterangan_otp_berkas');
        const inputLs = document.getElementById('input_nomor_sp2d_ls_berkas');

        const cardOtp = document.getElementById('card_choice_otp_berkas');
        const cardLs = document.getElementById('card_choice_ls_berkas');
        const ribbonOtp = document.getElementById('ribbon_otp_berkas');
        const ribbonLs = document.getElementById('ribbon_ls_berkas');
        const iconOtp = document.getElementById('icon_choice_otp_berkas');
        const iconLs = document.getElementById('icon_choice_ls_berkas');

        if (isAllChecked) {
            if (warnBanner) warnBanner.style.display = 'none';
            if (succBanner) succBanner.style.display = 'flex';

            if (btnOtp) {
                btnOtp.disabled = false;
                btnOtp.style.opacity = '1';
                btnOtp.style.cursor = 'pointer';
                btnOtp.style.pointerEvents = 'auto';
                btnOtp.style.background = '#2563eb';
                btnOtp.style.borderColor = '#2563eb';
                if (btnOtpIcon) btnOtpIcon.setAttribute('name', 'key-outline');
                if (btnOtpText) btnOtpText.innerText = 'Set Status: Menunggu OTP QLOLA KPA';
            }

            if (btnLs) {
                btnLs.disabled = false;
                btnLs.style.opacity = '1';
                btnLs.style.cursor = 'pointer';
                btnLs.style.pointerEvents = 'auto';
                btnLs.style.background = '#ea580c';
                if (btnLsIcon) btnLsIcon.setAttribute('name', 'paper-plane-outline');
                if (btnLsText) btnLsText.innerText = 'Set Status: Pembayaran LS Diajukan ke Bank';
            }

            if (inputOtp) inputOtp.disabled = false;
            if (inputLs) inputLs.disabled = false;

            if (cardOtp) { cardOtp.style.border = '2px solid #93c5fd'; cardOtp.style.opacity = '1'; }
            if (ribbonOtp) { ribbonOtp.style.background = '#2563eb'; }
            if (iconOtp) { iconOtp.style.background = '#eff6ff'; iconOtp.style.color = '#2563eb'; }

            if (cardLs) { cardLs.style.border = '2px solid #fed7aa'; cardLs.style.opacity = '1'; }
            if (ribbonLs) { ribbonLs.style.background = '#ea580c'; }
            if (iconLs) { iconLs.style.background = '#fff7ed'; iconLs.style.color = '#ea580c'; }
        } else {
            if (warnBanner) warnBanner.style.display = 'flex';
            if (succBanner) succBanner.style.display = 'none';

            if (btnOtp) {
                btnOtp.disabled = true;
                btnOtp.style.opacity = '0.55';
                btnOtp.style.cursor = 'not-allowed';
                btnOtp.style.pointerEvents = 'none';
                btnOtp.style.background = '#94a3b8';
                btnOtp.style.borderColor = '#94a3b8';
                if (btnOtpIcon) btnOtpIcon.setAttribute('name', 'lock-closed-outline');
                if (btnOtpText) btnOtpText.innerText = 'Terkunci: Menunggu OTP QLOLA KPA';
            }

            if (btnLs) {
                btnLs.disabled = true;
                btnLs.style.opacity = '0.55';
                btnLs.style.cursor = 'not-allowed';
                btnLs.style.pointerEvents = 'none';
                btnLs.style.background = '#94a3b8';
                if (btnLsIcon) btnLsIcon.setAttribute('name', 'lock-closed-outline');
                if (btnLsText) btnLsText.innerText = 'Terkunci: Pembayaran LS Diajukan ke Bank';
            }

            if (inputOtp) inputOtp.disabled = true;
            if (inputLs) inputLs.disabled = true;

            if (cardOtp) { cardOtp.style.border = '2px solid #cbd5e1'; cardOtp.style.opacity = '0.85'; }
            if (ribbonOtp) { ribbonOtp.style.background = '#94a3b8'; }
            if (iconOtp) { iconOtp.style.background = '#f1f5f9'; iconOtp.style.color = '#94a3b8'; }

            if (cardLs) { cardLs.style.border = '2px solid #cbd5e1'; cardLs.style.opacity = '0.85'; }
            if (ribbonLs) { ribbonLs.style.background = '#94a3b8'; }
            if (iconLs) { iconLs.style.background = '#f1f5f9'; iconLs.style.color = '#94a3b8'; }
        }
    }

    function centangSemuaBendahara(check) {
        const checkboxes = document.querySelectorAll('.bendahara-checkbox');
        checkboxes.forEach((cb, idx) => {
            cb.checked = check;
            updateCardState(idx);
        });
        checkBendaharaCompletionState();
    }
</script>
@endpush
@endsection
