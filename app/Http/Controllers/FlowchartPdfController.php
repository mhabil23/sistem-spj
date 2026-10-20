<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class FlowchartPdfController extends Controller
{
    /**
     * Menampilkan pratinjau (preview/stream) PDF Flowchart & SOP di browser.
     */
    public function preview(): Response
    {
        $pdf = Pdf::loadView('pdf.flowchart-sop')
            ->setPaper('a4', 'portrait')
            ->setOption([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);

        return $pdf->stream('SOP-Flowchart-SPJ-BPS-Kolaka-Utara.pdf');
    }

    /**
     * Mengunduh langsung berkas PDF Flowchart & SOP.
     */
    public function download(): Response
    {
        $pdf = Pdf::loadView('pdf.flowchart-sop')
            ->setPaper('a4', 'portrait')
            ->setOption([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);

        return $pdf->download('SOP-Flowchart-SPJ-BPS-Kolaka-Utara.pdf');
    }
}
