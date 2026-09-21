@extends('layouts.admin')

@section('content')

<div class="user-page">

    {{-- HEADER --}}
    <div class="page-header">

        <div>
            <h2>Tambah Pengguna</h2>

            <p>
                Tambahkan akun pengguna baru ke dalam sistem SPJ.
            </p>
        </div>

    </div>


    {{-- FORM PANEL --}}
    <div class="user-panel">

        <form
            action="{{ route('admin.pengguna.store') }}"
            method="POST"
            style="padding: 25px;">

            @csrf


            {{-- NAMA --}}
            <div style="margin-bottom: 20px;">

                <label
                    for="name"
                    style="
                        display:block;
                        margin-bottom:7px;
                        font-weight:600;
                        color:#334155;
                    ">
                    Nama Pengguna
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Masukkan nama pengguna"
                    required
                    style="
                        width:100%;
                        padding:11px 13px;
                        border:1px solid #e2e8f0;
                        border-radius:7px;
                        outline:none;
                        font-size:13px;
                    ">

                @error('name')
                <small style="color:#ef4444;">
                    {{ $message }}
                </small>
                @enderror

            </div>


            {{-- EMAIL --}}
            <div style="margin-bottom:20px;">

                <label
                    for="email"
                    style="
                        display:block;
                        margin-bottom:7px;
                        font-weight:600;
                        color:#334155;
                    ">
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="contoh@bps.go.id"
                    required
                    style="
                        width:100%;
                        padding:11px 13px;
                        border:1px solid #e2e8f0;
                        border-radius:7px;
                        outline:none;
                        font-size:13px;
                    ">

                @error('email')
                <small style="color:#ef4444;">
                    {{ $message }}
                </small>
                @enderror

            </div>


            {{-- PASSWORD --}}
            <div style="margin-bottom:20px;">

                <label
                    for="password"
                    style="
                        display:block;
                        margin-bottom:7px;
                        font-weight:600;
                        color:#334155;
                    ">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Minimal 8 karakter"
                    required
                    style="
                        width:100%;
                        padding:11px 13px;
                        border:1px solid #e2e8f0;
                        border-radius:7px;
                        outline:none;
                        font-size:13px;
                    ">

                @error('password')
                <small style="color:#ef4444;">
                    {{ $message }}
                </small>
                @enderror

            </div>


            {{-- ROLE --}}
            <div style="margin-bottom:20px;">

                <label
                    for="role"
                    style="
                        display:block;
                        margin-bottom:7px;
                        font-weight:600;
                        color:#334155;
                    ">
                    Role
                </label>

                <select
                    id="role"
                    name="role"
                    required
                    style="
                        width:100%;
                        padding:11px 13px;
                        border:1px solid #e2e8f0;
                        border-radius:7px;
                        outline:none;
                        font-size:13px;
                        background:white;
                    ">

                    <option value="">
                        -- Pilih Role --
                    </option>

                    <option
                        value="admin"
                        {{ old('role') == 'admin' ? 'selected' : '' }}>
                        Admin
                    </option>

                    <option
                        value="teknis"
                        {{ old('role') == 'teknis' ? 'selected' : '' }}>
                        Teknis
                    </option>

                    <option
                        value="umum"
                        {{ old('role') == 'umum' ? 'selected' : '' }}>
                        Umum / PPSPM
                    </option>

                    <option
                        value="ppk"
                        {{ old('role') == 'ppk' ? 'selected' : '' }}>
                        PPK
                    </option>

                    <option
                        value="bendahara"
                        {{ old('role') == 'bendahara' ? 'selected' : '' }}>
                        Bendahara
                    </option>

                </select>

                @error('role')
                <small style="color:#ef4444;">
                    {{ $message }}
                </small>
                @enderror

            </div>


            {{-- STATUS --}}
            <div style="margin-bottom:25px;">

                <label
                    for="status"
                    style="
                        display:block;
                        margin-bottom:7px;
                        font-weight:600;
                        color:#334155;
                    ">
                    Status
                </label>

                <select
                    id="status"
                    name="status"
                    required
                    style="
                        width:100%;
                        padding:11px 13px;
                        border:1px solid #e2e8f0;
                        border-radius:7px;
                        outline:none;
                        font-size:13px;
                        background:white;
                    ">

                    <option value="">
                        -- Pilih Status --
                    </option>

                    <option
                        value="aktif"
                        {{ old('status') == 'aktif' ? 'selected' : '' }}>
                        Aktif
                    </option>

                    <option
                        value="nonaktif"
                        {{ old('status') == 'nonaktif' ? 'selected' : '' }}>
                        Nonaktif
                    </option>

                </select>

                @error('status')
                <small style="color:#ef4444;">
                    {{ $message }}
                </small>
                @enderror

            </div>


            {{-- BUTTON --}}
            <div
                style="
                    display:flex;
                    justify-content:flex-end;
                    gap:10px;
                ">

                <a
                    href="{{ route('admin.pengguna.index') }}"
                    style="
                        padding:11px 17px;
                        border:1px solid #e2e8f0;
                        border-radius:7px;
                        color:#64748b;
                        text-decoration:none;
                        font-size:12px;
                    ">
                    Batal
                </a>


                <button
                    type="submit"
                    class="btn-primary">
                    <span>＋</span>
                    Simpan Pengguna
                </button>

            </div>

        </form>

    </div>

</div>

@endsection