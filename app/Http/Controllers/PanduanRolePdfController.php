<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class PanduanRolePdfController extends Controller
{
    /**
     * Menampilkan pratinjau (preview/stream) PDF Dokumen Panduan Hak & Tugas Role di browser.
     */
    public function preview(): Response
    {
        $pdf = Pdf::loadView('pdf.panduan-role')
            ->setPaper('a4', 'portrait')
            ->setOption([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);

        return $pdf->stream('Panduan-Hak-dan-Tugas-Role-SPJ.pdf');
    }

    /**
     * Mengunduh langsung berkas PDF Dokumen Panduan Hak & Tugas Role.
     */
    public function download(): Response
    {
        $pdf = Pdf::loadView('pdf.panduan-role')
            ->setPaper('a4', 'portrait')
            ->setOption([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);

        return $pdf->download('Panduan-Hak-dan-Tugas-Role-SPJ.pdf');
    }
}
