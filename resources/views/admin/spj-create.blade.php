@extends('layouts.admin')

@section('content')

<div class="container">

    <div class="page-header">
        <div>
            <h2>Tambah SPJ</h2>
            <p>Tambahkan data SPJ baru ke dalam sistem.</p>
        </div>

        <a href="{{ route('admin.spj.index') }}" class="btn-secondary">
            ← Kembali
        </a>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Terjadi kesalahan:</strong>

        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card">

        <form action="{{ route('admin.spj.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="nomor_spj">Nomor SPJ</label>
                <input
                    type="text"
                    id="nomor_spj"
                    name="nomor_spj"
                    value="{{ old('nomor_spj') }}"
                    placeholder="Contoh: SPJ/001/IX/2026"
                    required>
            </div>

            <div class="form-group">
                <label for="kegiatan">Kegiatan</label>
                <input
                    type="text"
                    id="kegiatan"
                    name="kegiatan"
                    value="{{ old('kegiatan') }}"
                    placeholder="Masukkan nama kegiatan"
                    required>
            </div>

            <div class="form-group">
                <label for="tanggal">Tanggal</label>
                <input
                    type="date"
                    id="tanggal"
                    name="tanggal"
                    value="{{ old('tanggal') }}"
                    required>
            </div>

            <div class="form-group">
                <label for="nilai">Nilai</label>
                <input
                    type="number"
                    id="nilai"
                    name="nilai"
                    value="{{ old('nilai') }}"
                    min="0"
                    step="0.01"
                    placeholder="Masukkan nilai SPJ"
                    required>
            </div>

            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <textarea
                    id="keterangan"
                    name="keterangan"
                    rows="4"
                    placeholder="Keterangan tambahan">{{ old('keterangan') }}</textarea>
            </div>

            <div class="form-group">
                <label for="status">Status</label>

                <select id="status" name="status" required>
                    <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>
                        Draft
                    </option>

                    <option value="diajukan" {{ old('status') == 'diajukan' ? 'selected' : '' }}>
                        Diajukan
                    </option>

                    <option value="diproses" {{ old('status') == 'diproses' ? 'selected' : '' }}>
                        Diproses
                    </option>

                    <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>
                        Selesai
                    </option>

                    <option value="dikembalikan" {{ old('status') == 'dikembalikan' ? 'selected' : '' }}>
                        Dikembalikan
                    </option>
                </select>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.spj.index') }}" class="btn-secondary">
                    Batal
                </a>

                <button type="submit" class="btn-primary">
                    Simpan SPJ
                </button>
            </div>

        </form>

    </div>

</div>

@endsection