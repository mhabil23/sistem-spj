@extends('layouts.admin')

@php
$title = 'Pengaturan Profil Administrator';
@endphp

@push('styles')
    @vite(['resources/css/admin/profil.css'])
@endpush

@section('content')
<div class="adm-profil-wrapper">

    <!-- BREADCRUMB & PAGE HEADER -->
    <div class="adm-page-header">
        <div class="adm-breadcrumb">
            <a href="{{ route('admin.dashboard') }}">
                <i class="bi bi-house"></i> Dashboard
            </a>
            <i class="bi bi-chevron-right" style="font-size: 10px;"></i>
            <span>Pengaturan Profil</span>
        </div>

        <h1 style="margin: 6px 0;">Pengaturan Profil &amp; Akun</h1>
        <p>Kelola data identitas, kontak WhatsApp, dan kredensial login akun Administrator.</p>
    </div>

    @if ($errors->any())
        <div class="adm-callout warning" style="margin-bottom: 24px; border-left: 4px solid #f59e0b;">
            <i class="bi bi-exclamation-triangle-fill" style="font-size: 20px; color: #d97706;"></i>
            <div>
                <strong style="color: #92400e; font-size: 14px; display: block; margin-bottom: 4px;">Mohon periksa kembali formulir Anda:</strong>
                <ul style="margin: 0; padding-left: 20px; font-size: 13px; color: #b45309;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- MAIN 2-COLUMN GRID -->
    <div class="adm-profil-grid">

        <!-- LEFT SIDEBAR: PROFILE SUMMARY CARD -->
        <div class="adm-summary-card">
            <div class="adm-summary-banner"></div>
            <div class="adm-summary-body">
                <div class="adm-avatar-wrapper">
                    <div class="adm-avatar-ring">
                        <img id="avatarPreview" src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="adm-avatar-img">
                    </div>
                    <label for="avatarInput" class="adm-avatar-camera-btn" title="Unggah Foto Baru">
                        <i class="bi bi-camera-fill" style="font-size: 15px;"></i>
                    </label>
                </div>

                <h3 class="adm-summary-name">{{ $user->name }}</h3>
                <div class="adm-role-pill">
                    <i class="bi bi-shield-check"></i>
                    <span>Administrator Utama</span>
                </div>

                @if($user->avatar)
                <div style="margin-bottom: 16px;">
                    <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; color: #e11d48; cursor: pointer; padding: 4px 10px; border-radius: 6px; border: 1px solid #fecdd3; background: #fff1f2; transition: all 0.15s;">
                        <input type="checkbox" name="remove_avatar" form="formProfile" value="1" onchange="toggleRemoveAvatar(this)">
                        <span>Hapus Foto Kustom</span>
                    </label>
                </div>
                @endif

                <div class="adm-summary-meta-list">
                    <div class="adm-summary-meta-item">
                        <div class="adm-summary-meta-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div class="adm-summary-meta-content">
                            <div class="adm-summary-meta-label">Email Login</div>
                            <div class="adm-summary-meta-val" title="{{ $user->email }}">{{ $user->email }}</div>
                        </div>
                    </div>

                    <div class="adm-summary-meta-item">
                        <div class="adm-summary-meta-icon" style="color: #059669; background: #ecfdf5;">
                            <i class="bi bi-whatsapp"></i>
                        </div>
                        <div class="adm-summary-meta-content">
                            <div class="adm-summary-meta-label">Nomor WhatsApp</div>
                            <div class="adm-summary-meta-val">
                                @if($user->no_wa)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->no_wa) }}" target="_blank" style="color: #059669; text-decoration: none; display: flex; align-items: center; gap: 4px;">
                                        {{ $user->no_wa }} <i class="bi bi-box-arrow-up-right" style="font-size: 10px;"></i>
                                    </a>
                                @else
                                    <span style="color: #94a3b8; font-style: italic;">Belum diatur</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="adm-summary-meta-item">
                        <div class="adm-summary-meta-icon">
                            <i class="bi bi-calendar3"></i>
                        </div>
                        <div class="adm-summary-meta-content">
                            <div class="adm-summary-meta-label">Bergabung Sejak</div>
                            <div class="adm-summary-meta-val">{{ $user->created_at ? $user->created_at->format('d M Y') : 'Aktif' }}</div>
                        </div>
                    </div>

                    <div class="adm-summary-meta-item">
                        <div class="adm-summary-meta-icon" style="color: #059669; background: #ecfdf5;">
                            <i class="bi bi-shield-fill-check"></i>
                        </div>
                        <div class="adm-summary-meta-content">
                            <div class="adm-summary-meta-label">Status Akun</div>
                            <div class="adm-summary-meta-val" style="color: #059669;">
                                Terverifikasi &amp; Aktif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: EDIT FORM -->
        <div>
            <form id="formProfile" action="{{ route('admin.profil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Hidden file input for avatar -->
                <input type="file" id="avatarInput" name="avatar" accept="image/png, image/jpeg, image/jpg, image/webp" style="display: none;" onchange="previewAvatar(event)">

                <!-- CARD 1: INFORMASI IDENTITAS -->
                <div class="adm-form-card">
                    <div class="adm-form-card-header">
                        <div class="adm-card-header-icon blue">
                            <i class="bi bi-person"></i>
                        </div>
                        <div>
                            <h2>Informasi Identitas &amp; Kontak</h2>
                            <p>Data diri administrator dan kontak resmi.</p>
                        </div>
                    </div>
                    <div class="adm-form-card-body">
                        <div class="adm-form-row-2">
                            <!-- NAMA LENGKAP -->
                            <div class="adm-field-group">
                                <label class="adm-field-label" for="input_name">
                                    <span>Nama Lengkap <span style="color: #e11d48;">*</span></span>
                                </label>
                                <div class="adm-input-wrapper">
                                    <i class="bi bi-person adm-input-icon"></i>
                                    <input 
                                        type="text" 
                                        id="input_name" 
                                        name="name" 
                                        class="adm-input @error('name') has-error @enderror" 
                                        value="{{ old('name', $user->name) }}" 
                                        required 
                                        placeholder="Masukkan nama lengkap Anda">
                                </div>
                                @error('name')
                                    <div class="adm-error-hint">
                                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- NOMOR WHATSAPP -->
                            <div class="adm-field-group">
                                <label class="adm-field-label" for="input_no_wa">
                                    <span>Nomor WhatsApp (Aktif)</span>
                                    <span class="adm-field-badge">Kontak Darurat</span>
                                </label>
                                <div class="adm-input-wrapper">
                                    <i class="bi bi-whatsapp adm-input-icon" style="color: #059669;"></i>
                                    <input 
                                        type="text" 
                                        id="input_no_wa" 
                                        name="no_wa" 
                                        class="adm-input @error('no_wa') has-error @enderror" 
                                        value="{{ old('no_wa', $user->no_wa) }}" 
                                        placeholder="Contoh: 081234567890">
                                </div>
                                <div class="adm-helper-text">
                                    Nomor kontak aktif untuk menerima konfirmasi operasional.
                                </div>
                                @error('no_wa')
                                    <div class="adm-error-hint">
                                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- PERAN & HAK AKSES -->
                        <div class="adm-field-group" style="margin-bottom: 0;">
                            <label class="adm-field-label">
                                <span>Peran &amp; Hak Akses</span>
                                <span class="adm-field-badge">Hak Akses Penuh</span>
                            </label>
                            <div class="adm-input-wrapper">
                                <i class="bi bi-shield-lock adm-input-icon"></i>
                                <input 
                                    type="text" 
                                    class="adm-input" 
                                    value="Administrator Sistem (Superuser)" 
                                    disabled>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD 2: KREDENSIAL LOGIN & ALAMAT EMAIL -->
                <div class="adm-form-card">
                    <div class="adm-form-card-header">
                        <div class="adm-card-header-icon emerald">
                            <i class="bi bi-envelope-at"></i>
                        </div>
                        <div>
                            <h2>Kredensial Login &amp; Alamat Email</h2>
                            <p>Alamat email yang digunakan untuk masuk ke Sistem Informasi Penyelesaian Tagihan (SPJ).</p>
                        </div>
                    </div>
                    <div class="adm-form-card-body">
                        
                        <div class="adm-callout info">
                            <i class="bi bi-info-circle-fill"></i>
                            <div>
                                <strong>Ketentuan Penggantian Alamat Email:</strong>
                                <p style="margin: 4px 0 0 0;">
                                    Email adalah identitas login utama Anda. Jika Anda mengubah alamat email di bawah ini, <strong>wajib mengisi Kata Sandi Saat Ini</strong> pada bagian Keamanan Akun demi memvalidasi bahwa perubahan dilakukan oleh pemilik akun sah.
                                </p>
                            </div>
                        </div>

                        <div class="adm-field-group" style="margin-bottom: 0;">
                            <label class="adm-field-label" for="input_email">
                                <span>Alamat Email Login <span style="color: #e11d48;">*</span></span>
                                <span class="adm-field-badge highlight">Kredensial Masuk</span>
                            </label>
                            <div class="adm-input-wrapper">
                                <i class="bi bi-envelope adm-input-icon"></i>
                                <input 
                                    type="email" 
                                    id="input_email" 
                                    name="email" 
                                    class="adm-input @error('email') has-error @enderror" 
                                    value="{{ old('email', $user->email) }}" 
                                    required 
                                    placeholder="nama@email.com">
                            </div>
                            <div class="adm-helper-text">
                                Email saat ini: <strong style="color: #0f172a;">{{ $user->email }}</strong>.
                            </div>
                            <!-- Dynamic Notice saat email diubah -->
                            <div id="emailNoticeBadge" style="display: none; margin-top: 8px; padding: 10px 14px; background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; color: #92400e; font-size: 12px; line-height: 1.45;">
                                <div style="display: flex; align-items: flex-start; gap: 8px;">
                                    <i class="bi bi-exclamation-triangle" style="font-size: 16px; color: #d97706; margin-top: 1px;"></i>
                                    <div>
                                        <strong>Konfirmasi Kata Sandi Diperlukan:</strong>
                                        <div>Anda sedang mengubah alamat email. Wajib memasukkan <strong>Kata Sandi Saat Ini</strong> pada formulir Keamanan di bawah sebelum menyimpan.</div>
                                    </div>
                                </div>
                            </div>
                            @error('email')
                                <div class="adm-error-hint">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>
                </div>

                <!-- CARD 3: KEAMANAN & KATA SANDI -->
                <div class="adm-form-card">
                    <div class="adm-form-card-header">
                        <div class="adm-card-header-icon amber">
                            <i class="bi bi-key"></i>
                        </div>
                        <div>
                            <h2>Keamanan &amp; Kata Sandi Akun</h2>
                            <p>Konfirmasi sandi saat ini dan ubah kata sandi akun secara berkala.</p>
                        </div>
                    </div>
                    <div class="adm-form-card-body">
                        
                        <!-- KATA SANDI SAAT INI -->
                        <div class="adm-field-group" style="max-width: 520px; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px dashed #e2e8f0;">
                            <label class="adm-field-label" for="input_current_password">
                                <span>Kata Sandi Saat Ini</span>
                                <span class="adm-field-badge" style="color: #d97706; background: #fffbeb; border: 1px solid #fde68a;">Wajib jika ganti email / sandi</span>
                            </label>
                            <div class="adm-input-wrapper">
                                <i class="bi bi-key adm-input-icon"></i>
                                <input 
                                    type="password" 
                                    id="input_current_password" 
                                    name="current_password" 
                                    class="adm-input @error('current_password') has-error @enderror" 
                                    autocomplete="current-password" 
                                    placeholder="Masukkan kata sandi lama Anda untuk konfirmasi..." 
                                    style="padding-right: 44px;">
                                <button type="button" class="adm-password-toggle-btn" title="Lihat kata sandi">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="adm-helper-text">
                                Diperlukan untuk verifikasi keamanan saat Anda mengganti alamat email login atau ingin membuat sandi baru.
                            </div>
                            @error('current_password')
                                <div class="adm-error-hint">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- KATA SANDI BARU & KONFIRMASI -->
                        <div class="adm-form-row-2" style="margin-bottom: 0;">
                            <div class="adm-field-group" style="margin-bottom: 0;">
                                <label class="adm-field-label" for="input_new_password">
                                    <span>Kata Sandi Baru</span>
                                    <span class="adm-field-badge">Opsional</span>
                                </label>
                                <div class="adm-input-wrapper">
                                    <i class="bi bi-lock adm-input-icon"></i>
                                    <input 
                                        type="password" 
                                        id="input_new_password" 
                                        name="password" 
                                        class="adm-input @error('password') has-error @enderror" 
                                        autocomplete="new-password" 
                                        placeholder="Minimal 8 karakter..." 
                                        style="padding-right: 44px;">
                                    <button type="button" class="adm-password-toggle-btn" title="Lihat kata sandi">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div class="adm-helper-text">
                                    Kosongkan jika tidak ingin mengubah kata sandi Anda.
                                </div>
                                @error('password')
                                    <div class="adm-error-hint">
                                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="adm-field-group" style="margin-bottom: 0;">
                                <label class="adm-field-label" for="input_password_confirmation">
                                    <span>Konfirmasi Kata Sandi Baru</span>
                                </label>
                                <div class="adm-input-wrapper">
                                    <i class="bi bi-shield-check adm-input-icon"></i>
                                    <input 
                                        type="password" 
                                        id="input_password_confirmation" 
                                        name="password_confirmation" 
                                        class="adm-input" 
                                        autocomplete="new-password" 
                                        placeholder="Ketik ulang kata sandi baru..." 
                                        style="padding-right: 44px;">
                                    <button type="button" class="adm-password-toggle-btn" title="Lihat kata sandi">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div class="adm-helper-text">
                                    Pastikan karakter sama persis dengan kata sandi baru di samping.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- ACTIONS BAR -->
                <div class="adm-actions-bar">
                    <a href="{{ route('admin.dashboard') }}" class="adm-btn adm-btn-secondary">
                        <i class="bi bi-arrow-left"></i>
                        <span>Kembali ke Dashboard</span>
                    </a>
                    <button type="submit" class="adm-btn adm-btn-primary">
                        <i class="bi bi-check2-circle"></i>
                        <span>Simpan Perubahan Profil</span>
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
        const toggleButtons = document.querySelectorAll('.adm-password-toggle-btn');
        
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const input = this.previousElementSibling;
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                    this.title = 'Sembunyikan kata sandi';
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                    this.title = 'Tampilkan kata sandi';
                }
            });
        });

        // Validasi interaktif perubahan email
        const formProfile = document.getElementById('formProfile');
        const inputEmail = document.getElementById('input_email');
        const inputCurrentPassword = document.getElementById('input_current_password');
        const emailNoticeBadge = document.getElementById('emailNoticeBadge');
        const originalEmail = "{{ strtolower(trim($user->email)) }}";

        if (inputEmail && emailNoticeBadge) {
            function checkEmailChanged() {
                const currentVal = inputEmail.value.trim().toLowerCase();
                if (currentVal !== '' && currentVal !== originalEmail) {
                    emailNoticeBadge.style.display = 'block';
                } else {
                    emailNoticeBadge.style.display = 'none';
                }
            }

            inputEmail.addEventListener('input', checkEmailChanged);
            checkEmailChanged();
        }

        if (formProfile) {
            formProfile.addEventListener('submit', function(e) {
                const isEmailChanged = inputEmail && inputEmail.value.trim().toLowerCase() !== originalEmail;
                const isCurrentPassEmpty = inputCurrentPassword && inputCurrentPassword.value.trim() === '';

                if (isEmailChanged && isCurrentPassEmpty) {
                    e.preventDefault();
                    
                    if (inputCurrentPassword) {
                        inputCurrentPassword.classList.add('has-error');
                        inputCurrentPassword.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        inputCurrentPassword.focus();
                    }

                    let errorHint = document.getElementById('clientPasswordError');
                    if (!errorHint && inputCurrentPassword) {
                        errorHint = document.createElement('div');
                        errorHint.id = 'clientPasswordError';
                        errorHint.className = 'adm-error-hint';
                        errorHint.style.marginTop = '6px';
                        errorHint.innerHTML = '<i class="bi bi-exclamation-circle"></i> Konfirmasi kata sandi saat ini wajib diisi untuk mengesahkan perubahan alamat email login!';
                        inputCurrentPassword.closest('.adm-field-group').appendChild(errorHint);
                    } else if (errorHint) {
                        errorHint.style.display = 'flex';
                    }

                    alert('Perhatian: Anda sedang mengubah alamat email login. Harap masukkan "Kata Sandi Saat Ini" pada bagian Keamanan & Kata Sandi untuk mengonfirmasi perubahan ini.');
                }
            });
        }
    });
</script>
@endpush
