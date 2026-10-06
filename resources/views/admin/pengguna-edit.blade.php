@extends('layouts.admin')

@php
$title = 'Edit Pengguna';
$subtitle = 'Perbarui informasi akun dan hak akses pengguna.';
@endphp

@section('content')

<div class="user-page">

    {{-- HEADER --}}
    <div class="page-header">

        <div>
            <h2>Edit Pengguna</h2>
            <p>
                Perbarui informasi akun dan hak akses pengguna sistem SPJ.
            </p>
        </div>

    </div>


    {{-- FORM PANEL --}}
    <div class="user-panel edit-user-panel">

        <div class="edit-user-header">

            <div class="edit-user-icon">
                <i class="bi bi-person-gear"></i>
            </div>

            <div>
                <h3>Informasi Pengguna</h3>
                <p>
                    Ubah data akun pengguna di bawah ini.
                </p>
            </div>

        </div>


        <form
            action="{{ route('admin.pengguna.update', $user->id) }}"
            method="POST">

            @csrf
            @method('PUT')


            <div class="user-form-grid">

                {{-- NAMA --}}
                <div class="form-group">

                    <label for="name">
                        Nama Lengkap
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        placeholder="Masukkan nama lengkap"
                        required>

                    @error('name')
                    <small class="form-error">
                        {{ $message }}
                    </small>
                    @enderror

                </div>


                {{-- EMAIL --}}
                <div class="form-group">

                    <label for="email">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        placeholder="Masukkan email"
                        required>

                    @error('email')
                    <small class="form-error">
                        {{ $message }}
                    </small>
                    @enderror

                </div>


                {{-- ROLE --}}
                <div class="form-group">

                    <label for="role">
                        Role Pengguna
                    </label>

                    <select
                        id="role"
                        name="role"
                        required>

                        <option value="admin"
                            {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>

                        <option value="teknis"
                            {{ old('role', $user->role) == 'teknis' ? 'selected' : '' }}>
                            Teknis
                        </option>

                        <option value="umum"
                            {{ old('role', $user->role) == 'umum' ? 'selected' : '' }}>
                            Umum / PPSPM
                        </option>

                        <option value="ppk"
                            {{ old('role', $user->role) == 'ppk' ? 'selected' : '' }}>
                            PPK
                        </option>

                        <option value="bendahara"
                            {{ old('role', $user->role) == 'bendahara' ? 'selected' : '' }}>
                            Bendahara
                        </option>

                    </select>

                    @error('role')
                    <small class="form-error">
                        {{ $message }}
                    </small>
                    @enderror

                </div>


                {{-- STATUS --}}
                <div class="form-group">

                    <label for="status">
                        Status Akun
                    </label>

                    <select
                        id="status"
                        name="status"
                        required>

                        <option value="aktif"
                            {{ old('status', $user->status) == 'aktif' ? 'selected' : '' }}>
                            Aktif
                        </option>

                        <option value="nonaktif"
                            {{ old('status', $user->status) == 'nonaktif' ? 'selected' : '' }}>
                            Nonaktif
                        </option>

                    </select>

                    @error('status')
                    <small class="form-error">
                        {{ $message }}
                    </small>
                    @enderror

                </div>

            </div>


            {{-- PASSWORD --}}
            <div class="password-section">

                <div class="section-title">

                    <div class="section-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>

                    <div>
                        <h3>Ubah Password</h3>
                        <p>
                            Kosongkan jika password tidak ingin diubah.
                        </p>
                    </div>

                </div>


                <div class="user-form-grid">

                    {{-- PASSWORD BARU --}}
                    <div class="form-group">

                        <label for="password">
                            Password Baru
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan password baru">

                        @error('password')
                        <small class="form-error">
                            {{ $message }}
                        </small>
                        @enderror

                    </div>


                    {{-- KONFIRMASI --}}
                    <div class="form-group">

                        <label for="password_confirmation">
                            Konfirmasi Password
                        </label>

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Ulangi password baru">

                    </div>

                </div>

            </div>


            {{-- BUTTON --}}
            <div class="edit-form-actions">

                <a
                    href="{{ route('admin.pengguna.index') }}"
                    class="btn-cancel">
                    Batal
                </a>

                <button
                    type="submit"
                    class="btn-save">
                    <i class="bi bi-check-lg"></i>
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>

@endsection