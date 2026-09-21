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