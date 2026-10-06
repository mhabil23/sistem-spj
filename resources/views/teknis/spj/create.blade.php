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
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            margin-bottom: 0.75rem;
            transition: var(--transition);
        }
        .upload-item:hover {
            border-color: var(--primary);
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.1);
        }
        .upload-info {
            flex: 1;
        }
        .upload-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-main);
            display: block;
            margin-bottom: 0.25rem;
        }
        .upload-desc {
            font-size: 0.75rem;
            color: var(--text-muted);
        }
        .upload-action {
            margin-left: 1rem;
        }
        .file-input {
            font-size: 0.85rem;
        }
        .file-input::file-selector-button {
            background: #e2e8f0;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: var(--radius-md);
            color: var(--text-main);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            margin-right: 1rem;
        }
        .file-input::file-selector-button:hover {
            background: #cbd5e1;
        }
        .badge-assignee {
            font-size: 0.7rem;
            padding: 0.15rem 0.5rem;
            border-radius: 4px;
            background: #e0f2fe;
            color: #0284c7;
            margin-left: 0.5rem;
            display: inline-block;
        }
        .badge-bendahara {
            background: #fef3c7;
            color: #d97706;
        }
        .badge-ppk {
            background: #fce7f3;
            color: #db2777;
        }
    </style>
@endpush

<div class="page-heading" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; animation: slideUp 0.4s ease-out forwards;">
    <div>
        <h2 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin-bottom: 0.25rem;">Pengajuan SPJ</h2>
        <p style="color: #64748b; font-size: 0.95rem;">Isi formulir berikut dengan lengkap untuk mengajukan SPJ baru.</p>
    </div>
    <a href="{{ route('teknis.spj.index') }}" class="spj-btn spj-btn-secondary">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
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
                    <div style="margin-bottom: 0.75rem; display: flex; align-items: center; justify-content: space-between;">
                        <label class="spj-label" style="margin-bottom: 0;">Dokumen Wajib</label>
                        <span style="font-size: 0.75rem; color: #64748b;">Centang berkas fisik yang sudah disiapkan</span>
                    </div>
                    <div id="dokumen_list">
                        <!-- File inputs will be generated here -->
                    </div>
                </div>
            </div>
            <!-- DYNAMIC DROPDOWNS END -->

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
                            <strong>Ajukan Sekarang</strong>
                            <span>Langsung dikirim ke Umum/PPSPM untuk proses verifikasi</span>
                        </div>
                    </label>
                </div>
                @error('status') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border);">
                <a href="{{ route('teknis.spj.index') }}" class="spj-btn spj-btn-secondary">Batal</a>
                <button type="submit" class="spj-btn spj-btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Simpan Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('styles')
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
</script>
@endpush
