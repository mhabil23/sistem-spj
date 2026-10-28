@extends('layouts.teknis')

@php
$title = 'Buat SPJ Baru';
$subtitle = 'Formulir pengajuan Surat Pertanggungjawaban (SPJ).';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/teknis/spj.css'])
    <style>
        .upload-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: var(--dash-input-bg);
            border: 1px solid var(--dash-border);
            border-radius: var(--radius-md);
            margin-bottom: 0.75rem;
            transition: var(--transition);
        }
        .upload-item:hover {
            border-color: #0284c7;
            box-shadow: 0 4px 6px -1px rgba(2, 132, 199, 0.15);
        }
        .upload-info {
            flex: 1;
        }
        .upload-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--dash-text-dark);
            display: block;
            margin-bottom: 0.25rem;
        }
        .upload-desc {
            font-size: 0.75rem;
            color: var(--dash-text-muted);
        }
        .upload-action {
            margin-left: 1rem;
        }
        .file-input {
            font-size: 0.85rem;
            color: var(--dash-text-dark);
        }
        .file-input::file-selector-button {
            background: var(--dash-card-bg);
            border: 1px solid var(--dash-border);
            padding: 0.5rem 1rem;
            border-radius: var(--radius-md);
            color: var(--dash-text-dark);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            margin-right: 1rem;
        }
        .file-input::file-selector-button:hover {
            background: var(--dash-table-header-bg);
        }
        .badge-assignee {
            font-size: 0.7rem;
            padding: 0.15rem 0.5rem;
            border-radius: 4px;
            background: var(--dash-cyan-badge);
            color: var(--dash-cyan-text);
            margin-left: 0.5rem;
            display: inline-block;
        }
        .badge-bendahara {
            background: var(--dash-peach-badge);
            color: var(--dash-peach-text);
        }
        .badge-ppk {
            background: #fce7f3;
            color: #db2777;
        }
    </style>
@endpush

<div class="dashboard-heading">
    <div>
        <h2 class="dashboard-heading-title">Pengajuan SPJ</h2>
        <p class="dashboard-heading-subtitle">Isi formulir berikut dengan lengkap untuk mengajukan SPJ baru.</p>
    </div>
    <a href="{{ route('teknis.spj.index') }}" class="spj-btn spj-btn-secondary">
        <ion-icon name="arrow-back-outline"></ion-icon>
        Kembali
    </a>
</div>

