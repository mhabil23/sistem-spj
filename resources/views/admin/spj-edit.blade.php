@extends('layouts.admin')

@section('title', 'Edit SPJ')

@section('content')

<div class="spj-page">

    {{-- HEADER --}}
    <div class="page-header">

        <div>
            <h2>Edit SPJ</h2>
            <p>
                Perbarui data SPJ yang telah terdaftar dalam sistem.
            </p>
        </div>
    </div>


    {{-- FORM --}}
    <div class="spj-panel">

        <div class="spj-form-card">

            <form
                action="{{ route('admin.spj.update', ['spj' => $spj->id]) }}"
                method="POST"
                class="spj-form">

                @csrf
                @method('PUT')


                <div class="form-grid">

                    {{-- NOMOR SPJ --}}
                    <div class="form-group">

                        <label for="nomor_spj">
                            Nomor SPJ
                        </label>

                        <input
                            type="text"
                            id="nomor_spj"
                            name="nomor_spj"
                            class="form-control"
                            value="{{ old('nomor_spj', $spj->nomor_spj) }}"
                            placeholder="Contoh: SPJ/001/IX/2026"
                            required>

                        @error('nomor_spj')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>


                    {{-- KEGIATAN --}}
                    <div class="form-group">

                        <label for="kegiatan">
                            Kegiatan
                        </label>

                        <input
                            type="text"
                            id="kegiatan"
                            name="kegiatan"
                            class="form-control"
                            value="{{ old('kegiatan', $spj->kegiatan) }}"
                            placeholder="Masukkan nama kegiatan"
                            required>

                        @error('kegiatan')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>


                    {{-- TANGGAL --}}
                    <div class="form-group">

                        <label for="tanggal">
                            Tanggal
                        </label>

                        <input
                            type="date"
                            id="tanggal"
                            name="tanggal"
                            class="form-control"
                            value="{{ old('tanggal', $spj->tanggal ? $spj->tanggal->format('Y-m-d') : '') }}"
                            required>

                        @error('tanggal')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>


                    {{-- NILAI --}}
                    <div class="form-group">

                        <label for="nilai">
                            Nilai
                        </label>

                        <input
                            type="number"
                            id="nilai"
                            name="nilai"
                            class="form-control"
                            value="{{ old('nilai', $spj->nilai) }}"
                            placeholder="Masukkan nilai SPJ"
                            min="0"
                            step="0.01"
                            required>

                        @error('nilai')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>


                    {{-- KETERANGAN --}}
                    <div class="form-group form-full">

                        <label for="keterangan">
                            Keterangan
                        </label>

                        <textarea
                            id="keterangan"
                            name="keterangan"
                            class="form-control"
                            rows="4"
                            placeholder="Masukkan keterangan tambahan">{{ old('keterangan', $spj->keterangan) }}</textarea>

                        @error('keterangan')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>


                    {{-- KELENGKAPAN ADMINISTRASI --}}
                    <div class="form-group form-full" style="margin-bottom: 20px;">
                        <label style="margin-bottom: 10px; font-weight: 600; display: block;">Kelengkapan Administrasi</label>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            <label style="display: flex; align-items: center; gap: 8px; font-weight: normal;"><input type="checkbox" name="kel_daftar_penerima" value="1" {{ old('kel_daftar_penerima', $spj->kel_daftar_penerima) ? 'checked' : '' }}> Daftar Penerima</label>
                            <label style="display: flex; align-items: center; gap: 8px; font-weight: normal;"><input type="checkbox" name="kel_bast" value="1" {{ old('kel_bast', $spj->kel_bast) ? 'checked' : '' }}> BAST</label>
                            <label style="display: flex; align-items: center; gap: 8px; font-weight: normal;"><input type="checkbox" name="kel_sk" value="1" {{ old('kel_sk', $spj->kel_sk) ? 'checked' : '' }}> SK</label>
                            <label style="display: flex; align-items: center; gap: 8px; font-weight: normal;"><input type="checkbox" name="kel_kak" value="1" {{ old('kel_kak', $spj->kel_kak) ? 'checked' : '' }}> KAK</label>
                            <label style="display: flex; align-items: center; gap: 8px; font-weight: normal;"><input type="checkbox" name="kel_form_permintaan" value="1" {{ old('kel_form_permintaan', $spj->kel_form_permintaan) ? 'checked' : '' }}> Form Permintaan</label>
                            <label style="display: flex; align-items: center; gap: 8px; font-weight: normal;"><input type="checkbox" name="kel_spk" value="1" {{ old('kel_spk', $spj->kel_spk) ? 'checked' : '' }}> SPK</label>
                            <label style="display: flex; align-items: center; gap: 8px; font-weight: normal;"><input type="checkbox" name="kel_surat_tugas" value="1" {{ old('kel_surat_tugas', $spj->kel_surat_tugas) ? 'checked' : '' }}> Surat Tugas</label>
                            <label style="display: flex; align-items: center; gap: 8px; font-weight: normal;"><input type="checkbox" name="kel_kesesuaian_mrk" value="1" {{ old('kel_kesesuaian_mrk', $spj->kel_kesesuaian_mrk) ? 'checked' : '' }}> Kesesuaian MRK dg SPK</label>
                            <label style="display: flex; align-items: center; gap: 8px; font-weight: normal;"><input type="checkbox" name="kel_cms" value="1" {{ old('kel_cms', $spj->kel_cms) ? 'checked' : '' }}> CMS</label>
                            <label style="display: flex; align-items: center; gap: 8px; font-weight: normal;"><input type="checkbox" name="kel_cek_sbks" value="1" {{ old('kel_cek_sbks', $spj->kel_cek_sbks) ? 'checked' : '' }}> Cek SBKS</label>
                        </div>
                    </div>


                    {{-- STATUS --}}
                    <div class="form-group">

                        <label for="status">
                            Status
                        </label>

                        <select
                            id="status"
                            name="status"
                            class="form-select"
                            required>

                            <option value="draft"
                                {{ old('status', $spj->status) == 'draft' ? 'selected' : '' }}>
                                Draft
                            </option>

                            <option value="diajukan"
                                {{ old('status', $spj->status) == 'diajukan' ? 'selected' : '' }}>
                                Diajukan
                            </option>

                            <option value="diproses"
                                {{ old('status', $spj->status) == 'diproses' ? 'selected' : '' }}>
                                Diproses
                            </option>

                            <option value="selesai"
                                {{ old('status', $spj->status) == 'selesai' ? 'selected' : '' }}>
                                Selesai
                            </option>

                            <option value="dikembalikan"
                                {{ old('status', $spj->status) == 'dikembalikan' ? 'selected' : '' }}>
                                Dikembalikan
                            </option>

                        </select>

                        @error('status')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>

                </div>


                {{-- BUTTON --}}
                <div class="form-actions">

                    <a
                        href="{{ route('admin.spj.index') }}"
                        class="btn-spj btn-cancel">

                        <i class="bi bi-x-lg"></i>
                        Batal

                    </a>

                    <button
                        type="submit"
                        class="btn-spj btn-save">

                        <i class="bi bi-check-lg"></i>
                        Simpan Perubahan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection