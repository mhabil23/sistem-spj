<?php

namespace Tests\Feature;

use Tests\TestCase;

class PanduanRolePdfTest extends TestCase
{
    /**
     * Test preview panduan role PDF.
     */
    public function test_can_preview_panduan_role_pdf(): void
    {
        $response = $this->get(route('panduan-role.preview'));

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
    }

    /**
     * Test download panduan role PDF.
     */
    public function test_can_download_panduan_role_pdf(): void
    {
        $response = $this->get(route('panduan-role.download'));

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
        $this->assertStringContainsString('attachment', (string) $response->headers->get('content-disposition'));
        $this->assertStringContainsString('Panduan-Hak-dan-Tugas-Role-SPJ.pdf', (string) $response->headers->get('content-disposition'));

        if (! is_dir(public_path('files'))) {
            mkdir(public_path('files'), 0777, true);
        }
        file_put_contents(public_path('files/Panduan-Hak-dan-Tugas-Role-SPJ.pdf'), $response->getContent());
    }
}