<div class="spj-panel" style="animation-delay: 0.1s;">
    <div class="spj-panel-header">
        <div>
            <h2>Detail Pengajuan</h2>
            <p>Pastikan data yang dimasukkan valid dan sesuai bukti fisik.</p>
        </div>
    </div>
    
    <div class="spj-panel-body">
        <form action="{{ route('teknis.spj.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            


            <!-- DYNAMIC DROPDOWNS START -->
            <div style="padding: 1.5rem; background: linear-gradient(135deg, #f8fafc, #f1f5f9); border-radius: var(--radius-md); border: 1px solid var(--border); margin-bottom: 1.5rem;">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem; color: #0f172a;">Kategori & Kelengkapan Berkas</h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label class="spj-label">Nama Belanja</label>
                        <select id="nama_belanja" name="nama_belanja" class="spj-input" required onchange="handleNamaBelanjaChange()">
                            <option value="" disabled selected>Pilih Nama Belanja...</option>
                        </select>
                    </div>

                    <div>
                        <label class="spj-label">Jenis Belanja</label>
                        <select id="jenis_belanja" name="jenis_belanja" class="spj-input" required disabled onchange="handleJenisBelanjaChange()">
                            <option value="" disabled selected>Pilih Jenis Belanja...</option>
                        </select>
                    </div>
                </div>

                <div id="dokumen_container" style="display: none;">
                    <div style="margin-bottom: 0.75rem;">
                        <label class="spj-label" style="margin-bottom: 0;">Dokumen Wajib</label>
                        <span style="font-size: 0.75rem; color: #64748b; display: block;">Centang berkas fisik yang sudah disiapkan</span>
                    </div>
                    <div id="dokumen_list">
                        <!-- File inputs will be generated here -->
                    </div>
                </div>
            </div>
            <!-- DYNAMIC DROPDOWNS END -->

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;" class="spj-form-group">
                <div>
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.4rem;">
                        <label class="spj-label" style="margin-bottom: 0;">Nomor SPJ</label>
                        <span style="font-size: 0.72rem; color: #0284c7; background: #e0f2fe; padding: 2px 8px; border-radius: 4px; font-weight: 600;">Otomatis Sistem</span>
                    </div>
                    <input type="text" name="nomor_spj" id="nomor_spj_input" class="spj-input" value="{{ old('nomor_spj', $nomorSpjOtomatis ?? '') }}" placeholder="Digenerate otomatis oleh sistem" readonly style="background-color: #f8fafc; font-weight: 700; color: #0f172a; cursor: not-allowed;" title="Nomor SPJ dibuat otomatis oleh sistem">
                    <span style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem; display: block;">Nomor registrasi SPJ digenerate secara otomatis oleh sistem.</span>
                    @error('nomor_spj') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="spj-label">Nilai (Rp)</label>
                    <input type="number" name="nilai" id="nilai_input" class="spj-input" value="{{ old('nilai') }}" placeholder="Contoh: 1500000" min="0" step="0.01" required>
                    @error('nilai') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;" class="spj-form-group">
                <div style="grid-column: span 2;">
                    <label class="spj-label">Uraian Kegiatan</label>
                    <textarea name="kegiatan" required class="spj-input spj-textarea" placeholder="Contoh: Honor LPTB Bulan September 2026..." id="kegiatan_input">{{ old('kegiatan') }}</textarea>
                    @error('kegiatan') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="spj-label">Tanggal Kegiatan</label>
                    <input type="date" name="tanggal" required class="spj-input" value="{{ old('tanggal') }}">
                    @error('tanggal') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="spj-label">Nomor WhatsApp Pengaju / PIC</label>
                    <input type="text" name="no_wa" class="spj-input" value="{{ old('no_wa') }}" placeholder="Contoh: 081234567890">
                    <span style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem; display: block;">Nomor WhatsApp Anda agar Bagian Umum & Bendahara dapat menghubungi Anda langsung.</span>
                    @error('no_wa') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="spj-form-group" style="margin-top: 1.5rem;">
                <label class="spj-label">Keterangan Tambahan</label>
                <textarea name="keterangan" class="spj-input spj-textarea" placeholder="Opsional: Keterangan tambahan untuk SPJ ini...">{{ old('keterangan') }}</textarea>
                @error('keterangan') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
            </div>



            <div class="spj-form-group" style="margin-top: 2rem;">
                <label class="spj-label">Tindakan Lanjutan</label>
                <div class="spj-radio-group">
                    <label class="spj-radio-card">
                        <input type="radio" name="status" value="draft" {{ old('status', 'draft') === 'draft' ? 'checked' : '' }}>
                        <div class="spj-radio-content">
                            <strong>Simpan sebagai Draft</strong>
                            <span>Dapat diubah kembali nanti sebelum diajukan</span>
                        </div>
                    </label>
                    <label class="spj-radio-card">
                        <input type="radio" name="status" value="diajukan" {{ old('status') === 'diajukan' ? 'checked' : '' }}>
                        <div class="spj-radio-content">
                            <strong>Ajukan &amp; Bawa Berkas Fisik ke Umum</strong>
                            <span>Simpan pengajuan dan segera serahkan berkas fisik ke meja Subbagian Umum</span>
                        </div>
                    </label>
                </div>
                @error('status') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror

                <div id="print_status_banner" style="display: none; width: 100%; margin-top: 1.25rem; padding: 0.85rem 1.25rem; border-radius: var(--radius-md); font-size: 0.875rem; font-weight: 500;"></div>

                <div id="print_requirement_hint" style="display: none; margin-top: 0.75rem; padding: 0.75rem 1rem; border-radius: var(--radius-md); background: #fff7ed; border: 1px solid #fed7aa; color: #c2410c; font-size: 0.825rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <span><strong>Wajib Print:</strong> Anda memilih opsi <strong>Ajukan Sekarang</strong>. Sesuai ketentuan, Anda <u>wajib menekan tombol Print</u> dan mencetak lembar checklist berkas SPJ terlebih dahulu sebelum pengajuan dapat disimpan.</span>
                    </div>
                </div>

                <input type="hidden" name="is_printed" id="is_printed_input" value="{{ old('is_printed', '0') }}">
            </div>

            <div style="display: flex; justify-content: flex-end; align-items: center; gap: 1rem; margin-top: 2.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border); flex-wrap: wrap;">
                <a href="{{ route('teknis.spj.index') }}" class="spj-btn spj-btn-secondary">Batal</a>
                <button type="button" id="btn_cetak_langsung" onclick="cetakLangsung()" class="spj-btn spj-btn-print" title="Cetak langsung lembar checklist berkas fisik SPJ">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 6 2 18 2 18 9"></polyline>
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                        <rect x="6" y="14" width="12" height="8"></rect>
                    </svg>
                    Print
                </button>
                <button type="submit" class="spj-btn spj-btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Simpan Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Data Kategori SPJ
    const spjData = {
        "Belanja Bahan (521211)": {
            "Belanja Konsumsi": [
                {name: "KAK", assignee: "SM"},
                {name: "FP (dari BOS)", assignee: "SM"},
                {name: "Undangan", assignee: "SM"},
                {name: "Daftar Hadir", assignee: "SM"},
                {name: "Notula", assignee: "SM"},
                {name: "Bukti pembelian (nota)", assignee: "SM"},
                {name: "BAST", assignee: "SM"},
                {name: "PPh 22 (jika diatas 2 juta)", assignee: "Bendahara"}
            ],
            "Belanja paket data komunikasi": [
                {name: "KAK", assignee: "SM"},
                {name: "FP (dari BOS)", assignee: "SM"},
                {name: "Bukti pembelian (nota)", assignee: "SM"},
                {name: "Daftar nama dan nomor hp penerima paket data/pulsa", assignee: "SM"},
                {name: "Screenshoot bukti transfer paket data/pulsa", assignee: "SM"},
                {name: "PPh 22 1.5% (jika pembelian diatas 2 juta)", assignee: "Bendahara"}
            ],
            "Perlengkapan pelatihan": [
                {name: "KAK", assignee: "SM"},
                {name: "FP (dari BOS)", assignee: "SM"},
                {name: "Bukti pembelian (nota)", assignee: "SM"},
                {name: "Daftar Alokasi (tanda terima barang diserahkan)", assignee: "SM"},
                {name: "BAST", assignee: "SM"},
                {name: "Faktur Pajak", assignee: "SM"},
                {name: "PPn (untuk transaksi diatas 2 juta)", assignee: "SM"},
                {name: "PPh 22 1.5% (jika pembelian diatas 2 juta)", assignee: "Bendahara"}
            ]
        },
        "Belanja barang persediaan konsumsi (521811)": {
            "Belanja ATK CS / percetakan dokumen": [
                {name: "KAK", assignee: "SM"},
                {name: "FP (dari BOS)", assignee: "SM"},
                {name: "Bukti pembelian (< 10 juta) / kwitansi (> 50 juta)", assignee: "SM"},
                {name: "BAST dilengkapi dengan dokumentasi barang", assignee: "SM"},
                {name: "Faktur Pajak", assignee: "SM"},
                {name: "PPn (untuk transaksi diatas 2 juta)", assignee: "SM"},
                {name: "PPh 22 1.5% (jika pembelian diatas 2 juta)", assignee: "Bendahara"}
            ]
        },
        "Belanja barang non operasional lainnya (521219)": {
            "Belanja asuransi petugas": [
                {name: "KAK", assignee: "SM"},
                {name: "FP (dari BOS)", assignee: "SM"},
                {name: "Bukti pembelian (invoice)", assignee: "SM"},
                {name: "Premi", assignee: "SM"},
                {name: "Polis / SPK", assignee: "SM"},
                {name: "Kartu Tanda Peserta", assignee: "SM"}
            ],
            "Belanja pengiriman dokumen": [
                {name: "KAK", assignee: "SM"},
                {name: "FP (dari BOS)", assignee: "SM"},
                {name: "Invoice/Resi pengiriman/Surat Tanda Terima Barang (STT)", assignee: "SM"},
                {name: "Bukti Pembelian/Pembayaran", assignee: "SM"},
                {name: "BAST", assignee: "SM"},
                {name: "Faktur Pajak", assignee: "SM"},
                {name: "SSP PPn Pengiriman 1,1%", assignee: "Bendahara"},
                {name: "SSP PPh 23 2%", assignee: "Bendahara"}
            ]
        },
        "Belanja jasa Sewa (522411)": {
            "Dekorasi stan pameran": [
                {name: "KAK", assignee: "SM"},
                {name: "FP (dari BOS)", assignee: "SM"},
                {name: "Bukti Pembelian/Pembayaran", assignee: "SM"},
                {name: "BAST", assignee: "SM"},
                {name: "Fotokopi NPWP dan fotokopi rekening koran (jika diperlukan)", assignee: "SM"},
                {name: "Invoice", assignee: "SM"},
                {name: "Fotocopy SIM/KTP dan STNK (kendaraan perorangan)", assignee: "SM"},
                {name: "Faktur Pajak", assignee: "Bendahara"},
                {name: "SSP PPN", assignee: "Bendahara"},
                {name: "SSP PPh Pasal 4 ayat 2 Final 10%", assignee: "Bendahara"},
                {name: "SSP PPh pasal 23 2%", assignee: "Bendahara"}
            ],
            "Sewa stan pameran": [
                {name: "KAK", assignee: "SM"},
                {name: "FP (dari BOS)", assignee: "SM"},
                {name: "Bukti Pembelian/Pembayaran", assignee: "SM"},
                {name: "BAST", assignee: "SM"},
                {name: "Fotokopi NPWP dan fotokopi rekening koran (jika diperlukan)", assignee: "SM"},
                {name: "Invoice", assignee: "SM"},
                {name: "Fotocopy SIM/KTP dan STNK", assignee: "SM"},
                {name: "Faktur Pajak", assignee: "Bendahara"},
                {name: "SSP PPN", assignee: "Bendahara"},
                {name: "SSP PPh Pasal 4 ayat 2 Final 10%", assignee: "Bendahara"},
                {name: "SSP PPh pasal 23 2%", assignee: "Bendahara"}
            ]
        },
        "Belanja jasa Profesi (522151)": {
            "Honor Moderator": [
                {name: "KAK", assignee: "SM"},
                {name: "FP (dari BOS)", assignee: "SM"},
                {name: "SK KPA (belanja jasa profesi)", assignee: "SM"},
                {name: "Undangan moderator", assignee: "SM"},
                {name: "Undangan peserta", assignee: "SM"},
                {name: "Jadwal kegiatan", assignee: "SM"},
                {name: "Daftar hadir moderator", assignee: "SM"},
                {name: "CV moderator", assignee: "SM"},
                {name: "Kuitansi", assignee: "SM"},
                {name: "Salinan/fotokopi NPWP dan nomor rekening", assignee: "SM"},
                {name: "SSP PPh Pasal 21", assignee: "Bendahara"}
            ],
            "Honor Narasumber": [
                {name: "KAK", assignee: "SM"},
                {name: "FP (dari BOS)", assignee: "SM"},
                {name: "SK KPA (belanja jasa profesi)", assignee: "SM"},
                {name: "Undangan moderator", assignee: "SM"},
                {name: "Undangan peserta", assignee: "SM"},
                {name: "Jadwal kegiatan", assignee: "SM"},
                {name: "Daftar hadir moderator", assignee: "SM"},
                {name: "CV moderator", assignee: "SM"},
                {name: "Bahan paparan narasumber", assignee: "SM"},
                {name: "Kuitansi", assignee: "SM"},
                {name: "Salinan/fotokopi NPWP dan nomor rekening", assignee: "SM"},
                {name: "SSP PPh Pasal 21", assignee: "Bendahara"}
            ]
        },
        "Belanja jasa lainnya (522191)": {
            "Penayangan iklan layanan masyarakat": [
                {name: "KAK", assignee: "SM"},
                {name: "FP (dari BOS)", assignee: "SM"},
                {name: "Bukti Pembelian/Pembayaran", assignee: "SM"},
                {name: "BAST", assignee: "SM"},
                {name: "Laporan pelaksanaan kegiatan disertai dokumentasi", assignee: "SM"},
                {name: "Faktur Pajak", assignee: "Bendahara"},
                {name: "SSP PPN / SSP PPh 23", assignee: "Bendahara"}
            ],
            "Pelaksanaan pencanangan kegiatan": [
                {name: "KAK", assignee: "SM"},
                {name: "FP (dari BOS)", assignee: "SM"},
                {name: "Undangan dan daftar Hadir", assignee: "SM"},
                {name: "Bukti Pembelian/Pembayaran", assignee: "SM"},
                {name: "BAST", assignee: "SM"},
                {name: "Laporan pelaksanaan kegiatan disertai dokumentasi", assignee: "SM"},
                {name: "Faktur Pajak", assignee: "Bendahara"},
                {name: "SSP PPN / SSP PPh 23", assignee: "Bendahara"}
            ]
        },
        "Honor output kegiatan (512213)": {
            "Honor Tim Pelaksana / Pokja kegiatan": [
                {name: "SK KPA/SK KBPS tentang Pokja", assignee: "SM"},
                {name: "Laporan tim/output pekerjaan", assignee: "SM"},
                {name: "Daftar rekap kuitansi", assignee: "SM"},
                {name: "SSP PPh Pasal 21", assignee: "Bendahara"}
            ],
            "Honor Pengajar": [
                {name: "KAK", assignee: "SM"},
                {name: "FP (dari BOS)", assignee: "SM"},
                {name: "SK KPA yang mencakup nama, tugas, rate (SBKS)", assignee: "SM"},
                {name: "Jadwal kegiatan", assignee: "SM"},
                {name: "Daftar hadir (panitia, pengajar, dan peserta)", assignee: "SM"},
                {name: "Daftar rekap kuitansi", assignee: "SM"},
                {name: "Laporan pengajar", assignee: "SM"},
                {name: "SSP PPh Pasal 21", assignee: "Bendahara"}
            ],
            "Honor Petugas Mitra (Non PNS atau PNS Non BPS)": [
                {name: "KAK", assignee: "SM"},
                {name: "FP (dari BOS)", assignee: "SM"},
                {name: "SK KPA yang mencakup nama, tugas, rate (SBKS)", assignee: "SM"},
                {name: "Surat kontrak/surat perjanjian kerja", assignee: "SM"},
                {name: "Daftar rekap kuitansi", assignee: "SM"},
                {name: "BAPP/BAST", assignee: "SM"},
                {name: "SSP PPh Pasal 21", assignee: "Bendahara"}
            ],
            "Honor Petugas (PNS BPS)": [
                {name: "KAK", assignee: "SM"},
                {name: "FP (dari BOS)", assignee: "SM"},
                {name: "SK KPA yang mencakup nama, tugas, rate (SBKS)", assignee: "SM"},
                {name: "Daftar rekap kuitansi", assignee: "SM"},
                {name: "SSP PPh Pasal 21", assignee: "Bendahara"}
            ]
        },
        "Belanja Perjalanan Dinas Biasa (524111)": {
            "Perjalanan Dinas Keluar Kota": [
                {name: "FP (dari BOS)", assignee: "SM"},
                {name: "Surat Tugas", assignee: "SM"},
                {name: "Surat Perintah Perjalanan Dinas lengkap dengan visum", assignee: "SM"},
                {name: "Rincian Biaya Perjalanan dinas biasa atau DOP", assignee: "PPK"},
                {name: "Tiket/kwitansi kendaraan", assignee: "SM"},
                {name: "Kuitansi Pembayaran Bukti Penginapan", assignee: "SM"},
                {name: "Surat pernyataan tidak menggunakan kendaraan dinas", assignee: "SM"},
                {name: "Laporan perjalanan dinas dan dokumentasi kegiatan (GPS)", assignee: "SM"},
                {name: "Kuitansi perjalanan dinas", assignee: "PPK"}
            ]
        },
        "Belanja Perjalanan Dinas Dalam Kota (524113)": {
            "Perjalanan Dinas Dalam Kota sampai dengan 8 jam": [
                {name: "KAK", assignee: "SM"},
                {name: "FP (dari BOS)", assignee: "SM"},
                {name: "Surat Tugas", assignee: "SM"},
                {name: "Visum yang ditandatangani pejabat di tempat tujuan", assignee: "SM"},
                {name: "Kwitansi Transpor/Daftar Biaya Transport lokal", assignee: "PPK"},
                {name: "Daftar Pengeluaran riil untuk transpor tanpa bukti", assignee: "PPK"},
                {name: "Surat pernyataan tidak menggunakan kendaraan dinas", assignee: "SM"}
            ],
            "Perjalanan Dinas Dalam Kota lebih dari 8 jam": [
                {name: "KAK", assignee: "SM"},
                {name: "FP (dari BOS)", assignee: "SM"},
                {name: "Surat Tugas", assignee: "SM"},
                {name: "SPPD yang ditandatangani pejabat tempat tujuan", assignee: "SM"},
                {name: "Visum yang ditandatangani responden pengawasan", assignee: "SM"},
                {name: "Kuitansi transpor", assignee: "PPK"},
                {name: "Daftar Pengeluaran riil untuk transpor tanpa bukti", assignee: "PPK"},
                {name: "Rincian Biaya Perjalanan Dinas", assignee: "PPK"},
                {name: "Jadwal Kegiatan (lebih dari 8 jam)", assignee: "SM"},
                {name: "Surat pernyataan tidak menggunakan kendaraan dinas", assignee: "SM"}
            ]
        },
        "Belanja Perjalanan Dinas Paket Meeting Dalam Kota (521114)": {
            "Penyelenggaraan paket meeting dalam kota": [
                {name: "KAK", assignee: "SM"},
                {name: "FP (dari BOS)", assignee: "SM"},
                {name: "Surat Undangan", assignee: "SM"},
                {name: "Surat Pernyataan Penanggung Jawab Kegiatan", assignee: "SM"},
                {name: "Surat Tugas", assignee: "SM"},
                {name: "Surat Perjalanan Dinas (SPD) kolektif (cap hotel)", assignee: "SM"},
                {name: "Daftar Hadir", assignee: "SM"},
                {name: "Jadwal Kegiatan", assignee: "SM"},
                {name: "Notulensi dan Dokumentasi rapat/laporan", assignee: "SM"},
                {name: "List Kamar Hotel yang dilegalisasi (fullboard)", assignee: "SM"},
                {name: "Tagihan Hotel", assignee: "PPK"},
                {name: "Kelengkapan berkas pengadaan", assignee: "PPK"}
            ],
            "Pembayaran perjalanan dalam rangka paket meeting": [
                {name: "KAK", assignee: "SM"},
                {name: "FP (dari BOS)", assignee: "SM"},
                {name: "Surat Undangan", assignee: "SM"},
                {name: "Surat Tugas", assignee: "SM"},
                {name: "Surat Perjalanan Dinas (SPD) kolektif (cap hotel)", assignee: "SM"},
                {name: "Daftar Hadir", assignee: "SM"},
                {name: "Surat Pernyataan tidak menggunakan kendaraan dinas", assignee: "SM"},
                {name: "Notulensi dan Dokumentasi rapat/laporan", assignee: "SM"},
                {name: "Daftar Rincian Perhitungan Pembayaran Perjalanan Dinas", assignee: "SM"}
            ]
        }
    };

    document.addEventListener("DOMContentLoaded", function() {
        const selNamaBelanja = document.getElementById("nama_belanja");
        
        // Populate first dropdown
        for (const nama in spjData) {
            const opt = document.createElement("option");
            opt.value = nama;
            opt.textContent = nama;
            selNamaBelanja.appendChild(opt);
        }
    });

    function handleNamaBelanjaChange() {
        resetPrintStatus();
        const nama = document.getElementById("nama_belanja").value;
        const selJenisBelanja = document.getElementById("jenis_belanja");
        const docContainer = document.getElementById("dokumen_container");
        
        // Reset and enable 2nd dropdown
        selJenisBelanja.innerHTML = '<option value="" disabled selected>Pilih Jenis Belanja...</option>';
        docContainer.style.display = "none";

        if (nama && spjData[nama]) {
            selJenisBelanja.disabled = false;
            for (const jenis in spjData[nama]) {
                const opt = document.createElement("option");
                opt.value = jenis;
                opt.textContent = jenis;
                selJenisBelanja.appendChild(opt);
            }
        } else {
            selJenisBelanja.disabled = true;
        }
    }

    function handleJenisBelanjaChange() {
        resetPrintStatus();
        const nama = document.getElementById("nama_belanja").value;
        const jenis = document.getElementById("jenis_belanja").value;
        const docContainer = document.getElementById("dokumen_container");
        const docList = document.getElementById("dokumen_list");
        const kegInput = document.getElementById("kegiatan_input");



        docList.innerHTML = "";

        if (nama && jenis && spjData[nama][jenis]) {
            docContainer.style.display = "block";
            const docs = spjData[nama][jenis];
            
            docs.forEach((doc, idx) => {
                let badgeHtml = '';
                let uploadHtml = '';
                
                // If it is supposed to be uploaded by SM/Panitia (Teknis)
                if(doc.assignee.includes('SM') || doc.assignee.includes('Panitia')) {
                    badgeHtml = `<span class="badge-assignee">Wajib Disiapkan Teknis</span>`;
                    uploadHtml = `
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" name="berkas[${idx}]" value="1" style="width: 1.25rem; height: 1.25rem; accent-color: var(--primary);" required>
                        <span style="font-size: 0.85rem; font-weight: 500; color: #0f172a;">Ada (Fisik)</span>
                    </label>
                    `;
                } else if(doc.assignee.includes('Bendahara')) {
                    badgeHtml = `<span class="badge-assignee badge-bendahara">Dilengkapi Bendahara</span>`;
                    uploadHtml = `<span style="font-size: 0.8rem; color: #94a3b8; font-style: italic;">Tidak perlu (Diurus Bendahara)</span>`;
                } else if(doc.assignee.includes('PPK')) {
                    badgeHtml = `<span class="badge-assignee badge-ppk">Dilengkapi PPK</span>`;
                    uploadHtml = `<span style="font-size: 0.8rem; color: #94a3b8; font-style: italic;">Tidak perlu (Diurus PPK)</span>`;
                }

                const item = document.createElement("div");
                item.className = "upload-item";
                item.innerHTML = `
                    <div class="upload-info">
                        <div style="display: flex; align-items: center; margin-bottom: 0.25rem;">
                            <span class="upload-label" style="margin: 0;">${doc.name}</span>
                            ${badgeHtml}
                        </div>
                        <input type="hidden" name="nama_dokumen[${idx}]" value="${doc.name}">
                    </div>
                    <div class="upload-action">
                        ${uploadHtml}
                    </div>
                `;
                docList.appendChild(item);
            });
        } else {
            docContainer.style.display = "none";
        }
    }

    function formatRupiah(number) {
        if (!number || isNaN(number)) return 'Rp 0';
        return 'Rp ' + Number(number).toLocaleString('id-ID');
    }

    function formatDateIndo(dateStr) {
        if (!dateStr) return '—';
        const months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        const parts = dateStr.split('-');
        if (parts.length === 3) {
            const year = parts[0];
            const month = months[parseInt(parts[1], 10) - 1] || parts[1];
            const day = parseInt(parts[2], 10);
            return `${day} ${month} ${year}`;
        }
        return dateStr;
    }

    const masterDocDefs = [
        { label: 'KAK', keywords: ['KAK'] },
        { label: 'Form Permintaan', keywords: ['FP', 'Form Permintaan'] },
        { label: 'Surat Tugas', keywords: ['Surat Tugas', 'SPPD', 'Visum'] },
        { label: 'SK / SPK', keywords: ['SK KPA', 'Surat Keputusan', 'Surat kontrak', 'SPK', 'Polis', 'Perjanjian'] },
        { label: 'Undangan', keywords: ['Undangan', 'Surat Undangan'] },
        { label: 'Daftar Hadir / Penerima', keywords: ['Daftar Hadir', 'Daftar Penerima', 'Daftar nama', 'Daftar Alokasi', 'Daftar rekap kuitansi'] },
        { label: 'Notula / Laporan', keywords: ['Notula', 'Notulensi', 'Laporan tim', 'Laporan pengajar', 'Laporan pelaksanaan', 'Laporan perjalanan dinas'] },
        { label: 'Bukti Beli / Nota', keywords: ['Bukti pembelian', 'Bukti Pembelian', 'nota', 'kwitansi', 'Kuitansi', 'invoice', 'Invoice', 'Tagihan', 'Tiket', 'Kwitansi Transpor', 'Daftar Pengeluaran riil'] },
        { label: 'BAST', keywords: ['BAST', 'BAPP'] },
        { label: 'Bukti Bayar / Pajak', keywords: ['Faktur Pajak', 'SSP', 'PPh', 'PPn', 'Screenshoot bukti transfer'] },
        { label: 'Kesesuaian MRK', keywords: ['Kesesuaian MRK'] },
        { label: 'CMS', keywords: ['CMS'] },
        { label: 'Cek SBKS', keywords: ['SBKS'] }
    ];

    function getCleanDocLabel(docName) {
        for (let i = 0; i < masterDocDefs.length; i++) {
            const def = masterDocDefs[i];
            for (let j = 0; j < def.keywords.length; j++) {
                if (docName.toLowerCase().indexOf(def.keywords[j].toLowerCase()) !== -1) {
                    return def.label;
                }
            }
        }
        return docName.length > 25 ? docName.substring(0, 22) + '...' : docName;
    }

    function cetakLangsung() {
        const selNamaBelanja = document.getElementById("nama_belanja");
        const selJenisBelanja = document.getElementById("jenis_belanja");
        const namaBelanja = selNamaBelanja ? selNamaBelanja.value : '';
        const jenisBelanja = selJenisBelanja ? selJenisBelanja.value : '';

        if (!namaBelanja || !jenisBelanja) {
            alert("Silakan pilih Nama Belanja dan Jenis Belanja terlebih dahulu serta centang berkas fisik yang sudah disiapkan.");
            if (selNamaBelanja) selNamaBelanja.focus();
            return;
        }

        const docInputs = document.querySelectorAll('input[name^="nama_dokumen"]');
        if (docInputs.length === 0) {
            alert("Daftar dokumen belum tersedia. Silakan pilih Jenis Belanja terlebih dahulu.");
            return;
        }

        const nomorSpj = document.querySelector('[name="nomor_spj"]')?.value.trim() || '—';
        const kegiatan = document.querySelector('[name="kegiatan"]')?.value.trim() || '—';
        const tanggal = document.querySelector('[name="tanggal"]')?.value || '';
        const nilai = document.querySelector('[name="nilai"]')?.value || '0';
        const noWa = document.querySelector('[name="no_wa"]')?.value.trim() || '—';
        const keterangan = document.querySelector('[name="keterangan"]')?.value.trim() || '—';
        const pengajuName = "{{ Auth::user()->name ?? 'Staf Teknis' }}";
        const logoUrl = "{{ asset('images/logo-bps.png') }}";
        const tanggalCetak = new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        const tanggalFormatted = formatDateIndo(tanggal) || tanggalCetak;

        const seenLabels = {};
        const docCols = [];
        docInputs.forEach((input, i) => {
            const match = input.name.match(/\[(\d+)\]/);
            const idx = match ? match[1] : i;
            const checkbox = document.querySelector(`input[name="berkas[${idx}]"]`);
            const docName = input.value;
            let label = getCleanDocLabel(docName);
            if (seenLabels[label]) {
                seenLabels[label]++;
                label = label + ' (' + seenLabels[label] + ')';
            } else {
                seenLabels[label] = 1;
            }
            docCols.push({
                label: label,
                fullName: docName,
                isChecked: checkbox ? checkbox.checked : false
            });
        });

        const totalDocCols = docCols.length;
        const totalKelengkapanColspan = totalDocCols + 4;

        let docHeadersHtml = '';
        docCols.forEach(col => {
            docHeadersHtml += '                    <th>' + col.label + '<\/th>\n';
        });

        let docValuesHtml = '';
        docCols.forEach(col => {
            docValuesHtml += '                    <td style="font-size: 11pt; font-weight: bold; text-align: center;">' + (col.isChecked ? '&radic;' : '') + '<\/td>\n';
        });

        const printHtml = '<!DOCTYPE html>\n'
            + '<html lang="id">\n'
            + '<head>\n'
            + '    <meta charset="UTF-8">\n'
            + '    <title>Laporan SPJ - ' + (kegiatan !== '—' ? kegiatan : 'Kegiatan') + '<\/title>\n'
            + '    <style>\n'
            + '        @page {\n'
            + '            size: landscape;\n'
            + '            margin: 10mm;\n'
            + '        }\n'
            + '        * { box-sizing: border-box; }\n'
            + '        body {\n'
            + '            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;\n'
            + '            background: #fff;\n'
            + '            color: #111;\n'
            + '            margin: 0;\n'
            + '            padding: 0;\n'
            + '            font-size: 10px;\n'
            + '        }\n'
            + '        .print-only-logo {\n'
            + '            text-align: center;\n'
            + '            margin-bottom: 18px;\n'
            + '        }\n'
            + '        .print-only-logo img {\n'
            + '            height: 50px;\n'
            + '            margin-bottom: 6px;\n'
            + '        }\n'
            + '        .print-only-logo h2 {\n'
            + '            font-size: 16px;\n'
            + '            margin: 5px 0 3px;\n'
            + '            font-weight: bold;\n'
            + '            text-transform: uppercase;\n'
            + '            letter-spacing: 0.5px;\n'
            + '        }\n'
            + '        .print-only-logo p {\n'
            + '            font-size: 12px;\n'
            + '            margin: 0;\n'
            + '            color: #374151;\n'
            + '        }\n'
            + '        .meta-info {\n'
            + '            display: flex;\n'
            + '            justify-content: space-between;\n'
            + '            align-items: center;\n'
            + '            font-size: 11px;\n'
            + '            margin-bottom: 10px;\n'
            + '            padding: 0 4px;\n'
            + '        }\n'
            + '        .table-responsive {\n'
            + '            overflow: visible !important;\n'
            + '            width: 100% !important;\n'
            + '        }\n'
            + '        .cetak-table {\n'
            + '            width: 100%;\n'
            + '            border-collapse: collapse;\n'
            + '            font-size: 8px;\n'
            + '            text-align: center;\n'
            + '            margin-bottom: 15px;\n'
            + '            table-layout: auto;\n'
            + '        }\n'
            + '        .cetak-table th, .cetak-table td {\n'
            + '            border: 1px solid #333;\n'
            + '            padding: 4px 2px;\n'
            + '            white-space: normal;\n'
            + '            word-wrap: break-word;\n'
            + '        }\n'
            + '        .cetak-table th {\n'
            + '            background-color: #f5f5f5;\n'
            + '            font-weight: bold;\n'
            + '            text-align: center;\n'
            + '            min-width: 25px;\n'
            + '        }\n'
            + '        .ttd-container {\n'
            + '            display: flex;\n'
            + '            justify-content: flex-end;\n'
            + '            padding-right: 50px;\n'
            + '            text-align: left;\n'
            + '            font-size: 12px;\n'
            + '            margin-top: 35px;\n'
            + '            page-break-inside: avoid;\n'
            + '        }\n'
            + '    <\/style>\n'
            + '<\/head>\n'
            + '<body>\n'
            + '    <div class="print-only-logo">\n'
            + '        <img src="' + logoUrl + '" alt="Logo BPS" onerror="this.style.display=\'none\'">\n'
            + '        <h2>LAPORAN REKAPITULASI SPJ<\/h2>\n'
            + '        <p>Badan Pusat Statistik Kabupaten Kolaka Utara<\/p>\n'
            + '    <\/div>\n'
            + '    <div class="meta-info">\n'
            + '        <div>No. SPJ: <strong>' + nomorSpj + '</strong> | Staf Pengaju / PIC: <strong>' + pengajuName + '<\/strong> ' + (noWa !== '—' ? '(' + noWa + ')' : '') + '<\/div>\n'
            + '        <div>Belanja: <strong>' + namaBelanja + ' (' + jenisBelanja + ')<\/strong><\/div>\n'
            + '    <\/div>\n'
            + '    <div class="table-responsive">\n'
            + '        <table class="cetak-table">\n'
            + '            <thead>\n'
            + '                <tr>\n'
            + '                    <th rowspan="2" style="width: 30px;">No<\/th>\n'
            + '                    <th rowspan="2" style="min-width: 170px;">Nama Kegiatan<\/th>\n'
            + '                    <th rowspan="2" style="min-width: 95px;">Jumlah Anggaran<\/th>\n'
            + '                    <th rowspan="2" style="min-width: 80px;">Tanggal Penginputan SPJ<\/th>\n'
            + '                    <th colspan="' + totalKelengkapanColspan + '">Kelengkapan Administrasi<\/th>\n'
            + '                    <th rowspan="2" style="min-width: 80px;">Keterangan<\/th>\n'
            + '                <\/tr>\n'
            + '                <tr>\n'
            + docHeadersHtml
            + '                    <th style="min-width: 65px;">Tgl Masuk SPJ<\/th>\n'
            + '                    <th style="min-width: 65px;">Tgl Periksa PPK<\/th>\n'
            + '                    <th style="min-width: 45px;">Paraf PPSPM<\/th>\n'
            + '                    <th style="min-width: 45px;">Paraf Bendahara<\/th>\n'
            + '                <\/tr>\n'
            + '            <\/thead>\n'
            + '            <tbody>\n'
            + '                <tr>\n'
            + '                    <td>1<\/td>\n'
            + '                    <td style="text-align: left; padding: 5px 6px;">' + kegiatan + '<\/td>\n'
            + '                    <td style="text-align: right; white-space: nowrap; font-weight: bold; padding: 5px 6px;">Rp. ' + formatRupiah(nilai) + '<\/td>\n'
            + '                    <td>' + tanggalCetak + '<\/td>\n'
            + docValuesHtml
            + '                    <td>-<\/td>\n'
            + '                    <td>-<\/td>\n'
            + '                    <td><\/td>\n'
            + '                    <td><\/td>\n'
            + '                    <td style="text-align: left; padding: 5px 6px;">' + (keterangan !== '—' ? keterangan : '') + '<\/td>\n'
            + '                <\/tr>\n'
            + '            <\/tbody>\n'
            + '            <tfoot>\n'
            + '                <tr>\n'
            + '                    <th colspan="2" style="text-align: right; padding-right: 15px;">TOTAL<\/th>\n'
            + '                    <th style="text-align: right; white-space: nowrap; font-weight: bold; padding: 5px 6px;">Rp. ' + formatRupiah(nilai) + '<\/th>\n'
            + '                    <th colspan="' + (totalKelengkapanColspan + 2) + '"><\/th>\n'
            + '                <\/tr>\n'
            + '            <\/tfoot>\n'
            + '        <\/table>\n'
            + '    <\/div>\n'
            + '    <div class="ttd-container">\n'
            + '        <div>\n'
            + '            <p style="margin: 0;">setor ke PPSPM :<\/p>\n'
            + '            <p style="margin: 0;">Lasusua, ' + tanggalCetak + '<\/p>\n'
            + '            <p style="margin: 0;">Pejabat Pembuat Komitmen<\/p>\n'
            + '            <br><br><br><br>\n'
            + '            <p style="margin: 0; text-decoration: underline; font-weight: bold;">Hidayatullah<\/p>\n'
            + '            <p style="margin: 0;">NIP.198510292011011009<\/p>\n'
            + '        <\/div>\n'
            + '    <\/div>\n'
            + '<\/body>\n'
            + '<\/html>';

        let printFrame = document.getElementById('spj_print_iframe');
        if (!printFrame) {
            printFrame = document.createElement('iframe');
            printFrame.id = 'spj_print_iframe';
            printFrame.style.position = 'fixed';
            printFrame.style.right = '0';
            printFrame.style.bottom = '0';
            printFrame.style.width = '0';
            printFrame.style.height = '0';
            printFrame.style.border = '0';
            document.body.appendChild(printFrame);
        }

        const frameDoc = printFrame.contentWindow.document;
        frameDoc.open();
        frameDoc.write(printHtml);
        frameDoc.close();

        let printCompleted = false;
        const markAsPrinted = function() {
            if (printCompleted) return;
            printCompleted = true;
            isPrinted = true;

            const isPrintedInput = document.getElementById('is_printed_input');
            if (isPrintedInput) isPrintedInput.value = '1';

            const printBtn = document.getElementById('btn_cetak_langsung');
            if (printBtn) {
                printBtn.classList.remove('print-btn-shake');
                printBtn.classList.add('is-printed');
                printBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Sudah Dicetak
                `;
            }

            const banner = document.getElementById('print_status_banner');
            if (banner) {
                banner.style.display = 'flex';
                banner.style.alignItems = 'center';
                banner.style.gap = '0.5rem';
                banner.style.backgroundColor = '#d1fae5';
                banner.style.color = '#065f46';
                banner.style.border = '1px solid #34d399';
                banner.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <span><strong>Checklist Telah Dicetak:</strong> Lembar checklist SPJ telah dicetak. Anda sekarang dapat melanjutkan pengajuan SPJ.</span>
                `;
            }

            const hint = document.getElementById('print_requirement_hint');
            if (hint) {
                hint.style.display = 'none';
            }
        };

        const doPrint = function() {
            try {
                try {
                    printFrame.contentWindow.addEventListener('afterprint', markAsPrinted, { once: true });
                } catch(e) {}
                window.addEventListener('afterprint', markAsPrinted, { once: true });

                const onFocusBack = function() {
                    window.removeEventListener('focus', onFocusBack);
                    setTimeout(markAsPrinted, 300);
                };
                window.addEventListener('focus', onFocusBack, { once: true });

                printFrame.contentWindow.focus();
                printFrame.contentWindow.print();
            } catch (err) {
                const printWin = window.open('', '_blank');
                if (printWin) {
                    try {
                        printWin.addEventListener('afterprint', markAsPrinted, { once: true });
                    } catch(e) {}
                    window.addEventListener('afterprint', markAsPrinted, { once: true });

                    const onFocusBack = function() {
                        window.removeEventListener('focus', onFocusBack);
                        setTimeout(markAsPrinted, 300);
                    };
                    window.addEventListener('focus', onFocusBack, { once: true });

                    printWin.document.write(printHtml);
                    printWin.document.close();
                    printWin.focus();
                    printWin.print();
                } else {
                    alert("Gagal membuka jendela cetak. Pastikan pop-up diizinkan.");
                }
            }
        };

        const logoImg = frameDoc.querySelector('img');
        if (logoImg && !logoImg.complete) {
            logoImg.onload = doPrint;
            logoImg.onerror = doPrint;
            setTimeout(doPrint, 500);
        } else {
            setTimeout(doPrint, 200);
        }
    }

    let isPrinted = {{ old('is_printed', '0') == '1' ? 'true' : 'false' }};

    function resetPrintStatus() {
        isPrinted = false;
        const input = document.getElementById('is_printed_input');
        if (input) input.value = '0';

        const printBtn = document.getElementById('btn_cetak_langsung');
        if (printBtn) {
            printBtn.classList.remove('is-printed');
            printBtn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                Print
            `;
        }

        const banner = document.getElementById('print_status_banner');
        if (banner) {
            banner.style.display = 'none';
        }

        updatePrintNotice();
    }

    function updatePrintNotice() {
        const selectedStatus = document.querySelector('input[name="status"]:checked')?.value || 'draft';
        const hint = document.getElementById('print_requirement_hint');
        if (hint) {
            if (selectedStatus === 'diajukan' && !isPrinted) {
                hint.style.display = 'block';
            } else {
                hint.style.display = 'none';
            }
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        const statusRadios = document.querySelectorAll('input[name="status"]');
        statusRadios.forEach(radio => {
            radio.addEventListener('change', updatePrintNotice);
        });

        if (isPrinted) {
            const printBtn = document.getElementById('btn_cetak_langsung');
            if (printBtn) {
                printBtn.classList.add('is-printed');
                printBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Sudah Dicetak
                `;
            }
        }

        updatePrintNotice();

        const form = document.querySelector('form[action="{{ route('teknis.spj.store') }}"]') || document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const selectedStatus = document.querySelector('input[name="status"]:checked')?.value || 'draft';
                if (selectedStatus === 'diajukan' && !isPrinted) {
                    e.preventDefault();

                    const banner = document.getElementById('print_status_banner');
                    if (banner) {
                        banner.style.display = 'flex';
                        banner.style.alignItems = 'center';
                        banner.style.gap = '0.5rem';
                        banner.style.backgroundColor = '#fee2e2';
                        banner.style.color = '#991b1b';
                        banner.style.border = '1px solid #f87171';
                        banner.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            <span><strong>Belum Dicetak:</strong> Teknis tidak dapat mengajukan SPJ jika belum menekan <strong>Print</strong> sampai tercetak. Silakan klik tombol Print terlebih dahulu.</span>
                        `;
                    }

                    const printBtn = document.getElementById('btn_cetak_langsung');
                    if (printBtn) {
                        printBtn.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        printBtn.classList.remove('print-btn-shake');
                        void printBtn.offsetWidth;
                        printBtn.classList.add('print-btn-shake');
                    }

                    alert("SPJ tidak dapat diajukan jika belum menekan tombol Print sampai tercetak!\nSilakan cetak lembar checklist berkas fisik SPJ terlebih dahulu.");
                    return false;
                }
            });
        }
    });
</script>
@endpush
