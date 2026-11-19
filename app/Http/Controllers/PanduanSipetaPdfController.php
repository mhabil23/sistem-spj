<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PanduanSipetaPdfController extends Controller
{
    /**
     * Menampilkan pratinjau (preview/stream) PDF Dokumen Buku Panduan Penggunaan SIPETA 2026 di browser.
     */
    public function preview(): BinaryFileResponse
    {
        $filePath = public_path('files/Buku-Panduan-SIPETA-2026.pdf');

        abort_if(! file_exists($filePath), 404, 'Berkas Buku Panduan SIPETA tidak ditemukan.');

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Buku-Panduan-SIPETA-2026.pdf"',
        ]);
    }

    /**
     * Mengunduh langsung berkas PDF Dokumen Buku Panduan Penggunaan SIPETA 2026.
     */
    public function download(): BinaryFileResponse
    {
        $filePath = public_path('files/Buku-Panduan-SIPETA-2026.pdf');

        abort_if(! file_exists($filePath), 404, 'Berkas Buku Panduan SIPETA tidak ditemukan.');

        return response()->download($filePath, 'Buku-Panduan-SIPETA-2026.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
