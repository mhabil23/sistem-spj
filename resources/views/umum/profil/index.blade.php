@extends('layouts.umum')

@php
$title = 'Profil Akun';
$subtitle = 'Perbarui data profil dan kata sandi Anda.';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/umum/profil.css'])
@endpush

<div style="padding-top: 8px;">
    <!-- Page Heading -->
    <div style="margin-bottom: 24px;">
        <h2 style="font-size: 24px; font-weight: 700; color: var(--dash-text-dark); margin: 0 0 4px 0; letter-spacing: -0.01em;">Pengaturan Profil Umum</h2>
        <p style="color: var(--dash-text-muted); font-size: 14px; margin: 0;">Kelola informasi profil, kontak WhatsApp verifikator, dan keamanan sandi akun Anda.</p>
    </div>

    @if ($errors->any())
        <div class="alert-box alert-error" style="margin: 0 0 24px 0; display: block;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px; font-weight: 600;">
                <ion-icon name="warning" style="font-size: 18px;"></ion-icon> Terdapat beberapa kesalahan pengisian:
            </div>
            <ul style="margin: 0; padding-left: 24px; font-size: 13px; line-height: 1.5;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="panel-card">
        <div class="panel-header">
            <div>
                <h2>Data Profil & Akun</h2>
                <p>Perbarui informasi identitas dan nomor WhatsApp verifikator.</p>
            </div>
        </div>
        <div class="panel-body">
            <form action="{{ route('umum.profil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Avatar / Foto Profil Section -->
                <div class="avatar-upload-container" style="display: flex; align-items: center; gap: 24px; margin-bottom: 28px; padding-bottom: 24px; border-bottom: 1px solid var(--dash-border); flex-wrap: wrap;">
                    <div style="position: relative;">
                        <div class="profile-avatar-preview-ring" style="width: 88px; height: 88px; border-radius: 50%; padding: 3px; background: linear-gradient(135deg, #0284c7, #10b981); box-shadow: 0 4px 14px rgba(2, 132, 199, 0.25);">
                            <img id="avatarPreview" src="{{ $user->avatar_url }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; background: var(--dash-card-bg); display: block;">
                        </div>
                        <label for="avatarInput" title="Unggah Foto Baru" style="position: absolute; bottom: 0; right: 0; width: 30px; height: 30px; border-radius: 50%; background: var(--dash-btn-primary-bg); color: #ffffff; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,0.25); transition: transform 0.15s ease;">
                            <ion-icon name="camera" style="font-size: 16px;"></ion-icon>
                        </label>
                    </div>

                    <div style="flex: 1; min-width: 240px;">
                        <h4 style="margin: 0 0 6px 0; font-size: 15px; font-weight: 700; color: var(--dash-text-dark);">Foto Profil</h4>
                        <p style="margin: 0 0 12px 0; font-size: 13px; color: var(--dash-text-muted); line-height: 1.4;">
                            Format gambar yang didukung: JPG, JPEG, PNG, atau WEBP. Maksimal ukuran file 2 MB.
                        </p>
                        
                        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                            <input type="file" id="avatarInput" name="avatar" accept="image/png, image/jpeg, image/jpg, image/webp" style="display: none;" onchange="previewAvatar(event)">
                            <button type="button" onclick="document.getElementById('avatarInput').click()" class="btn-dash-primary" style="height: 36px; padding: 0 16px; font-size: 12px;">
                                <ion-icon name="cloud-upload-outline" style="font-size: 16px;"></ion-icon> Pilih Foto Baru
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
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label class="filter-label" style="font-size: 13px; color: var(--dash-text-dark);">Nama Lengkap</label>
                        <input type="text" name="name" required class="dash-input" value="{{ old('name', $user->name) }}">
                    </div>

                    <div>
                        <label class="filter-label" style="font-size: 13px; color: var(--dash-text-dark);">Alamat Email</label>
                        <input type="email" name="email" required class="dash-input" value="{{ old('email', $user->email) }}">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 24px;">
                    <div>
                        <label class="filter-label" style="font-size: 13px; color: var(--dash-text-dark);">Nomor WhatsApp (Aktif)</label>
                        <input type="text" name="no_wa" class="dash-input" value="{{ old('no_wa', $user->no_wa) }}" placeholder="Contoh: 081234567890">
                        <small style="color: var(--dash-text-muted); font-size: 11px; margin-top: 5px; display: block;">Digunakan untuk menerima notifikasi pengajuan SPJ baru dan komunikasi langsung.</small>
                    </div>

                    <div>
                        <label class="filter-label" style="font-size: 13px; color: var(--dash-text-dark);">Peran & Hak Akses</label>
                        <input type="text" class="dash-input" value="Verifikator Bagian Umum" disabled style="opacity: 0.7; cursor: not-allowed; background-color: var(--dash-table-header-bg);">
                    </div>
                </div>

                <hr style="border: 0; border-top: 1px solid var(--dash-border); margin: 30px 0;">
                
                <h3 style="margin: 0 0 4px 0; color: var(--dash-text-dark); font-size: 16px; font-weight: 700;">Keamanan & Kata Sandi</h3>
                <p style="color: var(--dash-text-muted); font-size: 13px; margin: 0 0 20px 0;">Kosongkan bidang berikut jika Anda tidak bermaksud mengganti kata sandi saat ini.</p>

                <div style="max-width: 460px; margin-bottom: 20px;">
                    <label class="filter-label" style="font-size: 13px; color: var(--dash-text-dark);">Kata Sandi Saat Ini</label>
                    <div style="position: relative;">
                        <input type="password" name="current_password" class="dash-input" autocomplete="current-password" placeholder="Masukkan kata sandi lama..." style="padding-right: 42px;">
                        <button type="button" class="btn-toggle-password" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--dash-text-muted); cursor: pointer; display: flex; padding: 4px;" title="Tampilkan kata sandi">
                            <ion-icon name="eye-outline" style="font-size: 18px;"></ion-icon>
                        </button>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 24px;">
                    <div>
                        <label class="filter-label" style="font-size: 13px; color: var(--dash-text-dark);">Kata Sandi Baru</label>
                        <div style="position: relative;">
                            <input type="password" name="password" class="dash-input" autocomplete="new-password" placeholder="Minimal 8 karakter..." style="padding-right: 42px;">
                            <button type="button" class="btn-toggle-password" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--dash-text-muted); cursor: pointer; display: flex; padding: 4px;" title="Tampilkan kata sandi">
                                <ion-icon name="eye-outline" style="font-size: 18px;"></ion-icon>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="filter-label" style="font-size: 13px; color: var(--dash-text-dark);">Konfirmasi Kata Sandi Baru</label>
                        <div style="position: relative;">
                            <input type="password" name="password_confirmation" class="dash-input" autocomplete="new-password" placeholder="Ketik ulang kata sandi baru..." style="padding-right: 42px;">
                            <button type="button" class="btn-toggle-password" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--dash-text-muted); cursor: pointer; display: flex; padding: 4px;" title="Tampilkan kata sandi">
                                <ion-icon name="eye-outline" style="font-size: 18px;"></ion-icon>
                            </button>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 32px; display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn-dash-primary" style="height: 46px; padding: 0 28px; font-size: 14px;">
                        <ion-icon name="save-outline" style="font-size: 18px;"></ion-icon> Simpan Perubahan Profil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

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
        const toggleButtons = document.querySelectorAll('.btn-toggle-password');
        
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const input = this.previousElementSibling;
                const icon = this.querySelector('ion-icon');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.name = 'eye-off-outline';
                    this.title = 'Sembunyikan kata sandi';
                } else {
                    input.type = 'password';
                    icon.name = 'eye-outline';
                    this.title = 'Tampilkan kata sandi';
                }
            });
        });
    });
</script>
@endpush
