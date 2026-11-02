<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestSmtpMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public string $recipientEmail) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Uji Coba SMTP] Sistem SPJ BPS Kolaka Utara',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: '
                <div style="font-family: Arial, sans-serif; padding: 24px; background: #f8fafc; color: #1e293b; border-radius: 8px;">
                    <h2 style="color: #0284c7; margin-top: 0;">✓ Uji Coba Integrasi Email Berhasil!</h2>
                    <p>Halo,</p>
                    <p>Ini adalah email pengujian resmi dari <strong>Sistem Informasi Penyelesaian Tagihan (SPJ) BPS Kabupaten Kolaka Utara</strong>.</p>
                    <p>Jika Anda menerima email ini di kotak masuk (inbox), maka integrasi server SMTP email sungguhan telah <strong>berhasil dan siap digunakan</strong> untuk fitur Reset Kata Sandi Admin.</p>
                    <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 20px 0;">
                    <p style="font-size: 12px; color: #64748b; margin-bottom: 0;">Email dikirim ke: <strong>'.htmlspecialchars($this->recipientEmail, ENT_QUOTES, 'UTF-8').'</strong> &bull; Waktu: '.now()->translatedFormat('d F Y, H:i:s').' WITA</p>
                </div>
            ',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
