@extends('layouts.bendahara')

@php
$title = 'Profil Akun';
$subtitle = 'Perbarui data profil dan kata sandi Anda.';
@endphp

@section('content')
@push('styles')
    @vite(['resources/css/bendahara/profil.css'])
    <style>
        .profil-panel {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #e2e8f0;
            margin-bottom: 24px;
            overflow: hidden;
            width: 100%;
        }

        .profil-panel-header {
            padding: 24px;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .profil-panel-header h2 {
            font-size: 18px;
            color: #0f172a;
            margin: 0 0 4px 0;
            font-weight: 700;
        }

        .profil-panel-header p {
            color: #64748b;
            font-size: 13px;
            margin: 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #334155;
            font-size: 13px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background-color: #ffffff;
            color: #0f172a;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .btn-save {
            height: 46px;
            padding: 0 24px;
            font-size: 14px;
            font-weight: 600;
            background-color: #10b981;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: inherit;
        }

        .btn-save:hover {
            background-color: #059669;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(16, 185, 129, 0.25);
        }

        .section-divider {
            border: 0;
            border-top: 1px solid #e2e8f0;
            margin: 32px 0;
        }
    </style>
@endpush

<div style="padding: 32px 40px; box-sizing: border-box; max-width: 100%;">
    <div class="page-heading" style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h2 style="font-size: 24px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">Pengaturan Profil Bendahara</h2>
            <p style="color: #64748b; font-size: 14px; margin: 0;">Pastikan data Anda selalu yang paling mutakhir.</p>
        </div>
    </div>

    @if (session('success'))
        <div style="background-color: #f0fdf4; color: #166534; padding: 16px; border-radius: 8px; border: 1px solid #bbf7d0; margin-bottom: 24px; display: flex; align-items: center; gap: 8px; width: 100%;">
            <ion-icon name="checkmark-circle" style="font-size: 20px; color: #10b981;"></ion-icon>
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div style="background-color: #fef2f2; color: #991b1b; padding: 16px; border-radius: 8px; border: 1px solid #fecaca; margin-bottom: 24px; width: 100%;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-weight: 600;">
                <ion-icon name="warning" style="font-size: 20px; color: #ef4444;"></ion-icon> Terdapat beberapa kesalahan:
            </div>
            <ul style="margin: 0; padding-left: 32px; font-size: 13px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="profil-panel">
        <div class="profil-panel-header">
            <h2>Data Profil & Akun</h2>
            <p>Perbarui informasi identitas dan keamanan akun Anda.</p>
        </div>
        <div style="padding: 32px 24px;">
            <form action="{{ route('bendahara.profil.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" required class="form-input" value="{{ old('name', $user->name) }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Alamat Email</label>
                        <input type="email" name="email" required class="form-input" value="{{ old('email', $user->email) }}">
                    </div>
                </div>

                <hr class="section-divider">
                
                <h3 style="margin: 0 0 4px 0; color: #0f172a; font-size: 16px; font-weight: 700;">Keamanan & Kata Sandi</h3>
                <p style="color: #64748b; font-size: 13px; margin-bottom: 20px;">Kosongkan bidang ini jika Anda tidak ingin mengubah kata sandi Anda saat ini.</p>

                <div class="form-group" style="max-width: 50%;">
                    <label class="form-label">Kata Sandi Saat Ini</label>
                    <div style="position: relative;">
                        <input type="password" name="current_password" class="form-input" autocomplete="current-password" placeholder="Masukkan kata sandi lama..." style="padding-right: 40px;">
                        <button type="button" class="btn-toggle-password" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #64748b; cursor: pointer; display: flex; padding: 4px;" title="Tampilkan kata sandi">
                            <ion-icon name="eye-outline" style="font-size: 18px;"></ion-icon>
                        </button>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 12px;">
                    <div class="form-group">
                        <label class="form-label">Kata Sandi Baru</label>
                        <div style="position: relative;">
                            <input type="password" name="password" class="form-input" autocomplete="new-password" placeholder="Minimal 8 karakter..." style="padding-right: 40px;">
                            <button type="button" class="btn-toggle-password" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #64748b; cursor: pointer; display: flex; padding: 4px;" title="Tampilkan kata sandi">
                                <ion-icon name="eye-outline" style="font-size: 18px;"></ion-icon>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Kata Sandi Baru</label>
                        <div style="position: relative;">
                            <input type="password" name="password_confirmation" class="form-input" autocomplete="new-password" placeholder="Ketik ulang kata sandi baru..." style="padding-right: 40px;">
                            <button type="button" class="btn-toggle-password" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #64748b; cursor: pointer; display: flex; padding: 4px;" title="Tampilkan kata sandi">
                                <ion-icon name="eye-outline" style="font-size: 18px;"></ion-icon>
                            </button>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 32px; display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn-save">
                        <ion-icon name="save-outline" style="font-size: 18px;"></ion-icon> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
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
