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
            <form action="{{ route('teknis.profil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- SECTION 0: FOTO PROFIL --}}
                <div class="profile-avatar-section" style="display: flex; align-items: center; gap: 24px; padding-bottom: 24px; margin-bottom: 24px; border-bottom: 1px solid var(--dash-border); flex-wrap: wrap;">
                    <div style="position: relative;">
                        <div style="width: 88px; height: 88px; border-radius: 50%; padding: 3px; background: linear-gradient(135deg, #0284c7, #10b981); box-shadow: 0 4px 14px rgba(2, 132, 199, 0.25);">
                            <img id="avatarPreview" src="{{ $user->avatar_url }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; background: var(--dash-card-bg); display: block;">
                        </div>
                        <label for="avatarInput" title="Unggah Foto Baru" style="position: absolute; bottom: 0; right: 0; width: 30px; height: 30px; border-radius: 50%; background: var(--dash-btn-primary-bg); color: #ffffff; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,0.25); transition: transform 0.15s ease;">
                            <i class="bi bi-camera-fill" style="font-size: 14px;"></i>
                        </label>
                    </div>

                    <div style="flex: 1; min-width: 240px;">
                        <h4 style="margin: 0 0 6px 0; font-size: 15px; font-weight: 700; color: var(--dash-text-dark);">Foto Profil</h4>
                        <p style="margin: 0 0 12px 0; font-size: 13px; color: var(--dash-text-muted); line-height: 1.4;">
                            Format gambar yang didukung: JPG, JPEG, PNG, atau WEBP. Maksimal ukuran file 2 MB.
                        </p>
                        
                        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                            <input type="file" id="avatarInput" name="avatar" accept="image/png, image/jpeg, image/jpg, image/webp" style="display: none;" onchange="previewAvatar(event)">
                            <button type="button" onclick="document.getElementById('avatarInput').click()" style="height: 36px; padding: 0 16px; font-size: 13px; font-weight: 600; background: var(--dash-input-bg); color: var(--dash-text-dark); border: 1px solid var(--dash-input-border); border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                                <i class="bi bi-cloud-arrow-up" style="font-size: 15px;"></i> Pilih Foto Baru
                            </button>

                            @if($user->avatar)
                            <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; color: #ef4444; cursor: pointer; padding: 6px 12px; border-radius: 8px; border: 1px solid var(--dash-peach-border); background: var(--dash-peach-bg); transition: 0.2s;">
                                <input type="checkbox" name="remove_avatar" value="1" onchange="toggleRemoveAvatar(this)">
                                <span>Hapus Foto Kustom</span>
                            </label>
                            @endif
                        </div>
                    </div>
                </div>

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
                            <label class="profile-form-label" for="profile_email">
                                <span>Alamat Email</span>
                                <span style="font-size: 11px; font-weight: 600; color: #0284c7; background: #e0f2fe; padding: 2px 8px; border-radius: 4px; margin-left: 6px;">Kredensial Login</span>
                            </label>
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
                            <div class="profile-form-hint" style="color: #64748b; font-size: 11.5px; margin-top: 4px;">
                                <i class="bi bi-shield-check" style="color: #0284c7;"></i> Wajib isi kata sandi saat ini jika Anda mengubah email login.
                            </div>
                            @error('email')
                                <div class="profile-invalid-feedback">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="profile-grid-2" style="margin-top: 16px;">
                        <div class="profile-form-group" style="margin-bottom: 0;">
                            <label class="profile-form-label" for="profile_no_wa">Nomor WhatsApp (Aktif)</label>
                            <div class="profile-input-wrapper">
                                <i class="bi bi-whatsapp profile-input-icon" style="color: #25d366;"></i>
                                <input 
                                    type="text" 
                                    id="profile_no_wa" 
                                    name="no_wa" 
                                    class="profile-input @error('no_wa') is-invalid @enderror" 
                                    value="{{ old('no_wa', $user->no_wa) }}" 
                                    placeholder="Contoh: 081234567890">
                            </div>
                            <div class="profile-form-hint">
                                <i class="bi bi-info-circle"></i>
                                Untuk menerima notifikasi otomatis saat SPJ disetujui / direvisi.
                            </div>
                            @error('no_wa')
                                <div class="profile-invalid-feedback">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
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
                        <label class="profile-form-label" for="current_password">
                            <span>Kata Sandi Saat Ini</span>
                            <span style="font-size: 11px; font-weight: 600; color: #d97706; background: #fffbeb; padding: 2px 8px; border-radius: 4px; border: 1px solid #fde68a; margin-left: 8px;">Wajib jika ganti email / sandi</span>
                        </label>
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
                        <div class="profile-form-hint" style="color: #64748b; font-size: 11.5px; margin-top: 4px;">
                            <i class="bi bi-info-circle"></i> Diperlukan untuk validasi keamanan saat mengganti email login atau membuat kata sandi baru.
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
    function previewAvatar(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatarPreview');
                if (preview) {
                    preview.src = e.target.result;
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function toggleRemoveAvatar(checkbox) {
        const preview = document.getElementById('avatarPreview');
        if (preview) {
            preview.style.opacity = checkbox.checked ? '0.35' : '1';
        }
    }

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
