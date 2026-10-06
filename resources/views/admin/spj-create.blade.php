@extends('layouts.admin')

@section('title', 'Tambah SPJ')

@section('content')

<div class="spj-page">

    {{-- HEADER --}}
    <div class="page-header">
        <div>
            <h2>Tambah SPJ</h2>
            <p>Tambahkan data SPJ baru ke dalam sistem.</p>
        </div>

        <a href="{{ route('admin.spj.index') }}" class="btn-secondary">
            ← Kembali
        </a>
    </div>


    {{-- FORM --}}
    <div class="spj-form-panel">

        <form action="{{ route('admin.spj.store') }}" method="POST">

            @csrf

            {{-- NOMOR SPJ --}}
            <div class="form-group">
                <label for="nomor_spj">Nomor SPJ</label>

                <input
                    type="text"
                    id="nomor_spj"
                    name="nomor_spj"
                    value="{{ old('nomor_spj') }}"
                    placeholder="Contoh: SPJ/001/IX/2026"
                    required>

                @error('nomor_spj')
                <small class="error-message">{{ $message }}</small>
                @enderror
            </div>


            {{-- KEGIATAN --}}
            <div class="form-group">
                <label for="kegiatan">Kegiatan</label>

                <input
                    type="text"
                    id="kegiatan"
                    name="kegiatan"
                    value="{{ old('kegiatan') }}"
                    placeholder="Masukkan nama kegiatan"
                    required>

                @error('kegiatan')
                <small class="error-message">{{ $message }}</small>
                @enderror
            </div>


            {{-- TANGGAL --}}
            <div class="form-group">
                <label for="tanggal">Tanggal</label>

                <input
                    type="date"
                    id="tanggal"
                    name="tanggal"
                    value="{{ old('tanggal') }}"
                    required>

                @error('tanggal')
                <small class="error-message">{{ $message }}</small>
                @enderror
            </div>


            {{-- NILAI --}}
            <div class="form-group">
                <label for="nilai">Nilai</label>

                <input
                    type="number"
                    id="nilai"
                    name="nilai"
                    value="{{ old('nilai') }}"
                    placeholder="Masukkan nilai SPJ"
                    min="0"
                    step="0.01"
                    required>

                @error('nilai')
                <small class="error-message">{{ $message }}</small>
                @enderror
            </div>


            {{-- KETERANGAN --}}
            <div class="form-group">
                <label for="keterangan">Keterangan</label>

                <textarea
                    id="keterangan"
                    name="keterangan"
                    rows="4"
                    placeholder="Keterangan tambahan">{{ old('keterangan') }}</textarea>

                @error('keterangan')
                <small class="error-message">{{ $message }}</small>
                @enderror
            </div>

            {{-- KELENGKAPAN ADMINISTRASI --}}
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="margin-bottom: 10px; font-weight: 600; display: block;">Kelengkapan Administrasi</label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <label style="display: flex; align-items: center; gap: 8px; font-weight: normal;"><input type="checkbox" name="kel_daftar_penerima" value="1" {{ old('kel_daftar_penerima') ? 'checked' : '' }}> Daftar Penerima</label>
                    <label style="display: flex; align-items: center; gap: 8px; font-weight: normal;"><input type="checkbox" name="kel_bast" value="1" {{ old('kel_bast') ? 'checked' : '' }}> BAST</label>
                    <label style="display: flex; align-items: center; gap: 8px; font-weight: normal;"><input type="checkbox" name="kel_sk" value="1" {{ old('kel_sk') ? 'checked' : '' }}> SK</label>
                    <label style="display: flex; align-items: center; gap: 8px; font-weight: normal;"><input type="checkbox" name="kel_kak" value="1" {{ old('kel_kak') ? 'checked' : '' }}> KAK</label>
                    <label style="display: flex; align-items: center; gap: 8px; font-weight: normal;"><input type="checkbox" name="kel_form_permintaan" value="1" {{ old('kel_form_permintaan') ? 'checked' : '' }}> Form Permintaan</label>
                    <label style="display: flex; align-items: center; gap: 8px; font-weight: normal;"><input type="checkbox" name="kel_spk" value="1" {{ old('kel_spk') ? 'checked' : '' }}> SPK</label>
                    <label style="display: flex; align-items: center; gap: 8px; font-weight: normal;"><input type="checkbox" name="kel_surat_tugas" value="1" {{ old('kel_surat_tugas') ? 'checked' : '' }}> Surat Tugas</label>
                    <label style="display: flex; align-items: center; gap: 8px; font-weight: normal;"><input type="checkbox" name="kel_kesesuaian_mrk" value="1" {{ old('kel_kesesuaian_mrk') ? 'checked' : '' }}> Kesesuaian MRK dg SPK</label>
                    <label style="display: flex; align-items: center; gap: 8px; font-weight: normal;"><input type="checkbox" name="kel_cms" value="1" {{ old('kel_cms') ? 'checked' : '' }}> CMS</label>
                    <label style="display: flex; align-items: center; gap: 8px; font-weight: normal;"><input type="checkbox" name="kel_cek_sbks" value="1" {{ old('kel_cek_sbks') ? 'checked' : '' }}> Cek SBKS</label>
                </div>
            </div>


            {{-- STATUS --}}
            <div class="form-group">
                <label for="status">Status</label>

                <select
                    id="status"
                    name="status"
                    required>
                    <option value="draft"
                        {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>
                        Draft
                    </option>

                    <option value="diajukan"
                        {{ old('status') == 'diajukan' ? 'selected' : '' }}>
                        Diajukan
                    </option>

                    <option value="diproses"
                        {{ old('status') == 'diproses' ? 'selected' : '' }}>
                        Diproses
                    </option>

                    <option value="selesai"
                        {{ old('status') == 'selesai' ? 'selected' : '' }}>
                        Selesai
                    </option>

                    <option value="dikembalikan"
                        {{ old('status') == 'dikembalikan' ? 'selected' : '' }}>
                        Dikembalikan
                    </option>
                </select>

                @error('status')
                <small class="error-message">{{ $message }}</small>
                @enderror
            </div>


            {{-- BUTTON --}}
            <div class="form-actions">

                <a
                    href="{{ route('admin.spj.index') }}"
                    class="btn-secondary">
                    Batal
                </a>

                <button
                    type="submit"
                    class="btn-primary">
                    Simpan SPJ
                </button>

            </div>

        </form>

    </div>

</div>

@endsection