@extends('layouts.bendahara')

@php
$title = 'Pengaturan Profil Akun';
@endphp

@push('styles')
    @vite(['resources/css/bendahara/profil.css'])
@endpush

@section('content')
<div class="bnd-profil-wrapper">

    <!-- BREADCRUMB & PAGE HEADER -->
    <div class="bnd-header-wrapper">
        <div class="bnd-breadcrumb">
            <a href="{{ route('bendahara.dashboard') }}">
                <ion-icon name="home-outline"></ion-icon> Dashboard
            </a>
            <ion-icon name="chevron-forward-outline"></ion-icon>
            <span>Pengaturan Profil</span>
        </div>

        <div class="bnd-page-header">
            <div class="bnd-header-title-box">
                <h1>
                    Pengaturan Profil &amp; Akun
                </h1>
                <p>
                    Kelola data identitas, kontak WhatsApp penerima notifikasi, dan kredensial login akun Anda.
                </p>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="bnd-callout warning" style="margin-bottom: 24px; border-left: 4px solid #f59e0b;">
            <ion-icon name="alert-circle" style="font-size: 24px; color: #d97706;"></ion-icon>
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
    <div class="bnd-profil-grid">

        <!-- LEFT SIDEBAR: PROFILE SUMMARY CARD -->
        <div class="bnd-summary-card">
            <div class="bnd-summary-banner"></div>
            <div class="bnd-summary-body">
                <div class="bnd-avatar-wrapper">
                    <div class="bnd-avatar-ring">
                        <img id="avatarPreview" src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="bnd-avatar-img">
                    </div>
                    <label for="avatarInput" class="bnd-avatar-camera-btn" title="Unggah Foto Baru">
                        <ion-icon name="camera" style="font-size: 17px;"></ion-icon>
                    </label>
                </div>

                <h3 class="bnd-summary-name">{{ $user->name }}</h3>
                <div class="bnd-role-pill">
                    <span class="bnd-pulse-dot"></span>
                    <span>Bendahara Pengeluaran</span>
                </div>

                @if($user->avatar)
                <div style="margin-bottom: 16px;">
                    <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; color: #e11d48; cursor: pointer; padding: 4px 10px; border-radius: 6px; border: 1px solid #fecdd3; background: #fff1f2; transition: all 0.15s;">
                        <input type="checkbox" name="remove_avatar" form="formProfile" value="1" onchange="toggleRemoveAvatar(this)">
                        <span>Hapus Foto Kustom</span>
                    </label>
                </div>
                @endif

                <div class="bnd-summary-meta-list">
                    <div class="bnd-summary-meta-item">
                        <div class="bnd-summary-meta-icon">
                            <ion-icon name="mail-outline"></ion-icon>
                        </div>
                        <div class="bnd-summary-meta-content">
                            <div class="bnd-summary-meta-label">Email Login</div>
                            <div class="bnd-summary-meta-val" title="{{ $user->email }}">{{ $user->email }}</div>
                        </div>
                    </div>

                    <div class="bnd-summary-meta-item">
                        <div class="bnd-summary-meta-icon" style="color: #059669; background: #ecfdf5;">
                            <ion-icon name="logo-whatsapp"></ion-icon>
                        </div>
                        <div class="bnd-summary-meta-content">
                            <div class="bnd-summary-meta-label">Nomor WhatsApp</div>
                            <div class="bnd-summary-meta-val">
                                @if($user->no_wa)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->no_wa) }}" target="_blank" style="color: #059669; text-decoration: none; display: flex; align-items: center; gap: 4px;">
                                        {{ $user->no_wa }} <ion-icon name="open-outline" style="font-size: 12px;"></ion-icon>
                                    </a>
                                @else
                                    <span style="color: #94a3b8; font-style: italic;">Belum diatur</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="bnd-summary-meta-item">
                        <div class="bnd-summary-meta-icon">
                            <ion-icon name="calendar-outline"></ion-icon>
                        </div>
                        <div class="bnd-summary-meta-content">
                            <div class="bnd-summary-meta-label">Bergabung Sejak</div>
                            <div class="bnd-summary-meta-val">{{ $user->created_at ? $user->created_at->format('d M Y') : 'Aktif' }}</div>
                        </div>
                    </div>

                    <div class="bnd-summary-meta-item">
                        <div class="bnd-summary-meta-icon" style="color: #059669; background: #ecfdf5;">
                            <ion-icon name="shield-checkmark-outline"></ion-icon>
                        </div>
                        <div class="bnd-summary-meta-content">
                            <div class="bnd-summary-meta-label">Status Akun</div>
                            <div class="bnd-summary-meta-val" style="color: #059669;">
                                Terverifikasi &amp; Aktif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bnd-summary-security-box">
                    <ion-icon name="shield-checkmark"></ion-icon>
                    <p>
                        <strong>Keamanan Akun:</strong> Alamat email Anda merupakan username login resmi. Pastikan selalu menggunakan email yang valid dan aktif.
                    </p>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: EDIT FORM -->
        <div>
            <form id="formProfile" action="{{ route('bendahara.profil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Hidden file input for avatar -->
                <input type="file" id="avatarInput" name="avatar" accept="image/png, image/jpeg, image/jpg, image/webp" style="display: none;" onchange="previewAvatar(event)">

                <!-- CARD 1: INFORMASI IDENTITAS -->
                <div class="bnd-form-card">
                    <div class="bnd-form-card-header">
                        <div class="bnd-card-header-icon blue">
                            <ion-icon name="person-outline"></ion-icon>
                        </div>
                        <div>
                            <h2>Informasi Identitas &amp; Kontak</h2>
                            <p>Data diri bendahara dan kontak untuk integrasi notifikasi pencairan dana.</p>
                        </div>
                    </div>
                    <div class="bnd-form-card-body">
                        <div class="bnd-form-row-2">
                            <!-- NAMA LENGKAP -->
                            <div class="bnd-field-group">
                                <label class="bnd-field-label" for="input_name">
                                    <span>Nama Lengkap <span style="color: #e11d48;">*</span></span>
                                </label>
                                <div class="bnd-input-wrapper">
                                    <ion-icon name="person-outline" class="bnd-input-icon"></ion-icon>
                                    <input 
                                        type="text" 
                                        id="input_name" 
                                        name="name" 
                                        class="bnd-input @error('name') has-error @enderror" 
                                        value="{{ old('name', $user->name) }}" 
                                        required 
                                        placeholder="Masukkan nama lengkap Anda">
                                </div>
                                @error('name')
                                    <div class="bnd-error-hint">
                                        <ion-icon name="warning-outline"></ion-icon> {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- NOMOR WHATSAPP -->
                            <div class="bnd-field-group">
                                <label class="bnd-field-label" for="input_no_wa">
                                    <span>Nomor WhatsApp (Aktif)</span>
                                    <span class="bnd-field-badge">Notifikasi Otomatis</span>
                                </label>
                                <div class="bnd-input-wrapper">
                                    <ion-icon name="logo-whatsapp" class="bnd-input-icon" style="color: #059669;"></ion-icon>
                                    <input 
                                        type="text" 
                                        id="input_no_wa" 
                                        name="no_wa" 
                                        class="bnd-input @error('no_wa') has-error @enderror" 
                                        value="{{ old('no_wa', $user->no_wa) }}" 
                                        placeholder="Contoh: 081234567890">
                                </div>
                                <div class="bnd-helper-text">
                                    Digunakan saat mengirim atau menerima pesan konfirmasi verifikasi SPJ.
                                </div>
                                @error('no_wa')
                                    <div class="bnd-error-hint">
                                        <ion-icon name="warning-outline"></ion-icon> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- PERAN & HAK AKSES (READONLY) -->
                        <div class="bnd-field-group" style="margin-bottom: 0;">
                            <label class="bnd-field-label">
                                <span>Peran &amp; Hak Akses</span>
                                <span class="bnd-field-badge">Ditentukan Administrator</span>
                            </label>
                            <div class="bnd-input-wrapper">
                                <ion-icon name="shield-outline" class="bnd-input-icon"></ion-icon>
                                <input 
                                    type="text" 
                                    class="bnd-input" 
                                    value="Bendahara Pengeluaran (Maker BRI QLOLA &amp; Verifikator Pencairan)" 
                                    disabled>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD 2: KREDENSIAL LOGIN & ALAMAT EMAIL -->
                <div class="bnd-form-card">
                    <div class="bnd-form-card-header">
                        <div class="bnd-card-header-icon emerald">
                            <ion-icon name="mail-unread-outline"></ion-icon>
                        </div>
                        <div>
                            <h2>Kredensial Login &amp; Alamat Email</h2>
                            <p>Alamat email yang digunakan untuk masuk ke Sistem Informasi Penyelesaian Tagihan (SPJ).</p>
                        </div>
                    </div>
                    <div class="bnd-form-card-body">
                        
                        <!-- SECURITY CALLOUT NOTICE -->
                        <div class="bnd-callout info">
                            <ion-icon name="information-circle"></ion-icon>
                            <div>
                                <strong>Ketentuan Penggantian Alamat Email:</strong>
                                <p style="margin: 4px 0 0 0;">
                                    Email adalah identitas login utama Anda. Jika Anda mengubah alamat email di bawah ini, <strong>wajib mengisi Kata Sandi Saat Ini</strong> pada bagian Keamanan Akun demi memvalidasi bahwa perubahan dilakukan oleh pemilik akun sah.
                                </p>
                            </div>
                        </div>

                        <div class="bnd-field-group" style="margin-bottom: 0;">
                            <label class="bnd-field-label" for="input_email">
                                <span>Alamat Email Login <span style="color: #e11d48;">*</span></span>
                                <span class="bnd-field-badge highlight">Kredensial Masuk</span>
                            </label>
                            <div class="bnd-input-wrapper">
                                <ion-icon name="mail-outline" class="bnd-input-icon"></ion-icon>
                                <input 
                                    type="email" 
                                    id="input_email" 
                                    name="email" 
                                    class="bnd-input @error('email') has-error @enderror" 
                                    value="{{ old('email', $user->email) }}" 
                                    required 
                                    placeholder="nama@email.com">
                            </div>
                            <div class="bnd-helper-text">
                                Email saat ini: <strong style="color: #0f172a;">{{ $user->email }}</strong>. Gunakan email dinas atau pribadi yang selalu dapat diakses.
                            </div>
                            @error('email')
                                <div class="bnd-error-hint">
                                    <ion-icon name="warning-outline"></ion-icon> {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>
                </div>

                <!-- CARD 3: KEAMANAN & KATA SANDI -->
                <div class="bnd-form-card">
                    <div class="bnd-form-card-header">
                        <div class="bnd-card-header-icon amber">
                            <ion-icon name="lock-closed-outline"></ion-icon>
                        </div>
                        <div>
                            <h2>Keamanan &amp; Kata Sandi Akun</h2>
                            <p>Konfirmasi sandi saat ini dan ubah kata sandi akun secara berkala.</p>
                        </div>
                    </div>
                    <div class="bnd-form-card-body">
                        
                        <!-- KATA SANDI SAAT INI (PENTING) -->
                        <div class="bnd-field-group" style="max-width: 520px; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px dashed #e2e8f0;">
                            <label class="bnd-field-label" for="input_current_password">
                                <span>Kata Sandi Saat Ini</span>
                                <span class="bnd-field-badge" style="color: #d97706; background: #fffbeb; border: 1px solid #fde68a;">Wajib jika ganti email / sandi</span>
                            </label>
                            <div class="bnd-input-wrapper">
                                <ion-icon name="key-outline" class="bnd-input-icon"></ion-icon>
                                <input 
                                    type="password" 
                                    id="input_current_password" 
                                    name="current_password" 
                                    class="bnd-input @error('current_password') has-error @enderror" 
                                    autocomplete="current-password" 
                                    placeholder="Masukkan kata sandi lama Anda untuk konfirmasi..." 
                                    style="padding-right: 44px;">
                                <button type="button" class="bnd-password-toggle-btn" title="Lihat kata sandi">
                                    <ion-icon name="eye-outline" style="font-size: 19px;"></ion-icon>
                                </button>
                            </div>
                            <div class="bnd-helper-text">
                                Diperlukan untuk verifikasi keamanan saat Anda mengganti alamat email login atau ingin membuat sandi baru.
                            </div>
                            @error('current_password')
                                <div class="bnd-error-hint">
                                    <ion-icon name="warning-outline"></ion-icon> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- KATA SANDI BARU & KONFIRMASI -->
                        <div class="bnd-form-row-2" style="margin-bottom: 0;">
                            <div class="bnd-field-group" style="margin-bottom: 0;">
                                <label class="bnd-field-label" for="input_new_password">
                                    <span>Kata Sandi Baru</span>
                                    <span class="bnd-field-badge">Opsional</span>
                                </label>
                                <div class="bnd-input-wrapper">
                                    <ion-icon name="lock-closed-outline" class="bnd-input-icon"></ion-icon>
                                    <input 
                                        type="password" 
                                        id="input_new_password" 
                                        name="password" 
                                        class="bnd-input @error('password') has-error @enderror" 
                                        autocomplete="new-password" 
                                        placeholder="Minimal 8 karakter..." 
                                        style="padding-right: 44px;">
                                    <button type="button" class="bnd-password-toggle-btn" title="Lihat kata sandi">
                                        <ion-icon name="eye-outline" style="font-size: 19px;"></ion-icon>
                                    </button>
                                </div>
                                <div class="bnd-helper-text">
                                    Kosongkan jika tidak ingin mengubah kata sandi Anda.
                                </div>
                                @error('password')
                                    <div class="bnd-error-hint">
                                        <ion-icon name="warning-outline"></ion-icon> {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="bnd-field-group" style="margin-bottom: 0;">
                                <label class="bnd-field-label" for="input_password_confirmation">
                                    <span>Konfirmasi Kata Sandi Baru</span>
                                </label>
                                <div class="bnd-input-wrapper">
                                    <ion-icon name="checkmark-done-outline" class="bnd-input-icon"></ion-icon>
                                    <input 
                                        type="password" 
                                        id="input_password_confirmation" 
                                        name="password_confirmation" 
                                        class="bnd-input" 
                                        autocomplete="new-password" 
                                        placeholder="Ketik ulang kata sandi baru..." 
                                        style="padding-right: 44px;">
                                    <button type="button" class="bnd-password-toggle-btn" title="Lihat kata sandi">
                                        <ion-icon name="eye-outline" style="font-size: 19px;"></ion-icon>
                                    </button>
                                </div>
                                <div class="bnd-helper-text">
                                    Pastikan karakter sama persis dengan kata sandi baru di samping.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- ACTIONS BAR -->
                <div class="bnd-actions-bar">
                    <a href="{{ route('bendahara.dashboard') }}" class="bnd-btn bnd-btn-secondary">
                        <ion-icon name="arrow-back-outline"></ion-icon>
                        <span>Kembali ke Dashboard</span>
                    </a>
                    <button type="submit" class="bnd-btn bnd-btn-primary">
                        <ion-icon name="save-outline" style="font-size: 18px;"></ion-icon>
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
        const toggleButtons = document.querySelectorAll('.bnd-password-toggle-btn');
        
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
