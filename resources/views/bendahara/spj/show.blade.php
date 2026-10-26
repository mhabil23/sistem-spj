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
                    } elseif ($spj->status === 'selesai') {
                        $badgeClass = 'bnd-badge-mint';
                        $badgeText = 'Pembayaran Selesai';
                    } elseif ($spj->status === 'disetujui_ppspm') {
                        $badgeClass = 'bnd-badge-mint';
                        $badgeText = 'Siap Dicairkan (PPSPM)';
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
                @if($spj->nomor_spj)
                <div class="bnd-header-meta-item">
                    <ion-icon name="document-text-outline"></ion-icon>
                    <span>No. SPJ: <strong>{{ $spj->nomor_spj }}</strong></span>
                </div>
                @endif
            </div>
        </div>

        <div class="bnd-header-actions">
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

        <!-- LAMPIRAN FILE DOKUMEN -->
        <div>
            <label style="display: block; font-size: 12px; font-weight: 700; color: var(--bnd-slate-500); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">
                Berkas Dokumen SPJ
            </label>
            @if($spj->dokumen_file)
                <div class="bnd-doc-item">
                    <div class="bnd-doc-left">
                        <div class="bnd-doc-icon">
                            <ion-icon name="document-attach-outline"></ion-icon>
                        </div>
                        <div>
                            <h4 class="bnd-doc-title">Dokumen SPJ Lengkap (Fisik / Digital)</h4>
                            <div class="bnd-doc-meta">
                                <span>Lampiran resmi dari staf pengaju</span>
                                <span>&bull;</span>
                                <span>Format PDF / Gambar</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . $spj->dokumen_file) }}" target="_blank" class="bnd-btn" style="background: #2563eb; color: #ffffff; box-shadow: 0 4px 12px rgba(37,99,235,0.25);">
                        <ion-icon name="open-outline"></ion-icon>
                        <span>Lihat Dokumen Asli</span>
                    </a>
                </div>
            @else
                <div style="background-color: #fff1f2; color: #9f1239; padding: 14px 18px; border-radius: 10px; border: 1px solid #fecdd3; display: flex; align-items: center; gap: 10px; font-size: 13px;">
                    <ion-icon name="alert-circle-outline" style="font-size: 22px; color: #e11d48;"></ion-icon>
                    <span>Tidak ada file lampiran digital yang diunggah pada SPJ ini. Pemeriksaan dilakukan langsung terhadap berkas fisik.</span>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- PANEL EKSEKUSI PENCAIRAN DANA (BENDAHARA) -->
@if($spj->status == 'disetujui_ppspm' || $spj->status == 'menunggu_otp_kpa')
<div class="bnd-card" style="border: 2px solid #cbd5e1; box-shadow: var(--bnd-shadow-lg);">
    <div class="bnd-card-header" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
        <div class="bnd-card-header-left">
            <div class="bnd-card-header-icon emerald">
                <ion-icon name="cash-outline"></ion-icon>
            </div>
            <div>
                <h3 class="bnd-card-header-title">Eksekusi Pencairan Dana &amp; Pembayaran (Bendahara)</h3>
                <p class="bnd-card-header-desc">Pilih status tahapan pembayaran sesuai alur sistem perbankan BRI QLOLA &amp; otorisasi KPA.</p>
            </div>
        </div>
    </div>
    <div class="bnd-card-body">
        <form action="{{ route('bendahara.spj.verify', $spj->id) }}" method="POST">
            @csrf

            <!-- 2 TOMBOL PILIHAN UTAMA -->
            <div class="bnd-execution-grid">
                
                <!-- PILIHAN 1: MENUNGGU OTP QLOLA KPA -->
                <div class="bnd-choice-card card-otp">
                    <div class="bnd-choice-ribbon">Tahap 1 QLOLA</div>
                    <div>
                        <div class="bnd-choice-head">
                            <div class="bnd-choice-icon">
                                <ion-icon name="key-outline"></ion-icon>
                            </div>
                            <h3 class="bnd-choice-title">Menunggu OTP QLOLA KPA</h3>
                        </div>
                        <p class="bnd-choice-desc">
                            Pilih opsi ini jika transaksi pembayaran telah Anda input ke portal <strong>BRI QLOLA</strong> dan saat ini sedang menunggu otorisasi kode OTP dari <strong>Kuasa Pengguna Anggaran (KPA)</strong>.
                        </p>

                        <label class="bnd-choice-input-label">Catatan / Referensi QLOLA Maker (Opsional):</label>
                        <input type="text" name="keterangan_otp" class="bnd-choice-input" placeholder="Contoh: Batch Maker #2401 - Menunggu KPA approve">
                    </div>

                    <div class="bnd-choice-actions">
                        <button type="submit" name="action" value="otp_kpa" class="bnd-btn-submit-otp">
                            <ion-icon name="key-outline" style="font-size: 18px;"></ion-icon>
                            <span>Menunggu OTP QLOLA KPA</span>
                        </button>
                        <button type="button" onclick="openWaModal('otp_kpa')" class="bnd-btn-choice-wa" title="Kirim Info Menunggu OTP via WhatsApp">
                            <ion-icon name="logo-whatsapp"></ion-icon>
                            <span>WA</span>
                        </button>
                    </div>
                </div>

                <!-- PILIHAN 2: PROSES PEMBAYARAN TELAH SELESAI -->
                <div class="bnd-choice-card card-cair">
                    <div class="bnd-choice-ribbon">Tahap 2 Selesai</div>
                    <div>
                        <div class="bnd-choice-head">
                            <div class="bnd-choice-icon">
                                <ion-icon name="checkmark-done-circle-outline"></ion-icon>
                            </div>
                            <h3 class="bnd-choice-title">Proses Pembayaran Selesai</h3>
                        </div>
                        <p class="bnd-choice-desc">
                            Kode OTP KPA telah dimasukkan &amp; dana berhasil ditransfer via QLOLA. Pilih tombol ini untuk mengonfirmasi bahwa <strong>proses pembayaran telah selesai, silahkan cek rekening</strong>.
                        </p>

                        <label class="bnd-choice-input-label">Bukti Transfer / No. Referensi Transaksi (Opsional):</label>
                        <input type="text" name="bukti_transfer" class="bnd-choice-input" placeholder="Contoh: REF-QLOLA-9283719 atau No. Resi Bank" value="{{ $spj->bukti_transfer ?? '' }}">
                    </div>

                    <div class="bnd-choice-actions">
                        <button type="submit" name="action" value="setujui" class="bnd-btn-submit-cair">
                            <ion-icon name="wallet-outline" style="font-size: 18px;"></ion-icon>
                            <span>Proses Pembayaran Telah Selesai, Silahkan Cek Rekening</span>
                        </button>
                        <button type="button" onclick="openWaModalWithBukti('cair')" class="bnd-btn-choice-wa" title="Kirim Konfirmasi Pencairan via WhatsApp">
                            <ion-icon name="logo-whatsapp"></ion-icon>
                            <span>WA</span>
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
