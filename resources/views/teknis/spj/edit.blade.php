@extends('layouts.teknis')

@php
$title = 'Edit SPJ';
$subtitle = 'Perbaiki atau perbarui pengajuan SPJ Anda.';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/teknis/spj.css'])
@endpush

<div class="dashboard-heading">
    <div>
        <h2 class="dashboard-heading-title">Edit Pengajuan SPJ</h2>
        <p class="dashboard-heading-subtitle">Anda dapat mengedit SPJ ini karena statusnya <strong style="color: var(--dash-peach-text);">{{ str_replace('_', ' ', strtoupper($spj->status)) }}</strong>.</p>
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
            <p>Perbaiki data sesuai dengan catatan revisi yang diberikan.</p>
        </div>
    </div>
    
    <div class="spj-panel-body">
        @if(in_array($spj->status, ['dikembalikan', 'revisi_umum', 'revisi_ppk', 'revisi_ppspm', 'revisi_bendahara']) && $spj->catatan_revisi)
            <div class="spj-alert spj-alert-error" style="align-items: flex-start; margin-bottom: 2rem;">
                <div style="flex-shrink: 0; margin-top: 0.125rem; font-size: 22px; color: var(--dash-peach-text);">
                    <ion-icon name="alert-circle"></ion-icon>
                </div>
                <div>
                    <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 0.25rem; color: var(--dash-peach-text);">Catatan Revisi dari {{ strtoupper(explode('_', $spj->status)[1] ?? 'Pemeriksa') }}</h3>
                    <p style="font-weight: 400; margin: 0; white-space: pre-line; line-height: 1.5; color: var(--dash-text-dark);">{{ $spj->catatan_revisi }}</p>
                </div>
            </div>
        @endif

        <form action="{{ route('teknis.spj.update', $spj->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            @php
                $dokumen = $spj->dokumen_list;
                if (empty($dokumen) && !empty($spj->dokumen_file) && str_starts_with(trim($spj->dokumen_file), '[')) {
                    $dokumen = json_decode($spj->dokumen_file, true) ?: [];
                }
            @endphp
            
            <div style="padding: 1.5rem; background: var(--dash-input-bg); border-radius: var(--radius-md); border: 1px solid var(--border); margin-bottom: 1.5rem;">
                <div style="margin-bottom: 0.75rem;">
                    <label class="spj-label" style="margin-bottom: 0;">Kelengkapan Berkas (Telah Dipilih)</label>
                    <span style="font-size: 0.75rem; color: var(--dash-text-muted); display: block;">Centang berkas fisik yang sudah disiapkan</span>
                </div>
                <div id="dokumen_list" style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                    @if(count($dokumen) > 0)
                        @foreach($dokumen as $index => $doc)
                            <div class="checkbox-wrapper" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; background: var(--dash-card-bg); border: 1px solid var(--border); border-radius: var(--radius-md);">
                                <input type="hidden" name="nama_dokumen[{{ $index }}]" value="{{ $doc['nama'] }}">
                                <input type="checkbox" name="berkas[{{ $index }}]" id="doc_{{ $index }}" value="1" {{ $doc['disiapkan'] ? 'checked' : '' }} style="width: 1rem; height: 1rem; cursor: pointer;">
                                <label for="doc_{{ $index }}" style="margin: 0; font-size: 0.85rem; font-weight: 500; color: var(--dash-text-dark); cursor: pointer; user-select: none;">{{ $doc['nama'] }}</label>
                            </div>
                        @endforeach
                    @else
                        <p style="font-size: 0.85rem; color: var(--dash-text-muted); font-style: italic;">Tidak ada dokumen yang dipilih sebelumnya.</p>
                    @endif
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;" class="spj-form-group">
                <div>
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.4rem;">
                        <label class="spj-label" style="margin-bottom: 0;">Nomor SPJ</label>
                        <span style="font-size: 0.72rem; color: #0284c7; background: #e0f2fe; padding: 2px 8px; border-radius: 4px; font-weight: 600;">Otomatis Sistem</span>
                    </div>
                    <input type="text" name="nomor_spj" id="nomor_spj_input" class="spj-input" value="{{ old('nomor_spj', $spj->nomor_spj ?: ($nomorSpjOtomatis ?? '')) }}" placeholder="Digenerate otomatis oleh sistem" readonly style="background-color: #f8fafc; font-weight: 700; color: #0f172a; cursor: not-allowed;" title="Nomor SPJ dibuat otomatis oleh sistem">
                    @error('nomor_spj') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="spj-label">Nilai Nominal (Rp)</label>
                    <input type="number" name="nilai" id="nilai_input" required class="spj-input" min="0" value="{{ old('nilai', intval($spj->nilai)) }}">
                    @error('nilai') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem; margin-top: 1.5rem;" class="spj-form-group">
                <div>
                    <label class="spj-label">Uraian Kegiatan</label>
                    <input type="text" name="kegiatan" required class="spj-input" value="{{ old('kegiatan', $spj->kegiatan) }}">
                    @error('kegiatan') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem; margin-top: 1.5rem;" class="spj-form-group">
                <div>
                    <label class="spj-label">Tanggal Kegiatan</label>
                    <input type="date" name="tanggal" required class="spj-input" value="{{ old('tanggal', $spj->tanggal->format('Y-m-d')) }}">
                    @error('tanggal') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="spj-form-group">
                <label class="spj-label">Nomor WhatsApp Pengaju / PIC</label>
                <input type="text" name="no_wa" class="spj-input" value="{{ old('no_wa', $spj->no_wa) }}" placeholder="Contoh: 081234567890">
                <span style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem; display: block;">Nomor WhatsApp Anda agar Bagian Umum & Bendahara dapat menghubungi Anda langsung.</span>
                @error('no_wa') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
            </div>

            <div class="spj-form-group">
                <label class="spj-label">Keterangan Tambahan</label>
                <textarea name="keterangan" class="spj-input spj-textarea">{{ old('keterangan', $spj->keterangan) }}</textarea>
                @error('keterangan') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
            </div>

            <div class="spj-form-group" style="margin-top: 2rem;">
                <label class="spj-label">Tindakan Lanjutan</label>
                <div class="spj-radio-group">
                    <label class="spj-radio-card">
                        <input type="radio" name="status" value="draft" {{ old('status', $spj->status) === 'draft' ? 'checked' : '' }}>
                        <div class="spj-radio-content">
                            <strong>Simpan sebagai Draft</strong>
                            <span>Belum siap untuk diajukan, simpan perubahan sementara</span>
                        </div>
                    </label>
                    <label class="spj-radio-card">
                        <input type="radio" name="status" value="diajukan" {{ old('status', $spj->status) !== 'draft' ? 'checked' : '' }}>
                        <div class="spj-radio-content">
                            <strong>Ajukan Ulang &amp; Bawa Berkas Fisik ke Umum</strong>
                            <span>Kirim kembali dan serahkan berkas fisik perbaikan ke meja Subbagian Umum</span>
                        </div>
                    </label>
                </div>
                @error('status') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror

                <div id="print_status_banner" style="display: none; width: 100%; margin-top: 1.25rem; padding: 0.85rem 1.25rem; border-radius: var(--radius-md); font-size: 0.875rem; font-weight: 500;"></div>

                <div id="print_requirement_hint" style="display: none; margin-top: 0.75rem; padding: 0.75rem 1rem; border-radius: var(--radius-md); background: #fff7ed; border: 1px solid #fed7aa; color: #c2410c; font-size: 0.825rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <span><strong>Wajib Print:</strong> Anda memilih opsi <strong>Ajukan Ulang</strong>. Sesuai ketentuan, Anda <u>wajib menekan tombol Print</u> dan mencetak lembar checklist berkas SPJ sebelum pengajuan ulang dapat disimpan.</span>
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
                    Perbarui SPJ
                </button>
            </div>
        </form>
    </div>
