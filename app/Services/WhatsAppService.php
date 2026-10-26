<?php

namespace App\Services;

use App\Models\Spj;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Mengirim pesan WhatsApp via Fonnte API
     */
    public static function send(string|array $target, string $message): bool
    {
        $token = config('services.whatsapp.token');

        if (empty($token)) {
            Log::info('[WhatsAppService] Pesan tidak terkirim karena FONNTE_TOKEN belum diset di .env.', [
                'target' => $target,
                'message' => $message,
            ]);

            return false;
        }

        if (is_array($target)) {
            $target = implode(',', array_filter($target));
        }

        if (empty(trim($target))) {
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->timeout(10)->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62',
            ]);

            if ($response->successful()) {
                Log::info('[WhatsAppService] Sukses mengirim pesan WA', [
                    'target' => $target,
                ]);

                return true;
            }

            Log::warning('[WhatsAppService] Gagal mengirim pesan WA', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return false;
        } catch (\Throwable $e) {
            Log::error('[WhatsAppService] Exception saat kirim WA: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Notifikasi ketika SPJ baru diajukan oleh Teknis ke Umum
     */
    public static function notifySubmitted(Spj $spj): void
    {
        $targetUmum = config('services.whatsapp.target_umum');

        if (! empty($targetUmum)) {
            $targets = [$targetUmum];
        } else {
            $targets = User::where('role', 'umum')
                ->whereNotNull('no_wa')
                ->where('no_wa', '!=', '')
                ->pluck('no_wa')
                ->toArray();
        }

        if (empty($targets)) {
            return;
        }

        $namaPengaju = $spj->user->name ?? 'Staf Teknis';
        $nilaiRp = number_format($spj->nilai, 0, ',', '.');
        $waktu = now()->translatedFormat('d M Y, H:i');

        $message = "🔔 *PENGAJUAN SPJ BARU* 🔔\n\n"
            ."Halo Tim Verifikasi Umum BPS,\n\n"
            ."Ada dokumen SPJ baru yang diajukan dan membutuhkan pemeriksaan:\n\n"
            ."• *Pengaju* : {$namaPengaju}\n"
            ."• *Kegiatan*: {$spj->kegiatan}\n"
            ."• *Nilai*   : Rp {$nilaiRp}\n"
            ."• *Waktu*   : {$waktu}\n\n"
            ."Silakan masuk ke sistem untuk memeriksa kelengkapan berkas:\n"
            .url('/umum/spj/'.$spj->id)."\n\n"
            .'_Sistem Informasi Penyelesaian Tagihan (SPJ)_';

        self::send($targets, $message);
    }

    /**
     * Notifikasi ketika SPJ disetujui (Umum / PPK / PPSPM / Bendahara) ke Teknis
     */
    public static function notifyApproved(Spj $spj, string $stage): void
    {
        $noWaTeknis = $spj->no_wa ?: ($spj->user->no_wa ?? null);
        if (empty($noWaTeknis)) {
            return;
        }

        $namaTeknis = $spj->user->name ?? 'Staf Teknis';
        $nilaiRp = number_format($spj->nilai, 0, ',', '.');

        switch ($stage) {
            case 'Umum':
                $judul = '✅ *SPJ DISETUJUI OLEH UMUM*';
                $tahapBerikutnya = 'Harap bawa berkas fisik ke PPK untuk ditandatangani.';
                break;
            case 'PPK':
                $judul = '✅ *SPJ TELAH DITANDATANGANI PPK*';
                $tahapBerikutnya = 'Harap bawa berkas fisik ke PPSPM untuk persetujuan selanjutnya.';
                break;
            case 'PPSPM':
                $judul = '✅ *SPJ TELAH DISETUJUI PPSPM*';
                $tahapBerikutnya = 'Dokumen telah diteruskan ke Bendahara untuk proses pencairan dana.';
                break;
            case 'menunggu_otp_kpa':
            case 'OTP_KPA':
            case 'qlola':
                $judul = '⏳ *MENUNGGU OTP QLOLA KPA*';
                $tahapBerikutnya = 'Transaksi pembayaran telah diinput ke sistem BRI QLOLA oleh Bendahara dan saat ini sedang menunggu otorisasi kode OTP dari KPA.';
                break;
            case 'Selesai':
            case 'selesai':
                $judul = '🎉 *PROSES PEMBAYARAN TELAH SELESAI*';
                $tahapBerikutnya = 'Proses pembayaran telah selesai, silahkan cek rekening masing-masing.';
                break;
            default:
                $judul = '✅ *STATUS SPJ DIPERBARUI*';
                $tahapBerikutnya = 'SPJ Anda sedang diproses ke tahap selanjutnya.';
                break;
        }

        $message = "{$judul}\n\n"
            ."Halo *{$namaTeknis}*,\n\n"
            ."SPJ Anda telah disetujui pada tahap *{$stage}*:\n\n"
            ."• *Kegiatan*: {$spj->kegiatan}\n"
            ."• *Nilai*   : Rp {$nilaiRp}\n\n"
            ."📌 *Langkah Selanjutnya*:\n{$tahapBerikutnya}\n\n"
            ."Pantau detailnya di:\n".url('/teknis/spj/'.$spj->id)."\n\n"
            .'_Sistem Informasi Penyelesaian Tagihan (SPJ)_';

        self::send($noWaTeknis, $message);
    }

    /**
     * Notifikasi ketika SPJ dikembalikan / perlu revisi ke Teknis
     */
    public static function notifyRevised(Spj $spj, string $stage, ?string $catatan): void
    {
        $noWaTeknis = $spj->no_wa ?: ($spj->user->no_wa ?? null);
        if (empty($noWaTeknis)) {
            return;
        }

        $namaTeknis = $spj->user->name ?? 'Staf Teknis';
        $catatanText = $catatan ?: 'Tidak ada catatan perbaikan khusus.';

        $message = "⚠️ *SPJ PERLU PERBAIKAN (REVISI)* ⚠️\n\n"
            ."Halo *{$namaTeknis}*,\n\n"
            ."SPJ Anda dikembalikan oleh *{$stage}* untuk diperbaiki:\n\n"
            ."• *Kegiatan* : {$spj->kegiatan}\n"
            ."• *Catatan*  : \"{$catatanText}\"\n\n"
            ."Silakan perbaiki berkas dan ajukan kembali melalui tautan berikut:\n"
            .url('/teknis/spj/'.$spj->id.'/edit')."\n\n"
            .'_Sistem Informasi Penyelesaian Tagihan (SPJ)_';

        self::send($noWaTeknis, $message);
    }
}
