@extends('layouts.admin')

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
    <a href="{{ route('admin.spj.index') }}" class="spj-btn spj-btn-secondary">
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
        <form action="{{ route('admin.spj.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            


            <!-- PERUNTUKAN SPJ (TEKNIS / UMUM) -->
            <div style="padding: 1.25rem 1.5rem; background: #ffffff; border-radius: var(--radius-md); border: 1.5px solid #e2e8f0; margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem;">
                    <div>
                        <label class="spj-label" style="font-size: 1rem; font-weight: 700; color: #0f172a; margin-bottom: 0.15rem;">
                            Peruntukan Pengajuan SPJ <span style="color: #ef4444;">*</span>
                        </label>
                        <span style="font-size: 0.8rem; color: #64748b;">Pilih jenis alur pengajuan: untuk Tim Teknis (kegiatan sensus/survei) atau Subbagian Umum (belanja pegawai & operasional kantor).</span>
                    </div>
                </div>

                <div class="spj-radio-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <label class="spj-radio-card" id="card_peruntukan_teknis" style="cursor: pointer; border: 2px solid {{ old('peruntukan', 'teknis') === 'teknis' ? '#0284c7' : 'var(--border)' }}; background: {{ old('peruntukan', 'teknis') === 'teknis' ? '#f0f9ff' : '#ffffff' }}; padding: 1rem; border-radius: 8px; transition: all 0.2s ease;">
                        <input type="radio" name="peruntukan" value="teknis" {{ old('peruntukan', 'teknis') === 'teknis' ? 'checked' : '' }} onchange="handlePeruntukanChange(this.value)">
                        <div class="spj-radio-content" style="margin-left: 0.5rem;">
                            <strong style="color: #0369a1; font-size: 0.95rem;">SPJ Tim Teknis</strong>
                            <span style="display: block; font-size: 0.8rem; color: #64748b; margin-top: 2px;">Kegiatan sensus, survei lapangan, pelatihan & belanja seksi teknis</span>
                        </div>
                    </label>

                    <label class="spj-radio-card" id="card_peruntukan_umum" style="cursor: pointer; border: 2px solid {{ old('peruntukan') === 'umum' ? '#0284c7' : 'var(--border)' }}; background: {{ old('peruntukan') === 'umum' ? '#f0f9ff' : '#ffffff' }}; padding: 1rem; border-radius: 8px; transition: all 0.2s ease;">
                        <input type="radio" name="peruntukan" value="umum" {{ old('peruntukan') === 'umum' ? 'checked' : '' }} onchange="handlePeruntukanChange(this.value)">
                        <div class="spj-radio-content" style="margin-left: 0.5rem;">
                            <strong style="color: #0f172a; font-size: 0.95rem;">SPJ Subbagian Umum</strong>
                            <span style="display: block; font-size: 0.8rem; color: #64748b; margin-top: 2px;">Belanja Pegawai (Gaji, Tukin, Uang Makan, Lembur) & keperluan umum kantor</span>
                        </div>
                    </label>
                </div>
                @error('peruntukan') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
            </div>

            <!-- DYNAMIC DROPDOWNS START -->
            <div style="padding: 1.5rem; background: linear-gradient(135deg, #f8fafc, #f1f5f9); border-radius: var(--radius-md); border: 1px solid var(--border); margin-bottom: 1.5rem;">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem; color: #0f172a;">Kategori &amp; Kelengkapan Berkas</h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label class="spj-label" for="nama_belanja">Nama Belanja (Kategori Akun) <span style="color: #ef4444;">*</span></label>
                        <select id="nama_belanja" name="nama_belanja" class="spj-input" required onchange="handleNamaBelanjaChange()">
                            <option value="" disabled selected>-- Pilih Nama Belanja --</option>
                        </select>
                    </div>

                    <div>
                        <label class="spj-label" for="jenis_belanja">Jenis Belanja <span style="color: #ef4444;">*</span></label>
                        <select id="jenis_belanja" name="jenis_belanja" class="spj-input" required disabled onchange="handleJenisBelanjaChange()">
                            <option value="" disabled selected>-- Pilih Jenis Belanja --</option>
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
                    <label class="spj-label" for="nilai_display">Nilai (Rp) <span style="color: #ef4444;">*</span></label>
                    <div class="spj-input-prefix-group">
                        <span class="spj-input-prefix">Rp</span>
                        <input type="text" inputmode="numeric" id="nilai_display" class="spj-input spj-input-with-prefix @error('nilai') has-error @enderror" value="{{ old('nilai') }}" placeholder="Contoh: 1.500.000" autocomplete="off" required>
                    </div>
                    <input type="hidden" name="nilai" id="nilai_input" value="{{ old('nilai') }}">
                    <span class="spj-field-hint" id="nilai_terbilang_hint">Pemisah ribuan (titik) ditambahkan otomatis saat mengetik.</span>
                    @error('nilai') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;" class="spj-form-group">
                <div style="grid-column: span 2;">
                    <label class="spj-label" for="kegiatan_input">Uraian / Nama Kegiatan Belanja <span style="color: #ef4444;">*</span></label>
                    <textarea name="kegiatan" required class="spj-input spj-textarea" placeholder="Tuliskan uraian kegiatan secara lengkap dan jelas..." id="kegiatan_input">{{ old('kegiatan') }}</textarea>
                    @error('kegiatan') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="spj-label" for="tanggal_mulai_input">
                        Tanggal Mulai Kegiatan <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="date" name="tanggal" id="tanggal_mulai_input" required class="spj-input @error('tanggal') has-error @enderror" value="{{ old('tanggal', date('Y-m-d')) }}">
                    <span style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem; display: block;">Tanggal dimulainya pelaksanaan kegiatan.</span>
                    @error('tanggal') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="spj-label" for="tanggal_selesai_input" style="display: flex; justify-content: space-between; align-items: center;">
                        <span>Tanggal Selesai Kegiatan</span>
                        <span style="font-size: 0.72rem; font-weight: normal; color: #64748b; background: #f1f5f9; padding: 2px 7px; border-radius: 4px;">Opsional</span>
                    </label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai_input" class="spj-input @error('tanggal_selesai') has-error @enderror" value="{{ old('tanggal_selesai') }}" min="{{ old('tanggal', date('Y-m-d')) }}">
                    <span style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem; display: block;">Kosongkan jika kegiatan hanya berlangsung 1 hari.</span>
                    @error('tanggal_selesai') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>

                <div id="seksi_field_container" style="display: {{ old('peruntukan', 'teknis') === 'umum' ? 'none' : 'block' }};">
                    <label class="spj-label" for="seksi_input">
                        Seksi / Tim Teknis <span style="color: #ef4444;">*</span>
                    </label>
                    @php
                        $currentSeksi = old('seksi', 'Sosial');
                        $seksiList = $availableSeksi ?? \App\Models\User::getAvailableSeksi();
                        if ($currentSeksi && !in_array($currentSeksi, $seksiList)) {
                            $seksiList[] = $currentSeksi;
                            sort($seksiList);
                        }
                    @endphp
                    <select name="seksi" id="seksi_input" class="spj-input @error('seksi') has-error @enderror">
                        <option value="" disabled {{ old('seksi') ? '' : 'selected' }}>Pilih Seksi...</option>
                        @foreach($seksiList as $opt)
                            <option value="{{ $opt }}" {{ $currentSeksi === $opt ? 'selected' : '' }}>
                                {{ str_starts_with($opt, 'Seksi ') ? $opt : 'Seksi ' . $opt }}
                            </option>
                        @endforeach
                    </select>
                    <span style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem; display: block;">Seksi teknis pelaksana kegiatan SPJ.</span>
                    @error('seksi') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="spj-label" for="no_wa_input">Nomor WhatsApp Pengaju / PIC</label>
                    <input type="text" name="no_wa" id="no_wa_input" class="spj-input" value="{{ old('no_wa') }}" placeholder="Contoh: 081234567890">
                    <span style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem; display: block;">Nomor WhatsApp PIC agar Bagian Umum & Bendahara dapat menghubungi langsung.</span>
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
                            <strong>Ajukan Sekarang</strong>
                            <span>Langsung dikirim ke Umum/PPSPM untuk proses verifikasi</span>
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
                <a href="{{ route('admin.spj.index') }}" class="spj-btn spj-btn-secondary" onclick="clearFormState()">Batal</a>
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
    // Data Kategori SPJ
    const teknisSpjData = @json($teknisSpjData ?? \App\Models\SyaratDokumenSpj::getSpjData('teknis'));
    const umumSpjData = @json($umumSpjData ?? \App\Models\SyaratDokumenSpj::getSpjData('umum'));
    let currentPeruntukan = "{{ old('peruntukan', 'teknis') }}";
    let spjData = (currentPeruntukan === 'umum') ? umumSpjData : teknisSpjData;

    function handlePeruntukanChange(val) {
        currentPeruntukan = val;
        spjData = (val === 'umum') ? umumSpjData : teknisSpjData;

        const cardTeknis = document.getElementById('card_peruntukan_teknis');
        const cardUmum = document.getElementById('card_peruntukan_umum');
        const seksiContainer = document.getElementById('seksi_field_container');

        if (val === 'umum') {
            if (cardUmum) {
                cardUmum.style.borderColor = '#0284c7';
                cardUmum.style.backgroundColor = '#f0f9ff';
            }
            if (cardTeknis) {
                cardTeknis.style.borderColor = 'var(--border)';
                cardTeknis.style.backgroundColor = '#ffffff';
            }
            if (seksiContainer) {
                seksiContainer.style.display = 'none';
            }
        } else {
            if (cardTeknis) {
                cardTeknis.style.borderColor = '#0284c7';
                cardTeknis.style.backgroundColor = '#f0f9ff';
            }
            if (cardUmum) {
                cardUmum.style.borderColor = 'var(--border)';
                cardUmum.style.backgroundColor = '#ffffff';
            }
            if (seksiContainer) {
                seksiContainer.style.display = 'block';
            }
        }

        const selNamaBelanja = document.getElementById("nama_belanja");
        const selJenisBelanja = document.getElementById("jenis_belanja");
        const docContainer = document.getElementById("dokumen_container");
        const docList = document.getElementById("dokumen_list");

        if (selNamaBelanja) {
            selNamaBelanja.innerHTML = '<option value="" disabled selected>-- Pilih Nama Belanja --</option>';
            for (const nama in spjData) {
                const opt = document.createElement("option");
                opt.value = nama;
                opt.textContent = nama;
                selNamaBelanja.appendChild(opt);
            }
        }

        if (selJenisBelanja) {
            selJenisBelanja.innerHTML = '<option value="" disabled selected>-- Pilih Jenis Belanja --</option>';
            selJenisBelanja.disabled = true;
        }

        if (docContainer) {
            docContainer.style.display = 'none';
        }
        if (docList) {
            docList.innerHTML = '';
        }

        sessionStorage.removeItem('admin_spj_form_nama_belanja');
        sessionStorage.removeItem('admin_spj_form_jenis_belanja');
        sessionStorage.removeItem('admin_spj_form_berkas');

        resetPrintStatus();
    }

    const oldNamaBelanja = {!! json_encode(old('nama_belanja', '')) !!};
    const oldJenisBelanja = {!! json_encode(old('jenis_belanja', '')) !!};
    const oldBerkas = {!! json_encode(old('berkas', [])) !!};

    function clearFormState() {
        sessionStorage.removeItem('admin_spj_form_nama_belanja');
        sessionStorage.removeItem('admin_spj_form_jenis_belanja');
        sessionStorage.removeItem('admin_spj_form_berkas');
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
        sessionStorage.setItem('admin_spj_form_berkas', JSON.stringify(state));
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

                // If it is supposed to be uploaded by SM/Panitia/Pengaju
                if(doc.assignee.includes('SM') || doc.assignee.includes('Panitia') || doc.assignee.includes('Pengaju')) {
                    const badgeText = currentPeruntukan === 'umum' ? 'Wajib Disiapkan Subbag Umum' : 'Wajib Disiapkan Teknis';
                    badgeHtml = `<span class="badge-assignee">${badgeText}</span>`;
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
        const activeNama = oldNamaBelanja || sessionStorage.getItem('admin_spj_form_nama_belanja') || '';
        const activeJenis = oldJenisBelanja || sessionStorage.getItem('admin_spj_form_jenis_belanja') || '';
        let activeBerkas = (oldBerkas && Object.keys(oldBerkas).length > 0)
            ? oldBerkas
            : JSON.parse(sessionStorage.getItem('admin_spj_form_berkas') || '{}');

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
        
        sessionStorage.setItem('admin_spj_form_nama_belanja', nama);
        sessionStorage.removeItem('admin_spj_form_jenis_belanja');
        sessionStorage.removeItem('admin_spj_form_berkas');

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

        sessionStorage.setItem('admin_spj_form_jenis_belanja', jenis);
        renderDokumenList(nama, jenis, null);
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

    function initCurrencyMask() {
        const display = document.getElementById('nilai_display');
        const hidden = document.getElementById('nilai_input');
        const hint = document.getElementById('nilai_terbilang_hint');
        if (!display || !hidden) return;

        function terbilangAngka(n) {
            if (!n || isNaN(n) || n <= 0) return '';
            const angka = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];
            n = Math.floor(n);
            if (n < 12) return angka[n];
            if (n < 20) return terbilangAngka(n - 10) + ' Belas';
            if (n < 100) return terbilangAngka(Math.floor(n / 10)) + ' Puluh ' + terbilangAngka(n % 10);
            if (n < 200) return 'Seratus ' + terbilangAngka(n - 100);
            if (n < 1000) return terbilangAngka(Math.floor(n / 100)) + ' Ratus ' + terbilangAngka(n % 100);
            if (n < 2000) return 'Seribu ' + terbilangAngka(n - 1000);
            if (n < 1000000) return terbilangAngka(Math.floor(n / 1000)) + ' Ribu ' + terbilangAngka(n % 1000);
            if (n < 1000000000) return terbilangAngka(Math.floor(n / 1000000)) + ' Juta ' + terbilangAngka(n % 1000000);
            if (n < 1000000000000) return terbilangAngka(Math.floor(n / 1000000000)) + ' Miliar ' + terbilangAngka(n % 1000000000);
            return terbilangAngka(Math.floor(n / 1000000000000)) + ' Triliun ' + terbilangAngka(n % 1000000000000);
        }

        function parseAndFormat(inputVal) {
            if (!inputVal) return { display: '', raw: '', intNum: 0 };
            let val = String(inputVal).trim();
            
            let hasComma = val.includes(',');
            let parts = val.split(',');
            let intPart = parts[0].replace(/\D/g, '');
            let decPart = parts.length > 1 ? parts.slice(1).join('').replace(/\D/g, '').substring(0, 2) : null;

            let formattedInt = intPart ? intPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '';

            let displayVal = formattedInt;
            if (hasComma || decPart !== null) {
                displayVal += ',' + (decPart !== null ? decPart : '');
            }

            let rawVal = intPart;
            if (decPart !== null && decPart.length > 0) {
                rawVal += '.' + decPart;
            }

            return { display: displayVal, raw: rawVal, intNum: intPart ? parseInt(intPart, 10) : 0 };
        }

        function handleInput(e) {
            const rawVal = display.value;
            const selStart = display.selectionStart;
            
            const beforeCursor = rawVal.slice(0, selStart);
            const digitsBefore = (beforeCursor.match(/\d/g) || []).length;
            const hasCommaBefore = beforeCursor.includes(',');

            const res = parseAndFormat(rawVal);
            display.value = res.display;
            hidden.value = res.raw;

            // Restore cursor position
            let newCursor = 0;
            let count = 0;
            for (let i = 0; i < display.value.length; i++) {
                if (display.value[i] === ',') {
                    if (hasCommaBefore) {
                        newCursor = i + 1;
                    }
                }
                if (/\d/.test(display.value[i])) {
                    count++;
                }
                if (count >= digitsBefore) {
                    newCursor = i + 1;
                    break;
                }
            }
            if (!display.value.includes(',') && digitsBefore === 0) {
                newCursor = 0;
            }
            try {
                display.setSelectionRange(newCursor, newCursor);
            } catch(err) {}

            if (hint) {
                if (res.intNum > 0) {
                    const kata = terbilangAngka(res.intNum);
                    hint.innerHTML = `<span style="color: var(--primary, #0284c7); font-weight: 600;">Rp ${res.display}</span>` + 
                        (kata ? ` &bull; <em style="color: #64748b;">${kata} Rupiah</em>` : '');
                } else {
                    hint.textContent = 'Pemisah ribuan (titik) ditambahkan otomatis saat mengetik.';
                }
            }
        }

        display.addEventListener('input', handleInput);

        if (display.value) {
            const res = parseAndFormat(display.value);
            display.value = res.display;
            hidden.value = res.raw;
            if (hint && res.intNum > 0) {
                const kata = terbilangAngka(res.intNum);
                hint.innerHTML = `<span style="color: var(--primary, #0284c7); font-weight: 600;">Rp ${res.display}</span>` + 
                    (kata ? ` &bull; <em style="color: #64748b;">${kata} Rupiah</em>` : '');
            }
        }
    }

    const masterDocDefs = [
        { label: 'KAK', keywords: ['KAK'] },
        { label: 'Form Permintaan', keywords: ['FP', 'Form Permintaan'] },
        { label: 'Daftar Gaji Induk', keywords: ['Daftar Gaji Induk', 'Gaji Induk'] },
        { label: 'Rekap Daftar Gaji', keywords: ['Rekapitulasi Daftar Gaji', 'Rekap Gaji'] },
        { label: 'Perubahan Gaji', keywords: ['Perubahan Gaji', 'Daftar Perubahan'] },
        { label: 'SPTJM', keywords: ['SPTJM'] },
        { label: 'Surat Perintah Lembur', keywords: ['Surat Perintah Lembur', 'Perintah Lembur'] },
        { label: 'Daftar Hadir Lembur', keywords: ['Daftar Hadir Lembur'] },
        { label: 'Perhitungan Lembur', keywords: ['Perhitungan Uang Lembur', 'Perhitungan Lembur', 'Daftar Perhitungan Uang Lembur', 'Daftar Perhitungan'] },
        { label: 'Daftar Rekapitulasi', keywords: ['Daftar Rekapitulasi'] },
        { label: 'Surat Pesanan', keywords: ['Surat Pesanan', 'bukti pemesanan'] },
        { label: 'BA Pemeriksaan', keywords: ['Pemeriksaan Barang', 'Pemeriksaan Pekerjaan', 'Berita Acara Pemeriksaan'] },
        { label: 'SPMK', keywords: ['Surat Perintah Mulai Kerja', 'SPMK'] },
        { label: 'Progres Pekerjaan', keywords: ['progres pekerjaan', 'Progres Pekerjaan'] },
        { label: 'Dokumen Kebutuhan', keywords: ['permintaan atau kebutuhan', 'kebutuhan barang'] },
        { label: 'Dokumentasi Barang', keywords: ['Dokumentasi barang', 'Dokumentasi hasil pekerjaan', 'dokumentasi barang'] },
        { label: 'Bukti Pembayaran', keywords: ['Bukti transaksi', 'Dokumen pembayaran', 'Bukti pembayaran'] },
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
        const seksiVal = document.querySelector('[name="seksi"]')?.value || '';
        const unitName = (currentPeruntukan === 'umum') ? 'Subbagian Umum' : (seksiVal ? (seksiVal.startsWith('Seksi ') ? seksiVal : 'Seksi ' + seksiVal) : 'Tim Teknis');
        const pengajuName = "{{ Auth::user()->name ?? 'Administrator' }}";
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
            + '            <div>No. SPJ: <strong>' + nomorSpj + '</strong> | Periode Kegiatan: <strong>' + periodeKegiatanFormatted + '</strong> | Unit: <strong>' + unitName + '</strong> | PIC: <strong>' + pengajuName + '<\/strong> ' + (noWa !== '—' ? '(' + noWa + ')' : '') + '<\/div>\n'
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
            + '    <script>\n'
            + '        window.addEventListener("load", function() {\n'
            + '            setTimeout(function() {\n'
            + '                window.focus();\n'
            + '                window.print();\n'
            + '            }, 350);\n'
            + '        });\n'
            + '    <\/script>\n'
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
        updateSubmitButtonState();
    }

    function updateSubmitButtonState() {
        const selectedStatus = document.querySelector('input[name="status"]:checked')?.value || 'draft';
        const submitBtn = document.getElementById('btn_submit_spj');
        const submitText = document.getElementById('btn_submit_text');
        const submitIcon = document.getElementById('btn_submit_icon');
        const hint = document.getElementById('print_requirement_hint');

        if (!submitBtn) return;

        if (selectedStatus === 'diajukan') {
            if (isPrinted) {
                // SUDAH DICETAK -> TOMBOL HIJAU AKTIF!
                submitBtn.disabled = false;
                submitBtn.style.backgroundColor = '#059669';
                submitBtn.style.borderColor = '#047857';
                submitBtn.style.color = '#ffffff';
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
                submitBtn.style.boxShadow = '0 4px 14px -2px rgba(5, 150, 105, 0.45)';
                submitBtn.title = 'Lembar checklist telah dicetak. Klik untuk menyimpan dan mengajukan SPJ!';

                if (submitIcon) {
                    submitIcon.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    `;
                }
                if (submitText) {
                    submitText.textContent = 'Ajukan SPJ Sekarang';
                }
                if (hint) {
                    hint.style.display = 'none';
                }
            } else {
                // BELUM DICETAK -> TOMBOL TIDAK AKTIF (ABU-ABU / DISABLED STATE)
                submitBtn.disabled = false; // Dibuka agar event click bisa memunculkan MODAL POP-UP peringatan!
                submitBtn.style.backgroundColor = '#94a3b8';
                submitBtn.style.borderColor = '#94a3b8';
                submitBtn.style.color = '#ffffff';
                submitBtn.style.opacity = '0.75';
                submitBtn.style.cursor = 'not-allowed';
                submitBtn.style.boxShadow = 'none';
                submitBtn.title = 'Harap tekan tombol Print terlebih dahulu untuk mencetak checklist fisik sebelum mengajukan SPJ.';

                if (submitIcon) {
                    submitIcon.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 6 2 18 2 18 9"></polyline>
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                            <rect x="6" y="14" width="12" height="8"></rect>
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
        initCurrencyMask();
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

        const form = document.querySelector('form[action="{{ route('admin.spj.store') }}"]') || document.querySelector('form');
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
