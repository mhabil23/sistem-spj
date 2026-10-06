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
                action="{{ route('admin.spj.update', ['id' => $spj->id]) }}"
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