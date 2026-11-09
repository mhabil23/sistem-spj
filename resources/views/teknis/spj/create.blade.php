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
                    <div style="margin-bottom: 0.75rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                        <div>
                            <label class="spj-label" style="margin-bottom: 0;">Dokumen Wajib</label>
                            <span style="font-size: 0.75rem; color: #64748b; display: block;">Centang berkas fisik yang sudah disiapkan</span>
                        </div>
                        <div style="display: flex; gap: 0.5rem;">
                            <button type="button" onclick="centangSemuaBerkas(true)" class="spj-btn spj-btn-secondary" style="padding: 0.25rem 0.65rem; font-size: 0.75rem; height: auto;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 2px;"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                Centang Semua
                            </button>
                            <button type="button" onclick="centangSemuaBerkas(false)" class="spj-btn spj-btn-secondary" style="padding: 0.25rem 0.65rem; font-size: 0.75rem; height: auto; opacity: 0.8;">
                                Batal Semua
                            </button>
                        </div>
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
                    <label class="spj-label">
                        Tanggal Mulai Kegiatan <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="date" name="tanggal" id="tanggal_mulai_input" required class="spj-input @error('tanggal') has-error @enderror" value="{{ old('tanggal') }}">
                    <span style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem; display: block;">Tanggal dimulainya pelaksanaan kegiatan.</span>
                    @error('tanggal') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="spj-label" style="display: flex; justify-content: space-between; align-items: center;">
                        <span>Tanggal Selesai Kegiatan</span>
                        <span style="font-size: 0.72rem; font-weight: normal; color: #64748b; background: #f1f5f9; padding: 2px 7px; border-radius: 4px;">Opsional</span>
                    </label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai_input" class="spj-input @error('tanggal_selesai') has-error @enderror" value="{{ old('tanggal_selesai') }}">
                    <span style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem; display: block;">Kosongkan jika kegiatan hanya berlangsung 1 hari.</span>
                    @error('tanggal_selesai') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>

                <input type="hidden" name="seksi" id="seksi_input" value="{{ old('seksi', Auth::user()->seksi ?: Auth::user()->name) }}">

                <div style="grid-column: span 2;">
                    <label class="spj-label">Nomor WhatsApp Pengaju / PIC</label>
                    <input type="text" name="no_wa" class="spj-input" value="{{ old('no_wa', Auth::user()->no_wa ?? '') }}" placeholder="Contoh: 081234567890">
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
                <a href="{{ route('teknis.spj.index') }}" class="spj-btn spj-btn-secondary" onclick="clearFormState()">Batal</a>
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
        sessionStorage.removeItem('spj_form_nama_belanja');
        sessionStorage.removeItem('spj_form_jenis_belanja');
        sessionStorage.removeItem('spj_form_berkas');
    }

    function saveBerkasState() {
        const checkboxes = document.querySelectorAll('input[name^="berkas["]');
        const state = {};
        checkboxes.forEach(cb => {
            const match = cb.name.match(/\[(\d+)\]/);
            if (match) {
                state[match[1]] = cb.checked ? 1 : 0;
            }
        });
        sessionStorage.setItem('spj_form_berkas', JSON.stringify(state));
    }

    function centangSemuaBerkas(val = true) {
        const checkboxes = document.querySelectorAll('input[name^="berkas["]');
        checkboxes.forEach(cb => {
            cb.checked = val;
        });
        saveBerkasState();
    }

    function renderDokumenList(nama, jenis, savedCheckboxes = null) {
        const docContainer = document.getElementById("dokumen_container");
        const docList = document.getElementById("dokumen_list");
        if (!docContainer || !docList) return;

        docList.innerHTML = "";

        if (nama && jenis && spjData[nama] && spjData[nama][jenis]) {
            docContainer.style.display = "block";
            const docs = spjData[nama][jenis];
            
            docs.forEach((doc, idx) => {
                let badgeHtml = '';
                let uploadHtml = '';
                
                let isChecked = false;
                if (savedCheckboxes) {
                    isChecked = savedCheckboxes[idx] == 1 || savedCheckboxes[idx] === true || savedCheckboxes[idx] === '1';
                }

                // If it is supposed to be uploaded by SM/Panitia (Teknis)
                if(doc.assignee.includes('SM') || doc.assignee.includes('Panitia')) {
                    badgeHtml = `<span class="badge-assignee">Wajib Disiapkan Teknis</span>`;
                    uploadHtml = `
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" name="berkas[${idx}]" value="1" ${isChecked ? 'checked' : ''} onchange="saveBerkasState()" style="width: 1.25rem; height: 1.25rem; accent-color: var(--primary);" required>
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
                        <input type="hidden" name="assignee_dokumen[${idx}]" value="${doc.assignee}">
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

    document.addEventListener("DOMContentLoaded", function() {
        const selNamaBelanja = document.getElementById("nama_belanja");
        const selJenisBelanja = document.getElementById("jenis_belanja");
        
        // Populate first dropdown
        if (selNamaBelanja) {
            for (const nama in spjData) {
                const opt = document.createElement("option");
                opt.value = nama;
                opt.textContent = nama;
                selNamaBelanja.appendChild(opt);
            }
        }

        // Pulihkan pilihan form yang sudah dipilih agar tidak reset
        const activeNama = oldNamaBelanja || sessionStorage.getItem('spj_form_nama_belanja') || '';
        const activeJenis = oldJenisBelanja || sessionStorage.getItem('spj_form_jenis_belanja') || '';
        let activeBerkas = (oldBerkas && Object.keys(oldBerkas).length > 0)
            ? oldBerkas
            : JSON.parse(sessionStorage.getItem('spj_form_berkas') || '{}');

        if (activeNama && spjData[activeNama] && selNamaBelanja) {
            selNamaBelanja.value = activeNama;

            if (selJenisBelanja) {
                selJenisBelanja.innerHTML = '<option value="" disabled>Pilih Jenis Belanja...</option>';
                selJenisBelanja.disabled = false;
                for (const jenis in spjData[activeNama]) {
                    const opt = document.createElement("option");
                    opt.value = jenis;
                    opt.textContent = jenis;
                    if (jenis === activeJenis) {
                        opt.selected = true;
                    }
                    selJenisBelanja.appendChild(opt);
                }
            }

            if (activeJenis && spjData[activeNama][activeJenis]) {
                renderDokumenList(activeNama, activeJenis, activeBerkas);
            }
        }
    });

    function handleNamaBelanjaChange() {
        resetPrintStatus();
        const nama = document.getElementById("nama_belanja").value;
        const selJenisBelanja = document.getElementById("jenis_belanja");
        const docContainer = document.getElementById("dokumen_container");
        
        sessionStorage.setItem('spj_form_nama_belanja', nama);
        sessionStorage.removeItem('spj_form_jenis_belanja');
        sessionStorage.removeItem('spj_form_berkas');

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

        sessionStorage.setItem('spj_form_jenis_belanja', jenis);
        renderDokumenList(nama, jenis);
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
        const tanggalSelesai = document.querySelector('[name="tanggal_selesai"]')?.value || '';
        const nilai = document.querySelector('[name="nilai"]')?.value || '0';
        const noWa = document.querySelector('[name="no_wa"]')?.value.trim() || '—';
        const keterangan = document.querySelector('[name="keterangan"]')?.value.trim() || '—';
        const pengajuName = "{{ Auth::user()->name ?? 'Staf Teknis' }}";
        const logoUrl = "{{ asset('images/logo-bps.png') }}";
        const tanggalCetak = new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        const tanggalFormatted = formatDateIndo(tanggal) || tanggalCetak;
        const periodeKegiatanFormatted = (tanggal && tanggalSelesai && tanggalSelesai !== tanggal)
            ? (formatDateIndo(tanggal) + ' s.d. ' + formatDateIndo(tanggalSelesai))
            : tanggalFormatted;

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
            + '        @media print {\n'
            + '            .no-print { display: none !important; }\n'
            + '            .print-wrapper { padding: 0 !important; }\n'
            + '        }\n'
            + '        .no-print {\n'
            + '            position: sticky;\n'
            + '            top: 0;\n'
            + '            left: 0;\n'
            + '            right: 0;\n'
            + '            background: #0f172a;\n'
            + '            color: #ffffff;\n'
            + '            padding: 12px 20px;\n'
            + '            display: flex;\n'
            + '            justify-content: space-between;\n'
            + '            align-items: center;\n'
            + '            box-shadow: 0 4px 12px rgba(0,0,0,0.15);\n'
            + '            z-index: 99999;\n'
            + '            margin-bottom: 20px;\n'
            + '            font-family: Arial, sans-serif;\n'
            + '        }\n'
            + '    <\/style>\n'
            + '<\/head>\n'
            + '<body>\n'
            + '    <div class="no-print">\n'
            + '        <div style="display: flex; align-items: center; gap: 8px;">\n'
            + '            <strong style="font-size: 13px;">📄 Pratinjau Lembar Checklist Berkas Fisik SPJ</strong>\n'
            + '            <span style="background: rgba(255,255,255,0.15); padding: 2px 8px; border-radius: 4px; font-size: 11px;">Tab Baru</span>\n'
            + '        </div>\n'
            + '        <div style="display: flex; gap: 10px;">\n'
            + '            <button type="button" onclick="window.print()" style="background: #0284c7; color: #ffffff; border: none; padding: 7px 16px; border-radius: 6px; font-size: 12px; font-weight: bold; cursor: pointer;">🖨️ Cetak Lembar Checklist</button>\n'
            + '            <button type="button" onclick="window.close()" style="background: #475569; color: #ffffff; border: none; padding: 7px 14px; border-radius: 6px; font-size: 12px; cursor: pointer;">✕ Tutup Tab</button>\n'
            + '        </div>\n'
            + '    </div>\n'
            + '    <div class="print-wrapper" style="padding: 10px 20px;">\n'
            + '        <div class="print-only-logo">\n'
            + '            <img src="' + logoUrl + '" alt="Logo BPS" onerror="this.style.display=\'none\'">\n'
            + '            <h2>LAPORAN REKAPITULASI SPJ<\/h2>\n'
            + '            <p>Badan Pusat Statistik Kabupaten Kolaka Utara<\/p>\n'
            + '        <\/div>\n'
            + '        <div class="meta-info">\n'
            + '            <div>No. SPJ: <strong>' + nomorSpj + '</strong> | Periode Kegiatan: <strong>' + periodeKegiatanFormatted + '</strong> | Staf Pengaju / PIC: <strong>' + pengajuName + '<\/strong> ' + (noWa !== '—' ? '(' + noWa + ')' : '') + '<\/div>\n'
            + '            <div>Belanja: <strong>' + namaBelanja + ' (' + jenisBelanja + ')<\/strong><\/div>\n'
            + '        <\/div>\n'
            + '        <div class="table-responsive">\n'
            + '            <table class="cetak-table">\n'
            + '                <thead>\n'
            + '                    <tr>\n'
            + '                        <th rowspan="2" style="width: 30px;">No<\/th>\n'
            + '                        <th rowspan="2" style="min-width: 170px;">Nama Kegiatan<\/th>\n'
            + '                        <th rowspan="2" style="min-width: 95px;">Jumlah Anggaran<\/th>\n'
            + '                        <th rowspan="2" style="min-width: 80px;">Tanggal Penginputan SPJ<\/th>\n'
            + '                        <th colspan="' + totalKelengkapanColspan + '">Kelengkapan Administrasi<\/th>\n'
            + '                        <th rowspan="2" style="min-width: 80px;">Keterangan<\/th>\n'
            + '                    <\/tr>\n'
            + '                    <tr>\n'
            + docHeadersHtml
            + '                        <th style="min-width: 65px;">Tgl Masuk SPJ<\/th>\n'
            + '                        <th style="min-width: 65px;">Tgl Periksa PPK<\/th>\n'
            + '                        <th style="min-width: 45px;">Paraf PPSPM<\/th>\n'
            + '                        <th style="min-width: 45px;">Paraf Bendahara<\/th>\n'
            + '                    <\/tr>\n'
            + '                <\/thead>\n'
            + '                <tbody>\n'
            + '                    <tr>\n'
            + '                        <td>1<\/td>\n'
            + '                        <td style="text-align: left; padding: 5px 6px;">' + kegiatan + '<\/td>\n'
            + '                        <td style="text-align: right; white-space: nowrap; font-weight: bold; padding: 5px 6px;">Rp. ' + formatRupiah(nilai) + '<\/td>\n'
            + '                        <td>' + tanggalCetak + '<\/td>\n'
            + docValuesHtml
            + '                        <td>-<\/td>\n'
            + '                        <td>-<\/td>\n'
            + '                        <td><\/td>\n'
            + '                        <td><\/td>\n'
            + '                        <td style="text-align: left; padding: 5px 6px;">' + (keterangan !== '—' ? keterangan : '') + '<\/td>\n'
            + '                    <\/tr>\n'
            + '                <\/tbody>\n'
            + '                <tfoot>\n'
            + '                    <tr>\n'
            + '                        <th colspan="2" style="text-align: right; padding-right: 15px;">TOTAL<\/th>\n'
            + '                        <th style="text-align: right; white-space: nowrap; font-weight: bold; padding: 5px 6px;">Rp. ' + formatRupiah(nilai) + '<\/th>\n'
            + '                        <th colspan="' + (totalKelengkapanColspan + 2) + '"><\/th>\n'
            + '                    <\/tr>\n'
            + '                <\/tfoot>\n'
            + '            <\/table>\n'
            + '        <\/div>\n'
            + '        <div class="ttd-container">\n'
            + '            <div>\n'
            + '                <p style="margin: 0;">setor ke PPSPM :<\/p>\n'
            + '                <p style="margin: 0;">Lasusua, ' + tanggalCetak + '<\/p>\n'
            + '                <p style="margin: 0;">Pejabat Pembuat Komitmen<\/p>\n'
            + '                <br><br><br><br>\n'
            + '                <p style="margin: 0; text-decoration: underline; font-weight: bold;">Hidayatullah<\/p>\n'
            + '                <p style="margin: 0;">NIP.198510292011011009<\/p>\n'
            + '            <\/div>\n'
            + '        <\/div>\n'
            + '    <\/div>\n'
            + '<\/body>\n'
            + '<\/html>';

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
                    <span><strong>Checklist Telah Dicetak:</strong> Lembar checklist SPJ telah dicetak di tab baru. Tombol pengajuan sekarang aktif berwarna hijau!</span>
                `;
            }

            const hint = document.getElementById('print_requirement_hint');
            if (hint) {
                hint.style.display = 'none';
            }

            updateSubmitButtonState();
        };

        // Buka proses cetak di TAB BARU (bukan di tab yang sama)
        const printWin = window.open('', '_blank');
        if (!printWin) {
            alert("Gagal membuka tab baru untuk mencetak. Pastikan peramban (browser) Anda mengizinkan pop-up.");
            return;
        }

        printWin.document.open();
        printWin.document.write(printHtml);
        printWin.document.close();

        // Tandai langsung di tab form bahwa checklist sudah dicetak
        markAsPrinted();

        try {
            printWin.addEventListener('afterprint', markAsPrinted, { once: true });
        } catch(e) {}

        setTimeout(function() {
            try {
                printWin.focus();
                printWin.print();
            } catch(e) {}
        }, 350);
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
                <span id="btn_cetak_text">Print</span>
            `;
        }

        const banner = document.getElementById('print_status_banner');
        if (banner) {
            banner.style.display = 'none';
        }

        updatePrintNotice();
        updateSubmitButtonState();
    }

    function updateSubmitButtonState() {
        const selectedStatus = document.querySelector('input[name="status"]:checked')?.value || 'draft';
        const submitBtn = document.getElementById('btn_submit_spj');
        const submitIcon = document.getElementById('btn_submit_icon');
        const submitText = document.getElementById('btn_submit_text');
        const hint = document.getElementById('print_requirement_hint');

        if (!submitBtn) return;

        if (selectedStatus === 'diajukan') {
            if (isPrinted) {
                // STATUS DIAJUKAN & SUDAH PRINT -> AKTIF & WARNA HIJAU
                submitBtn.disabled = false;
                submitBtn.style.backgroundColor = '#059669'; // Emerald Green
                submitBtn.style.borderColor = '#047857';
                submitBtn.style.color = '#ffffff';
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
                submitBtn.style.boxShadow = '0 4px 14px rgba(5, 150, 105, 0.4)';
                submitBtn.title = 'Lembar checklist telah dicetak. Klik untuk mengajukan SPJ ke Umum.';
                
                if (submitIcon) {
                    submitIcon.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    `;
                }
                if (submitText) {
                    submitText.textContent = 'Ajukan & Bawa Berkas Fisik ke Umum';
                }
                if (hint) {
                    hint.style.display = 'none';
                }
            } else {
                // STATUS DIAJUKAN & BELUM PRINT -> TIDAK AKTIF DULU (ABU-ABU)
                submitBtn.disabled = false; // Tetap bisa diklik agar memicu popup modal penjelasan
                submitBtn.style.backgroundColor = '#94a3b8'; // Slate 400
                submitBtn.style.borderColor = '#94a3b8';
                submitBtn.style.color = '#ffffff';
                submitBtn.style.opacity = '0.75';
                submitBtn.style.cursor = 'not-allowed';
                submitBtn.style.boxShadow = 'none';
                submitBtn.title = 'Wajib menekan tombol Print terlebih dahulu sebelum pengajuan dapat disimpan.';

                if (submitIcon) {
                    submitIcon.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    `;
                }
                if (submitText) {
                    submitText.textContent = 'Ajukan SPJ (Wajib Print Dulu)';
                }
                if (hint) {
                    hint.style.display = 'block';
                }
            }
        } else {
            // STATUS DRAFT -> BIRU STANDAR
            submitBtn.disabled = false;
            submitBtn.style.backgroundColor = 'var(--primary)';
            submitBtn.style.borderColor = 'var(--primary)';
            submitBtn.style.color = '#ffffff';
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
            submitBtn.style.boxShadow = 'none';
            submitBtn.title = 'Simpan draft SPJ untuk diedit kembali nanti.';

            if (submitIcon) {
                submitIcon.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                `;
            }
            if (submitText) {
                submitText.textContent = 'Simpan sebagai Draft';
            }
            if (hint) {
                hint.style.display = 'none';
            }
        }
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
        updateSubmitButtonState();
    }

    document.addEventListener("DOMContentLoaded", function() {
        const statusRadios = document.querySelectorAll('input[name="status"]');
        statusRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                updatePrintNotice();
                updateSubmitButtonState();
            });
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
        updateSubmitButtonState();

        // Sinkronisasi rentang tanggal (mulai & selesai)
        const inputTglMulai = document.getElementById('tanggal_mulai_input');
        const inputTglSelesai = document.getElementById('tanggal_selesai_input');
        if (inputTglMulai && inputTglSelesai) {
            function syncTanggalMin() {
                if (inputTglMulai.value) {
                    inputTglSelesai.min = inputTglMulai.value;
                    if (inputTglSelesai.value && inputTglSelesai.value < inputTglMulai.value) {
                        inputTglSelesai.value = inputTglMulai.value;
                    }
                }
            }
            inputTglMulai.addEventListener('change', syncTanggalMin);
            syncTanggalMin();
        }

        // Intercept klik pada tombol submit jika status diajukan dan belum print -> langsung POP-UP MODAL!
        const submitBtn = document.getElementById('btn_submit_spj');
        if (submitBtn) {
            submitBtn.addEventListener('click', function(e) {
                const selectedStatus = document.querySelector('input[name="status"]:checked')?.value || 'draft';
                if (selectedStatus === 'diajukan' && !isPrinted) {
                    e.preventDefault();
                    e.stopPropagation();

                    const printBtn = document.getElementById('btn_cetak_langsung');
                    if (printBtn) {
                        printBtn.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        printBtn.classList.remove('print-btn-shake');
                        void printBtn.offsetWidth;
                        printBtn.classList.add('print-btn-shake');
                    }

                    // TAMPILKAN MODAL POP-UP PERINGATAN PRINT
                    openPrintValidationModal();
                    return false;
                }
            });
        }

        const form = document.querySelector('form[action="{{ route('teknis.spj.store') }}"]') || document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const selectedStatus = document.querySelector('input[name="status"]:checked')?.value || 'draft';
                if (selectedStatus === 'diajukan' && !isPrinted) {
                    e.preventDefault();
                    e.stopPropagation();

                    const printBtn = document.getElementById('btn_cetak_langsung');
                    if (printBtn) {
                        printBtn.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        printBtn.classList.remove('print-btn-shake');
                        void printBtn.offsetWidth;
                        printBtn.classList.add('print-btn-shake');
                    }

                    openPrintValidationModal();
                    return false;
                }

                // Sukses diajukan / disimpan: bersihkan storage
                clearFormState();
            });
        }

        @if($errors->has('status') && str_contains($errors->first('status'), 'Print'))
            setTimeout(function() {
                openPrintValidationModal();
            }, 250);
        @endif
    });
</script>
@endpush
