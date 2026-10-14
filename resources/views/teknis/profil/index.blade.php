@extends('layouts.teknis')

@php
$title = 'Profil Akun';
$subtitle = 'Perbarui data profil dan kata sandi Anda.';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/teknis/profil.css'])
@endpush

<div class="profile-page-header">
    <h2>Pengaturan Profil</h2>
    <p>Kelola informasi identitas, data akun, dan keamanan kata sandi Anda.</p>
</div>

{{-- Flash Messages --}}
@if (session('success'))
    <div class="profile-alert profile-alert-success">
        <i class="bi bi-check-circle-fill"></i>
        <div>
            <strong>Berhasil!</strong> {{ session('success') }}
        </div>
    </div>
@endif

@if (isset($errors) && $errors->any())
    <div class="profile-alert profile-alert-danger">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>
            <strong>Terdapat beberapa kesalahan:</strong>
            <ul style="margin: 6px 0 0 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

{{-- Form Panel Profil & Keamanan --}}
<div class="profile-card">
        <div class="profile-card-header">
            <div>
                <h3><i class="bi bi-person-lines-fill"></i> Data Profil & Keamanan</h3>
                <p>Perbarui data identitas pribadi atau kata sandi akun Anda di bawah ini.</p>
            </div>
        </div>

        <div class="profile-card-body">
            <form action="{{ route('teknis.profil.update') }}" method="POST">
                @csrf
                @method('PUT')

                {{-- SECTION 1: DATA IDENTITAS --}}
                <div class="profile-form-section">
                    <h4 class="profile-section-title">
                        <i class="bi bi-person-vcard"></i> Informasi Identitas
                    </h4>
                    <p class="profile-section-subtitle">
                        Data identitas utama yang digunakan saat mengajukan dan mengelola dokumen SPJ.
                    </p>

                    <div class="profile-grid-2">
                        <div class="profile-form-group">
                            <label class="profile-form-label" for="profile_name">Nama Lengkap</label>
                            <div class="profile-input-wrapper">
                                <i class="bi bi-person profile-input-icon"></i>
                                <input 
                                    type="text" 
                                    id="profile_name" 
                                    name="name" 
                                    class="profile-input @error('name') is-invalid @enderror" 
                                    value="{{ old('name', $user->name) }}" 
                                    required 
                                    placeholder="Masukkan nama lengkap">
                            </div>
                            @error('name')
                                <div class="profile-invalid-feedback">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="profile-form-group">
                            <label class="profile-form-label" for="profile_email">Alamat Email</label>
                            <div class="profile-input-wrapper">
                                <i class="bi bi-envelope profile-input-icon"></i>
                                <input 
                                    type="email" 
                                    id="profile_email" 
                                    name="email" 
                                    class="profile-input @error('email') is-invalid @enderror" 
                                    value="{{ old('email', $user->email) }}" 
                                    required 
                                    placeholder="nama@email.com">
                            </div>
                            @error('email')
                                <div class="profile-invalid-feedback">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="profile-form-group" style="margin-bottom: 0;">
                        <label class="profile-form-label">Peran & Hak Akses</label>
                        <div class="profile-input-wrapper">
                            <i class="bi bi-shield-check profile-input-icon"></i>
                            <input 
                                type="text" 
                                class="profile-input profile-input-readonly" 
                                value="Tim Teknis (Pengaju SPJ)" 
                                readonly 
                                disabled>
                        </div>
                        <div class="profile-form-hint">
                            <i class="bi bi-info-circle"></i>
                            Peran hak akses dikelola langsung oleh Administrator sistem.
                        </div>
                    </div>
                </div>

                <hr class="profile-divider" style="margin: 28px 0;">

                {{-- SECTION 2: KATA SANDI --}}
                <div class="profile-form-section" style="margin-bottom: 12px;">
                    <h4 class="profile-section-title">
                        <i class="bi bi-shield-lock"></i> Ubah Kata Sandi
                    </h4>
                    <p class="profile-section-subtitle">
                        Kosongkan bagian ini jika Anda tidak ingin melakukan pergantian kata sandi.
                    </p>

                    <div class="profile-form-group profile-half-width">
                        <label class="profile-form-label" for="current_password">Kata Sandi Saat Ini</label>
                        <div class="profile-input-wrapper">
                            <i class="bi bi-key profile-input-icon"></i>
                            <input 
                                type="password" 
                                id="current_password" 
                                name="current_password" 
                                class="profile-input has-toggle @error('current_password') is-invalid @enderror" 
                                autocomplete="current-password" 
                                placeholder="Masukkan kata sandi lama Anda">
                            <button type="button" class="profile-toggle-password" data-target="current_password" title="Lihat kata sandi">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <div class="profile-invalid-feedback">
                                <i class="bi bi-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="profile-grid-2">
                        <div class="profile-form-group">
                            <label class="profile-form-label" for="new_password">Kata Sandi Baru</label>
                            <div class="profile-input-wrapper">
                                <i class="bi bi-lock profile-input-icon"></i>
                                <input 
                                    type="password" 
                                    id="new_password" 
                                    name="password" 
                                    class="profile-input has-toggle @error('password') is-invalid @enderror" 
                                    autocomplete="new-password" 
                                    placeholder="Minimal 8 karakter baru">
                                <button type="button" class="profile-toggle-password" data-target="new_password" title="Lihat kata sandi">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="profile-invalid-feedback">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="profile-form-group">
                            <label class="profile-form-label" for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
                            <div class="profile-input-wrapper">
                                <i class="bi bi-lock-fill profile-input-icon"></i>
                                <input 
                                    type="password" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    class="profile-input has-toggle" 
                                    autocomplete="new-password" 
                                    placeholder="Ulangi kata sandi baru">
                                <button type="button" class="profile-toggle-password" data-target="password_confirmation" title="Lihat kata sandi">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="profile-form-hint">
                        <i class="bi bi-info-circle"></i>
                        Jika mengisi kata sandi baru, Anda wajib memasukkan kata sandi saat ini untuk verifikasi keamanan.
                    </div>
                </div>

                {{-- Action Button --}}
                <div class="profile-form-footer">
                    <button type="submit" class="profile-btn-save">
                        <i class="bi bi-check2-circle"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButtons = document.querySelectorAll('.profile-toggle-password');
        
        toggleButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');
                
                if (!input) return;

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                    this.title = 'Sembunyikan kata sandi';
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                    this.title = 'Lihat kata sandi';
                }
            });
        });
    });
</script>
@endpush
@endsection