</div>

<x-print-validation-modal />

@endsection

@push('scripts')
<script>
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
        const docInputs = document.querySelectorAll('input[name^="nama_dokumen"]');
        if (docInputs.length === 0) {
            alert("Belum ada dokumen yang tersedia untuk dicetak.");
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
        const tanggalInputSpj = "{{ $spj->created_at ? $spj->created_at->locale('id')->translatedFormat('d F Y') : '' }}";
        const tglMasukUmum = "{{ $spj->disetujui_umum_at ? \Carbon\Carbon::parse($spj->disetujui_umum_at)->locale('id')->translatedFormat('d F Y') : '-' }}";

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
            + '    <title>Laporan SPJ - ' + nomorSpj + '<\/title>\n'
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
            + '                    <td>' + (tanggalInputSpj || tanggalFormatted) + '<\/td>\n'
            + docValuesHtml
            + '                    <td>' + tglMasukUmum + '<\/td>\n'
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
                    <span><strong>Checklist Telah Dicetak:</strong> Lembar checklist SPJ telah dicetak. Anda sekarang dapat melanjutkan pengajuan ulang SPJ.</span>
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

        const form = document.querySelector('form[action="{{ route('teknis.spj.update', $spj) }}"]') || document.querySelector('form');
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

                    openPrintValidationModal();
                    return false;
                }
            });
        }
    });
</script>
@endpush
