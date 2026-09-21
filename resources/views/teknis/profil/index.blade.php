@extends('layouts.teknis')

@php
$title = 'Profil Akun';
$subtitle = 'Perbarui data profil dan kata sandi Anda.';
@endphp

@section('content')

<div class="page-heading">
    <div>
        <h2>Pengaturan Profil</h2>
        <p>Pastikan data Anda selalu yang paling mutakhir.</p>
    </div>
</div>

@if (session('success'))
    <div style="background-color: #d1fae5; color: #065f46; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
        {{ session('success') }}
    </div>
@endif
@if ($errors->any())
    <div style="background-color: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
        <ul style="margin: 0; padding-left: 1.5rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="panel">
    <div class="panel-header">
        <div>
            <h2>Data Profil</h2>
        </div>
    </div>
    <div style="padding: 1.5rem;">
        <form action="{{ route('teknis.profil.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Nama Lengkap</label>
                <input type="text" name="name" required style="width: 100%; max-width: 400px; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; outline: none;" value="{{ old('name', $user->name) }}">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Alamat Email</label>
                <input type="email" name="email" required style="width: 100%; max-width: 400px; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; outline: none;" value="{{ old('email', $user->email) }}">
            </div>

            <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 2rem 0; max-width: 600px;">
            <h3 style="margin-bottom: 1rem; color: #111827;">Ubah Kata Sandi</h3>
            <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 1.5rem;">Kosongkan jika Anda tidak ingin mengubah kata sandi.</p>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Kata Sandi Saat Ini</label>
                <input type="password" name="current_password" style="width: 100%; max-width: 400px; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; outline: none;" autocomplete="current-password">
            </div>

            <div style="display: flex; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div style="flex: 1; max-width: 400px;">
                    <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Kata Sandi Baru</label>
                    <input type="password" name="password" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; outline: none;" autocomplete="new-password">
                </div>
                <div style="flex: 1; max-width: 400px;">
                    <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Konfirmasi Kata Sandi</label>
                    <input type="password" name="password_confirmation" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; outline: none;" autocomplete="new-password">
                </div>
            </div>

            <div style="margin-top: 2rem;">
                <button type="submit" class="btn-primary" style="border: none; cursor: pointer; font-family: inherit; font-size: 1rem;">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@endsection
