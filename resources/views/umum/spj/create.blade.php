@extends('layouts.umum')

@php
$title = 'Pengajuan SPJ Baru';
$subtitle = 'Formulir pengajuan Surat Pertanggungjawaban (SPJ) Bagian Umum.';
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
        .upload-info { flex: 1; }
        .upload-label { font-weight: 600; font-size: 0.95rem; color: var(--dash-text-dark); display: block; margin-bottom: 0.25rem; }
        .upload-desc { font-size: 0.8rem; color: var(--dash-text-muted); }
        .file-status { display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; font-weight: 500; }
        .file-status.uploaded { color: #10b981; }
        .file-status.empty { color: var(--dash-text-muted); }
        .custom-file-upload { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; font-size: 0.85rem; font-weight: 500; color: #0284c7; background: #e0f2fe; border: 1px solid #bae6fd; border-radius: var(--radius-sm); cursor: pointer; transition: var(--transition); }
        .custom-file-upload:hover { background: #bae6fd; }
        .file-input { display: none; }
        .checklist-group { background: var(--dash-card-bg); border: 1px solid var(--dash-border); border-radius: var(--radius-md); padding: 1.25rem; margin-top: 1rem; }
        .checklist-title { font-size: 1rem; font-weight: 600; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem; color: var(--dash-text-dark); }
        .checklist-item { display: flex; align-items: center; padding: 0.6rem 0; border-bottom: 1px dashed var(--dash-border); }
        .checklist-item:last-child { border-bottom: none; }
        .checklist-label { margin-left: 0.75rem; font-size: 0.9rem; color: var(--dash-text-dark); cursor: pointer; flex: 1; }
        .checklist-badge { font-size: 0.75rem; padding: 0.15rem 0.5rem; border-radius: 9999px; background: #f1f5f9; color: #475569; font-weight: 500; }
    </style>
@endpush

<div style="padding-top: 4px;">
    <!-- PAGE HEADING -->
    <div class="dashboard-heading">
        <div>
            <h2 class="dashboard-heading-title">Formulir Pengajuan SPJ Baru</h2>
            <p class="dashboard-heading-subtitle">Isi rincian kegiatan belanja dan siapkan kelengkapan berkas fisik sebelum diajukan.</p>
        </div>
        <div>
            <a href="{{ route('umum.spj.index') }}" class="btn-dash-reset" onclick="clearFormState()">
                <ion-icon name="arrow-back-outline" style="font-size: 16px; vertical-align: middle;"></ion-icon> Kembali ke Antrean
            </a>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="spj-panel" style="padding: 2.25rem 2.5rem; margin-top: 1rem;">
        <form action="{{ route('umum.spj.store') }}" method="POST" id="formPengajuanSpj">
            @csrf

            <!-- 1. IDENTITAS KEGIATAN & ANGGARAN -->
            <div class="spj-section">
                <div class="spj-section-header">
                    <div class="spj-section-title">
                        <span class="spj-step-number">1</span>
                        Identitas Kegiatan &amp; Anggaran
                    </div>
                </div>

                <div class="spj-form-grid">
                    <div>
                        <label class="spj-label">Nomor Registrasi SPJ</label>
                        <input type="text" name="nomor_spj" id="nomor_spj" class="spj-input" value="{{ old('nomor_spj', $nomorSpjOtomatis) }}" readonly style="background: var(--dash-input-bg); cursor: not-allowed; font-weight: 700; color: #0284c7;">
                        <span class="spj-hint">Nomor registrasi SPJ digenerate secara otomatis oleh sistem.</span>
                    </div>

                    <div>
                        <label class="spj-label">Nilai Anggaran / Nominal (Rp) <span style="color: #ef4444;">*</span></label>
                        <input type="number" name="nilai" id="nilai_input" class="spj-input" value="{{ old('nilai') }}" placeholder="Contoh: 1500000" min="0" step="any" required>
                        @error('nilai') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                        <span class="spj-hint" id="terbilang_hint">Ketik nominal dalam bentuk angka tanpa titik atau koma.</span>
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <label class="spj-label">Uraian / Nama Kegiatan Belanja <span style="color: #ef4444;">*</span></label>
                        <textarea name="kegiatan" class="spj-textarea" rows="2" placeholder="Tuliskan uraian kegiatan secara lengkap dan jelas..." required>{{ old('kegiatan') }}</textarea>
                        @error('kegiatan') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="spj-label">Tanggal Pelaksanaan Kegiatan <span style="color: #ef4444;">*</span></label>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                            <div>
                                <span style="display: block; font-size: 0.75rem; color: var(--dash-text-muted); margin-bottom: 0.25rem; font-weight: 500;">Mulai</span>
                                <input type="date" name="tanggal" id="tanggal_mulai" class="spj-input" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            </div>
                            <div>
                                <span style="display: block; font-size: 0.75rem; color: var(--dash-text-muted); margin-bottom: 0.25rem; font-weight: 500;">Sampai (Opsional)</span>
                                <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="spj-input" value="{{ old('tanggal_selesai') }}" min="{{ old('tanggal', date('Y-m-d')) }}">
                            </div>
                        </div>
                        @error('tanggal') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                        @error('tanggal_selesai') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                        <span class="spj-hint">Jika kegiatan berlangsung satu hari, biarkan tanggal selesai kosong.</span>
                    </div>

                    <div>
                        <label class="spj-label">Seksi / Unit Kerja Pengusul <span style="color: #ef4444;">*</span></label>
                        <select name="seksi" class="spj-input" required>
                            <option value="Subbag Umum" {{ old('seksi', 'Subbag Umum') === 'Subbag Umum' ? 'selected' : '' }}>Subbag Umum</option>
                            <option value="Sosial" {{ old('seksi') === 'Sosial' ? 'selected' : '' }}>Seksi Sosial</option>
                            <option value="Produksi" {{ old('seksi') === 'Produksi' ? 'selected' : '' }}>Seksi Produksi</option>
                            <option value="Distribusi" {{ old('seksi') === 'Distribusi' ? 'selected' : '' }}>Seksi Distribusi</option>
                            <option value="Neraca" {{ old('seksi') === 'Neraca' ? 'selected' : '' }}>Seksi Neraca</option>
                            <option value="IPDS" {{ old('seksi') === 'IPDS' ? 'selected' : '' }}>Seksi IPDS</option>
                        </select>
                        <span class="spj-hint">Unit kerja pembebanan anggaran SPJ.</span>
                    </div>

                    <div>
                        <label class="spj-label">Nomor WhatsApp PIC / Pengusul</label>
                        <input type="text" name="no_wa" class="spj-input" value="{{ old('no_wa', Auth::user()->no_wa) }}" placeholder="Contoh: 081234567890">
                        <span class="spj-hint">Nomor WhatsApp aktif untuk menerima pembaruan status SPJ.</span>
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <label class="spj-label">Keterangan Tambahan</label>
                        <textarea name="keterangan" class="spj-textarea" rows="2" placeholder="Catatan internal tambahan untuk verifikator atau bendahara (opsional)...">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- 2. KATEGORI BELANJA & KELENGKAPAN BERKAS FISIK -->
            <div class="spj-section" style="margin-top: 2rem;">
                <div class="spj-section-header">
                    <div class="spj-section-title">
                        <span class="spj-step-number">2</span>
                        Kategori Belanja &amp; Berkas Fisik yang Disiapkan
                    </div>
                </div>

                <div class="spj-form-grid" style="margin-bottom: 1.5rem;">
                    <div>
                        <label class="spj-label">Nama Belanja (Kategori Akun) <span style="color: #ef4444;">*</span></label>
                        <select id="nama_belanja" name="nama_belanja" class="spj-input" required onchange="handleNamaBelanjaChange()">
                            <option value="">-- Pilih Nama Belanja --</option>
                        </select>
                    </div>

                    <div>
                        <label class="spj-label">Jenis Belanja <span style="color: #ef4444;">*</span></label>
                        <select id="jenis_belanja" name="jenis_belanja" class="spj-input" required onchange="handleJenisBelanjaChange()">
                            <option value="">-- Pilih Jenis Belanja --</option>
                        </select>
                    </div>
                </div>

                <div id="dokumen_container" style="display: none;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; flex-wrap: wrap; gap: 0.5rem;">
                        <div>
                            <span style="font-weight: 700; font-size: 0.95rem; color: var(--dash-text-dark);">Dokumen Persyaratan Wajib</span>
                            <p style="font-size: 0.8rem; color: var(--dash-text-muted); margin: 0;">Centang berkas fisik yang sudah disiapkan.</p>
                        </div>
                        <div style="display: flex; gap: 0.5rem;">
                            <button type="button" onclick="checkAllBerkas(true)" class="spj-btn spj-btn-secondary" style="padding: 0.35rem 0.75rem; font-size: 0.8rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><polyline points="20 6 9 17 4 12"></polyline></svg> Centang Semua
                            </button>
                            <button type="button" onclick="checkAllBerkas(false)" class="spj-btn spj-btn-secondary" style="padding: 0.35rem 0.75rem; font-size: 0.8rem;">
                                Batal Semua
                            </button>
                        </div>
                    </div>

                    <div id="dokumen_list" style="display: flex; flex-direction: column; gap: 0.65rem;"></div>
                </div>
            </div>

            <!-- 3. TINDAKAN LANJUTAN & ATURAN WAJIB PRINT -->
            <div class="spj-section" style="margin-top: 2rem;">
                <div class="spj-section-header">
                    <div class="spj-section-title">
                        <span class="spj-step-number">3</span>
                        Tindakan Lanjutan
                    </div>
                </div>

                <div class="spj-radio-grid">
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
                            <strong>Ajukan Sekarang ke Alur SPJ</strong>
                            <span>Simpan pengajuan dan teruskan ke alur pemeriksaan</span>
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

            <!-- BUTTONS -->
            <div style="display: flex; justify-content: flex-end; align-items: center; gap: 1rem; margin-top: 2.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border); flex-wrap: wrap;">
                <a href="{{ route('umum.spj.index') }}" class="spj-btn spj-btn-secondary" onclick="clearFormState()">Batal</a>
                <button type="button" id="btn_cetak_langsung" onclick="cetakLangsung()" class="spj-btn spj-btn-print" title="Cetak langsung lembar checklist berkas fisik SPJ">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 6 2 18 2 18 9"></polyline>
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                        <rect x="6" y="14" width="12" height="8"></rect>
                    </svg>
                    <span id="btn_cetak_text">Print</span>
                </button>
                <button type="submit" id="btn_submit_spj" class="spj-btn spj-btn-primary" style="transition: all 0.25s ease;">
                    <span id="btn_submit_icon" style="display: inline-flex; align-items: center; margin-right: 6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    </span>
                    <span id="btn_submit_text">Simpan Pengajuan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<x-print-validation-modal />

@endsection

@push('scripts')
<script>
    // Data Kategori SPJ (Dinamis dari Database Syarat Dokumen SPJ)
    const spjData = @json($spjData ?? \App\Models\SyaratDokumenSpj::getSpjData());

    const oldNamaBelanja = {!! json_encode(old('nama_belanja', '')) !!};
    const oldJenisBelanja = {!! json_encode(old('jenis_belanja', '')) !!};
    const oldBerkas = {!! json_encode(old('berkas', [])) !!};

    function clearFormState() {
        sessionStorage.removeItem('umum_spj_form_nama_belanja');
        sessionStorage.removeItem('umum_spj_form_jenis_belanja');
    }

    function checkAllBerkas(checked) {
        const checkboxes = document.querySelectorAll('#dokumen_list input[type="checkbox"]:not([disabled])');
        checkboxes.forEach(cb => { cb.checked = checked; });
    }

    let hasPrintedChecklist = {{ old('is_printed', '0') === '1' ? 'true' : 'false' }};

    function updateSubmitButtonState() {
        const statusSelected = document.querySelector('input[name="status"]:checked')?.value;
        const btnSubmit = document.getElementById('btn_submit_spj');
        const btnSubmitText = document.getElementById('btn_submit_text');
        const printHint = document.getElementById('print_requirement_hint');
        const banner = document.getElementById('print_status_banner');
        const printedInput = document.getElementById('is_printed_input');

        if (!btnSubmit) return;

        if (statusSelected === 'diajukan') {
            if (hasPrintedChecklist) {
                btnSubmit.disabled = false;
                btnSubmit.style.background = '#10b981';
                btnSubmit.style.borderColor = '#059669';
                btnSubmit.style.color = '#ffffff';
                btnSubmit.style.cursor = 'pointer';
                btnSubmit.style.opacity = '1';
                if (btnSubmitText) btnSubmitText.innerText = 'Ajukan Sekarang';
                if (printHint) printHint.style.display = 'none';
                if (banner) {
                    banner.style.display = 'block';
                    banner.style.background = '#ecfdf5';
                    banner.style.border = '1px solid #a7f3d0';
                    banner.style.color = '#047857';
                    banner.innerHTML = '<strong>✓ Siap Diajukan:</strong> Lembar checklist fisik telah dicetak. Tombol pengajuan sekarang aktif (hijau).';
                }
                if (printedInput) printedInput.value = '1';
            } else {
                btnSubmit.disabled = true;
                btnSubmit.style.background = '#94a3b8';
                btnSubmit.style.borderColor = '#94a3b8';
                btnSubmit.style.color = '#f8fafc';
                btnSubmit.style.cursor = 'not-allowed';
                btnSubmit.style.opacity = '0.75';
                if (btnSubmitText) btnSubmitText.innerText = 'Cetak Dulu Sebelum Mengajukan';
                if (printHint) printHint.style.display = 'block';
                if (banner) banner.style.display = 'none';
                if (printedInput) printedInput.value = '0';
            }
        } else {
            btnSubmit.disabled = false;
            btnSubmit.style.background = '';
            btnSubmit.style.borderColor = '';
            btnSubmit.style.color = '';
            btnSubmit.style.cursor = 'pointer';
            btnSubmit.style.opacity = '1';
            if (btnSubmitText) btnSubmitText.innerText = 'Simpan sebagai Draft';
            if (printHint) printHint.style.display = 'none';
            if (banner) banner.style.display = 'none';
        }
    }

    function cetakLangsung() {
        const form = document.getElementById("formPengajuanSpj");
        const missingFields = [];

        const uraianVal = form.querySelector('[name="kegiatan"]')?.value?.trim();
        const nilaiVal = form.querySelector('[name="nilai"]')?.value?.trim();
        const tglVal = form.querySelector('[name="tanggal"]')?.value?.trim();
        const namaBelanjaVal = document.getElementById("nama_belanja")?.value?.trim();
        const jenisBelanjaVal = document.getElementById("jenis_belanja")?.value?.trim();

        if (!uraianVal) missingFields.push("Uraian / Nama Kegiatan");
        if (!nilaiVal) missingFields.push("Nilai Anggaran (Nomor 1)");
        if (!tglVal) missingFields.push("Tanggal Pelaksanaan (Mulai)");
        if (!namaBelanjaVal) missingFields.push("Nama Belanja (Nomor 2)");
        if (!jenisBelanjaVal) missingFields.push("Jenis Belanja (Nomor 2)");

        if (missingFields.length > 0) {
            if (typeof window.showPrintValidationModal === 'function') {
                window.showPrintValidationModal(missingFields);
            } else {
                alert("Harap lengkapi field berikut sebelum mencetak:\n- " + missingFields.join("\n- "));
            }
            return;
        }

        const nomorSpj = document.getElementById("nomor_spj")?.value || "-";
        const kegiatan = uraianVal;
        const nilaiNum = parseFloat(nilaiVal) || 0;
        const nilaiFormatted = "Rp " + nilaiNum.toLocaleString("id-ID");
        const tglMulai = tglVal;
        const tglSelesai = document.getElementById("tanggal_selesai")?.value?.trim() || "";
        const tanggalStr = tglSelesai && tglSelesai !== tglMulai ? tglMulai + " s.d. " + tglSelesai : tglMulai;
        const seksi = form.querySelector('[name="seksi"]')?.value || "Subbag Umum";

        const docs = [];
        const rows = document.querySelectorAll("#dokumen_list > div");
        rows.forEach(r => {
            const label = r.querySelector("label span:first-child")?.innerText || "";
            const isChecked = r.querySelector('input[type="checkbox"]')?.checked;
            const isBendahara = r.innerText.includes("Dilengkapi Bendahara");
            docs.push({ name: label, isChecked: !!isChecked, isBendahara: isBendahara });
        });

        let rowsHtml = "";
        docs.forEach((d, idx) => {
            const statusBadge = d.isBendahara
                ? '<span style="color:#d97706; font-size:11px; font-weight:700;">[ Dilengkapi Bendahara ]</span>'
                : (d.isChecked
                    ? '<span style="color:#15803d; font-size:11px; font-weight:700;">[ ✓ Ada (Fisik) ]</span>'
                    : '<span style="color:#94a3b8; font-size:11px;">[ - Belum ]</span>');
            rowsHtml += `<tr>
                <td style="border:1px solid #cbd5e1; padding:6px 10px; text-align:center; width:35px;">${idx + 1}</td>
                <td style="border:1px solid #cbd5e1; padding:6px 10px;">${d.name}</td>
                <td style="border:1px solid #cbd5e1; padding:6px 10px; text-align:center; width:170px;">${statusBadge}</td>
            </tr>`;
        });

        const printWindow = window.open("", "_blank");
        if (!printWindow) {
            alert("Popup window diblokir oleh browser. Harap izinkan popup untuk situs ini.");
            return;
        }

        printWindow.document.write(`<!DOCTYPE html>
        <html>
        <head>
            <title>Lembar Kendali SPJ - ${nomorSpj}</title>
            <style>
                body { font-family: Arial, sans-serif; font-size: 11pt; color: #1e293b; padding: 20px; }
                h2 { margin: 0 0 4px 0; font-size: 14pt; color: #082052; text-align: center; }
                p.sub { margin: 0 0 16px 0; font-size: 10pt; color: #64748b; text-align: center; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 10pt; }
                th { background: #f1f5f9; border: 1px solid #cbd5e1; padding: 7px 10px; }
                td { vertical-align: top; }
                .meta-table td { padding: 4px 6px; }
            </style>
        </head>
        <body>
            <h2>BADAN PUSAT STATISTIK KABUPATEN KOLAKA UTARA</h2>
            <p class="sub">LEMBAR KENDALI KELENGKAPAN BERKAS FISIK SPJ</p>
            <table class="meta-table" style="margin-bottom: 14px; border: none;">
                <tr><td style="width: 140px; font-weight: bold;">Nomor SPJ</td><td>: ${nomorSpj}</td></tr>
                <tr><td style="font-weight: bold;">Uraian Kegiatan</td><td>: ${kegiatan}</td></tr>
                <tr><td style="font-weight: bold;">Unit Kerja / Seksi</td><td>: ${seksi}</td></tr>
                <tr><td style="font-weight: bold;">Nilai Anggaran</td><td>: ${nilaiFormatted}</td></tr>
                <tr><td style="font-weight: bold;">Tanggal Pelaksanaan</td><td>: ${tanggalStr}</td></tr>
                <tr><td style="font-weight: bold;">Nama Belanja</td><td>: ${namaBelanjaVal}</td></tr>
                <tr><td style="font-weight: bold;">Jenis Belanja</td><td>: ${jenisBelanjaVal}</td></tr>
            </table>
            <table>
                <thead>
                    <tr><th>No</th><th>Nama Dokumen Persyaratan</th><th>Status Fisik</th></tr>
                </thead>
                <tbody>${rowsHtml}</tbody>
            </table>
        </body>
        </html>`);
        printWindow.document.close();
        printWindow.focus();
        setTimeout(() => {
            printWindow.print();
            hasPrintedChecklist = true;
            updateSubmitButtonState();
        }, 350);
    }

    function initNamaBelanja() {
        const selNamaBelanja = document.getElementById("nama_belanja");
        if (!selNamaBelanja) return;

        selNamaBelanja.innerHTML = '<option value="">-- Pilih Nama Belanja --</option>';
        Object.keys(spjData).forEach(nama => {
            const opt = document.createElement("option");
            opt.value = nama;
            opt.textContent = nama;
            selNamaBelanja.appendChild(opt);
        });

        const activeNama = oldNamaBelanja || sessionStorage.getItem('umum_spj_form_nama_belanja') || '';
        if (activeNama && spjData[activeNama]) {
            selNamaBelanja.value = activeNama;
            handleNamaBelanjaChange(true);
        }
    }

    function handleNamaBelanjaChange(isInitial = false) {
        const nama = document.getElementById("nama_belanja").value;
        const selJenisBelanja = document.getElementById("jenis_belanja");
        const docContainer = document.getElementById("dokumen_container");

        sessionStorage.setItem('umum_spj_form_nama_belanja', nama);

        selJenisBelanja.innerHTML = '<option value="">-- Pilih Jenis Belanja --</option>';
        docContainer.style.display = "none";

        if (nama && spjData[nama]) {
            Object.keys(spjData[nama]).forEach(jenis => {
                const opt = document.createElement("option");
                opt.value = jenis;
                opt.textContent = jenis;
                selJenisBelanja.appendChild(opt);
            });

            const activeJenis = (isInitial ? oldJenisBelanja : '') || sessionStorage.getItem('umum_spj_form_jenis_belanja') || '';
            if (activeJenis && spjData[nama][activeJenis]) {
                selJenisBelanja.value = activeJenis;
                handleJenisBelanjaChange(isInitial);
            }
        }
    }

    function handleJenisBelanjaChange(isInitial = false) {
        const nama = document.getElementById("nama_belanja").value;
        const jenis = document.getElementById("jenis_belanja").value;
        const docContainer = document.getElementById("dokumen_container");
        const docList = document.getElementById("dokumen_list");

        sessionStorage.setItem('umum_spj_form_jenis_belanja', jenis);

        if (nama && jenis && spjData[nama] && spjData[nama][jenis]) {
            const items = spjData[nama][jenis];
            docList.innerHTML = "";

            items.forEach((item, index) => {
                const row = document.createElement("div");
                row.style.display = "flex";
                row.style.alignItems = "center";
                row.style.justifyContent = "space-between";
                row.style.padding = "0.75rem 1rem";
                row.style.background = "var(--dash-card-bg)";
                row.style.border = "1px solid var(--dash-border)";
                row.style.borderRadius = "var(--radius-md)";

                const isBendahara = (item.assignee === 'Bendahara') || (item.name.toLowerCase().includes('pph 22') && !item.name.toLowerCase().includes('ppn'));

                let isChecked = false;
                if (isInitial && oldBerkas[index]) isChecked = true;
                else if (!isBendahara) isChecked = true;

                row.innerHTML = `
                    <div style="display: flex; align-items: center; gap: 0.75rem; flex: 1;">
                        <input type="hidden" name="nama_dokumen[${index}]" value="${item.name}">
                        <input type="hidden" name="assignee_dokumen[${index}]" value="${item.assignee || 'SM'}">
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; flex: 1; margin: 0;">
                            <span style="font-weight: 600; font-size: 0.9rem; color: var(--dash-text-dark);">${item.name}</span>
                            ${isBendahara
                                ? '<span style="font-size: 0.75rem; padding: 2px 8px; border-radius: 9999px; background: #fffbeb; color: #b45309; border: 1px solid #fde68a; font-weight: 600;">Dilengkapi Bendahara</span>'
                                : '<span style="font-size: 0.75rem; padding: 2px 8px; border-radius: 9999px; background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; font-weight: 500;">Wajib Disiapkan</span>'
                            }
                        </label>
                    </div>
                    <div>
                        ${isBendahara
                            ? '<span style="font-size: 0.8rem; color: #94a3b8; font-style: italic;">Tidak perlu (Diurus Bendahara)</span>'
                            : `<label style="display: inline-flex; align-items: center; gap: 6px; cursor: pointer; font-size: 0.85rem; font-weight: 600; color: #0284c7;">
                                   <input type="checkbox" name="berkas[${index}]" value="1" ${isChecked ? 'checked' : ''} style="width: 16px; height: 16px; accent-color: #0284c7;">
                                   Ada (Fisik)
                               </label>`
                        }
                    </div>
                `;
                docList.appendChild(row);
            });

            docContainer.style.display = "block";
        } else {
            docContainer.style.display = "none";
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        initNamaBelanja();

        const radioStatus = document.querySelectorAll('input[name="status"]');
        radioStatus.forEach(r => {
            r.addEventListener('change', updateSubmitButtonState);
        });

        updateSubmitButtonState();
    });
</script>
@endpush
