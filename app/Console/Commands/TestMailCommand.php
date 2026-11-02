<?php

namespace App\Console\Commands;

use App\Mail\TestSmtpMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email? : Alamat email tujuan pengujian}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uji coba koneksi SMTP dan pengiriman email sungguhan dari sistem SPJ';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('========================================================');
        $this->info('  UJI COBA INTEGRASI EMAIL (SMTP) SISTEM SPJ BPS        ');
        $this->info('========================================================');

        $mailer = (string) config('mail.default');
        $host = (string) config('mail.mailers.smtp.host');
        $port = (string) config('mail.mailers.smtp.port');
        $username = (string) config('mail.mailers.smtp.username');
        $fromAddress = (string) config('mail.from.address');
        $fromName = (string) config('mail.from.name');

        $this->table(
            ['Parameter', 'Nilai Saat Ini'],
            [
                ['MAIL_MAILER', $mailer],
                ['MAIL_HOST', $host ?: '(kosong)'],
                ['MAIL_PORT', $port ?: '(kosong)'],
                ['MAIL_USERNAME', $username ?: '(kosong)'],
                ['MAIL_FROM_ADDRESS', $fromAddress ?: '(kosong)'],
                ['MAIL_FROM_NAME', $fromName ?: '(kosong)'],
            ]
        );

        $recipient = $this->argument('email');
        if (! $recipient) {
            $adminEmail = User::where('role', 'admin')->value('email');
            $recipient = $adminEmail ?: 'admin@example.com';
            $this->comment("Alamat email tidak ditentukan, menggunakan email admin: {$recipient}");
        }

        $this->info("Sedang mencoba mengirim email uji coba ke: {$recipient}...");

        try {
            Mail::to($recipient)->send(new TestSmtpMail($recipient));

            $this->newLine();
            $this->info("✓ BERHASIL! Email uji coba berhasil dikirim ke: {$recipient}");
            if ($mailer === 'log') {
                $this->warn('Catatan: MAIL_MAILER Anda saat ini bernilai "log", sehingga email dicatat ke file storage/logs/laravel.log.');
                $this->warn('Untuk mengirim email sungguhan ke inbox Gmail/Yahoo, ubah MAIL_MAILER=smtp dan isi MAIL_USERNAME & MAIL_PASSWORD di file .env');
            } else {
                $this->info('Silakan periksa kotak masuk (Inbox) atau folder Spam pada akun email penerima.');
            }

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->newLine();
            $this->error('✗ GAGAL MENGIRIM EMAIL!');
            $this->error('Detail Error: '.$e->getMessage());
            $this->newLine();
            $this->line('<comment>Panduan Troubleshooting:</comment>');
            $this->line('1. Jika menggunakan <info>Gmail</info>:');
            $this->line('   - Pastikan mengaktifkan 2-Step Verification pada Akun Google Anda.');
            $this->line('   - Buat App Password khusus di https://myaccount.google.com/apppasswords');
            $this->line('   - Isi <info>MAIL_USERNAME=emailanda@gmail.com</info> dan <info>MAIL_PASSWORD=16karakterapppassword</info>');
            $this->line('   - Gunakan <info>MAIL_HOST=smtp.gmail.com</info> dan <info>MAIL_PORT=587</info>');
            $this->line('2. Jika menggunakan <info>Brevo / Mailtrap / SendGrid</info>:');
            $this->line('   - Masukkan SMTP host, port 587, username, dan API key password yang disediakan.');

            return self::FAILURE;
        }
    }
}
