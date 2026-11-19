<?php

namespace Tests\Feature;

use Tests\TestCase;

class PanduanSipetaPdfTest extends TestCase
{
    /**
     * Test preview buku panduan SIPETA PDF.
     */
    public function test_can_preview_panduan_sipeta_pdf(): void
    {
        $response = $this->get(route('panduan-sipeta.preview'));

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
        $this->assertStringContainsString('inline', (string) $response->headers->get('content-disposition'));
        $this->assertStringContainsString('Buku-Panduan-SIPETA-2026.pdf', (string) $response->headers->get('content-disposition'));
    }

    /**
     * Test download buku panduan SIPETA PDF.
     */
    public function test_can_download_panduan_sipeta_pdf(): void
    {
        $response = $this->get(route('panduan-sipeta.download'));

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
        $this->assertStringContainsString('attachment', (string) $response->headers->get('content-disposition'));
        $this->assertStringContainsString('Buku-Panduan-SIPETA-2026.pdf', (string) $response->headers->get('content-disposition'));
    }
}
