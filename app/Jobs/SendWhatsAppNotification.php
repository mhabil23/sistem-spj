<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWhatsAppNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Jumlah percobaan maksimal jika terjadi kegagalan jaringan.
     */
    public int $tries = 3;

    /**
     * Batas waktu eksekusi job (detik).
     */
    public int $timeout = 20;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $target,
        public string $message
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $token = config('services.whatsapp.token');

        if (empty($token)) {
            Log::info('[WhatsAppService Job] Pesan tidak terkirim karena FONNTE_TOKEN belum diset di .env.', [
                'target' => $this->target,
            ]);

            return;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->timeout(10)->post('https://api.fonnte.com/send', [
                'target' => $this->target,
                'message' => $this->message,
                'countryCode' => '62',
            ]);

            if ($response->successful()) {
                Log::info('[WhatsAppService Job] Sukses mengirim pesan WA via Antrean', [
                    'target' => $this->target,
                ]);
            } else {
                Log::warning('[WhatsAppService Job] Gagal mengirim pesan WA via Antrean', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('[WhatsAppService Job] Exception saat kirim WA via Antrean: '.$e->getMessage());
            throw $e;
        }
    }
}

