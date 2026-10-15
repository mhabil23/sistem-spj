@extends('layouts.teknis')

@php
$title = 'Edit SPJ';
$subtitle = 'Perbaiki atau perbarui pengajuan SPJ Anda.';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/teknis/spj.css'])
@endpush

<div class="page-heading" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; animation: slideUp 0.4s ease-out forwards;">
    <div>
        <h2 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin-bottom: 0.25rem;">Edit Pengajuan SPJ</h2>
        <p style="color: #64748b; font-size: 0.95rem;">Anda dapat mengedit SPJ ini karena statusnya <strong style="color: #0f172a;">{{ str_replace('_', ' ', strtoupper($spj->status)) }}</strong>.</p>
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
            <p>Perbaiki data sesuai dengan catatan revisi yang diberikan.</p>
        </div>
    </div>
    
    <div class="spj-panel-body">
        @if(in_array($spj->status, ['dikembalikan', 'revisi_umum', 'revisi_ppk', 'revisi_ppspm', 'revisi_bendahara']) && $spj->catatan_revisi)
            <div class="spj-alert spj-alert-error" style="align-items: flex-start; margin-bottom: 2rem;">
                <div style="flex-shrink: 0; margin-top: 0.125rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                </div>
                <div>
                    <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 0.25rem;">Catatan Revisi dari {{ strtoupper(explode('_', $spj->status)[1] ?? 'Pemeriksa') }}</h3>
                    <p style="font-weight: 400; margin: 0; white-space: pre-line; line-height: 1.5;">{{ $spj->catatan_revisi }}</p>
                </div>
            </div>
        @endif

        <form action="{{ route('teknis.spj.update', $spj->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            @php
                $dokumen = $spj->dokumen_file ? json_decode($spj->dokumen_file, true) : [];
            @endphp
            
            <div style="padding: 1.5rem; background: linear-gradient(135deg, #f8fafc, #f1f5f9); border-radius: var(--radius-md); border: 1px solid var(--border); margin-bottom: 1.5rem;">
                <div style="margin-bottom: 0.75rem; display: flex; align-items: center; justify-content: space-between;">
                    <label class="spj-label" style="margin-bottom: 0;">Kelengkapan Berkas (Telah Dipilih)</label>
                    <span style="font-size: 0.75rem; color: #64748b;">Centang berkas fisik yang sudah disiapkan</span>
                </div>
                <div id="dokumen_list" style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                    @if(count($dokumen) > 0)
                        @foreach($dokumen as $index => $doc)
                            <div class="checkbox-wrapper" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; background: white; border: 1px solid var(--border); border-radius: var(--radius-md);">
                                <input type="hidden" name="nama_dokumen[{{ $index }}]" value="{{ $doc['nama'] }}">
                                <input type="checkbox" name="berkas[{{ $index }}]" id="doc_{{ $index }}" value="1" {{ $doc['disiapkan'] ? 'checked' : '' }} style="width: 1rem; height: 1rem; cursor: pointer;">
                                <label for="doc_{{ $index }}" style="margin: 0; font-size: 0.85rem; font-weight: 500; color: #334155; cursor: pointer; user-select: none;">{{ $doc['nama'] }}</label>
                            </div>
                        @endforeach
                    @else
                        <p style="font-size: 0.85rem; color: #64748b; font-style: italic;">Tidak ada dokumen yang dipilih sebelumnya.</p>
                    @endif
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;" class="spj-form-group">
                <div>
                    <label class="spj-label">Uraian Kegiatan</label>
                    <input type="text" name="kegiatan" required class="spj-input" value="{{ old('kegiatan', $spj->kegiatan) }}">
                    @error('kegiatan') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;" class="spj-form-group">
                <div>
                    <label class="spj-label">Tanggal Kegiatan</label>
                    <input type="date" name="tanggal" required class="spj-input" value="{{ old('tanggal', $spj->tanggal->format('Y-m-d')) }}">
                    @error('tanggal') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="spj-label">Nilai Nominal (Rp)</label>
                    <input type="number" name="nilai" required class="spj-input" min="0" value="{{ old('nilai', intval($spj->nilai)) }}">
                    @error('nilai') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
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
                            <strong>Ajukan Ulang</strong>
                            <span>Kirim kembali ke Umum/PPSPM setelah perbaikan</span>
                        </div>
                    </label>
                </div>
                @error('status') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border);">
                <a href="{{ route('teknis.spj.index') }}" class="spj-btn spj-btn-secondary">Batal</a>
                <button type="submit" class="spj-btn spj-btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Perbarui SPJ
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
