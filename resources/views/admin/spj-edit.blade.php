@extends('layouts.admin')

@section('title', 'Edit SPJ')

@section('content')

<div class="spj-page">

    {{-- HEADER --}}
    <div class="page-header">
        <div>
            <h2>Edit SPJ</h2>
            <p>Perbarui data SPJ yang telah terdaftar dalam sistem.</p>
        </div>

        <a href="{{ route('admin.spj.index') }}" class="btn btn-light border">
            ← Kembali
        </a>
    </div>


    {{-- FORM --}}
    <div class="spj-panel">

        <div class="spj-form-card">

            {{ dd($spj->id) }}

            <form
                action="{{ route('admin.spj.update', ['id' => $spj->id]) }}"
                method="POST">

                @csrf
                @method('PUT')

                {{-- NOMOR SPJ --}}
                <div class="mb-4">
                    <label for="nomor_spj" class="form-label">
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
                    <div class="text-danger mt-1">
                        {{ $message }}
                    </div>
                    @enderror
                </div>


                {{-- KEGIATAN --}}
                <div class="mb-4">
                    <label for="kegiatan" class="form-label">
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
                    <div class="text-danger mt-1">
                        {{ $message }}
                    </div>
                    @enderror
                </div>


                {{-- TANGGAL --}}
                <div class="mb-4">
                    <label for="tanggal" class="form-label">
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
                    <div class="text-danger mt-1">
                        {{ $message }}
                    </div>
                    @enderror
                </div>


                {{-- NILAI --}}
                <div class="mb-4">
                    <label for="nilai" class="form-label">
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
                    <div class="text-danger mt-1">
                        {{ $message }}
                    </div>
                    @enderror
                </div>


                {{-- KETERANGAN --}}
                <div class="mb-4">
                    <label for="keterangan" class="form-label">
                        Keterangan
                    </label>

                    <textarea
                        id="keterangan"
                        name="keterangan"
                        class="form-control"
                        rows="4"
                        placeholder="Keterangan tambahan">{{ old('keterangan', $spj->keterangan) }}</textarea>

                    @error('keterangan')
                    <div class="text-danger mt-1">
                        {{ $message }}
                    </div>
                    @enderror
                </div>


                {{-- STATUS --}}
                <div class="mb-4">
                    <label for="status" class="form-label">
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
                    <div class="text-danger mt-1">
                        {{ $message }}
                    </div>
                    @enderror
                </div>


                {{-- BUTTON --}}
                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a
                        href="{{ route('spj.index') }}"
                        class="btn btn-light border">
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary">
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection