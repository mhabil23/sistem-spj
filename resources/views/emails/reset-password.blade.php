<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Kata Sandi Akun</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1e293b; -webkit-font-smoothing: antialiased;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f1f5f9; padding: 30px 15px;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 580px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 18px rgba(15, 23, 42, 0.08); border: 1px solid #e2e8f0;">
                    
                    <!-- Header with BPS Brand -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #0f2744 0%, #173b5c 50%, #0284c7 100%); padding: 30px 32px; text-align: center;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center">
                                        <div style="background-color: rgba(255, 255, 255, 0.15); display: inline-block; padding: 8px 18px; border-radius: 8px; margin-bottom: 12px; backdrop-filter: blur(4px);">
                                            <span style="color: #ffffff; font-size: 11px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase;">
                                                PORTAL RESMI SPJ BPS
                                            </span>
                                        </div>
                                        <h1 style="color: #ffffff; font-size: 19px; font-weight: 800; margin: 0 0 4px 0; letter-spacing: 0.3px;">
                                            BADAN PUSAT STATISTIK
                                        </h1>
                                        <p style="color: #e0f2fe; font-size: 12.5px; margin: 0; font-weight: 500;">
                                            Kabupaten Kolaka Utara &bull; Sistem Penyelesaian Tagihan
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 36px 34px 28px 34px;">
                            <h2 style="color: #0f172a; font-size: 18px; font-weight: 700; margin: 0 0 16px 0;">
                                Permintaan Atur Ulang Kata Sandi
                            </h2>

                            <p style="font-size: 14.5px; line-height: 1.6; color: #334155; margin: 0 0 16px 0;">
                                Halo <strong>{{ $user->name }}</strong>,
                            </p>

                            <p style="font-size: 14px; line-height: 1.65; color: #475569; margin: 0 0 24px 0;">
                                Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda pada <strong>Sistem Informasi Penyelesaian Tagihan (SPJ)</strong>. Jika Anda yang mengajukan permintaan ini, silakan klik tombol di bawah untuk membuat kata sandi baru Anda:
                            </p>

                            <!-- CTA Button -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 28px 0 28px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $resetUrl }}" target="_blank" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: #ffffff; text-decoration: none; font-size: 14.5px; font-weight: 700; padding: 13px 32px; border-radius: 8px; display: inline-block; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.35); letter-spacing: 0.3px;">
                                            Reset Kata Sandi Akun &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Security Expiration Notice -->
                            <div style="background-color: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 14px 18px; margin: 24px 0 20px 0;">
                                <p style="font-size: 12.5px; line-height: 1.55; color: #92400e; margin: 0;">
                                    <strong>Perhatian Keamanan:</strong> Tautan pengaturan ulang kata sandi ini akan kedaluwarsa secara otomatis dalam <strong>{{ $expireMinutes }} menit</strong>.
                                </p>
                            </div>

                            <p style="font-size: 13px; line-height: 1.6; color: #64748b; margin: 0 0 20px 0;">
                                Jika Anda tidak pernah meminta perubahan kata sandi ini, silakan abaikan email ini. Akun Anda tetap aman dan kata sandi saat ini tidak akan mengalami perubahan.
                            </p>

                            <!-- Fallback Link -->
                            <div style="border-top: 1px solid #e2e8f0; padding-top: 20px; margin-top: 26px;">
                                <p style="font-size: 11.5px; line-height: 1.5; color: #94a3b8; margin: 0 0 6px 0;">
                                    Jika tombol di atas tidak dapat diklik, salin dan tempel alamat tautan berikut pada peramban (browser) Anda:
                                </p>
                                <p style="font-size: 11px; line-height: 1.4; color: #0284c7; word-break: break-all; margin: 0;">
                                    <a href="{{ $resetUrl }}" style="color: #0284c7; text-decoration: underline;">{{ $resetUrl }}</a>
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 22px 34px; border-top: 1px solid #e2e8f0; text-align: center;">
                            <p style="font-size: 11.5px; line-height: 1.5; color: #64748b; margin: 0 0 4px 0;">
                                &copy; {{ date('Y') }} Badan Pusat Statistik Kabupaten Kolaka Utara
                            </p>
                            <p style="font-size: 10.5px; color: #94a3b8; margin: 0;">
                                Email otomatis dikirim oleh Sistem Informasi SPJ Terintegrasi. Mohon tidak membalas email ini.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>

