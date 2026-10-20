<?php

namespace Tests\Feature;

use Tests\TestCase;

class FlowchartPdfTest extends TestCase
{
    /**
     * Test preview flowchart PDF.
     */
    public function test_can_preview_flowchart_pdf(): void
    {
        $response = $this->get(route('flowchart.preview'));

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
    }

    /**
     * Test download flowchart PDF.
     */
    public function test_can_download_flowchart_pdf(): void
    {
        $response = $this->get(route('flowchart.download'));

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
        $this->assertStringContainsString('attachment', $response->headers->get('content-disposition'));
        $this->assertStringContainsString('SOP-Flowchart-SPJ-BPS-Kolaka-Utara.pdf', $response->headers->get('content-disposition'));

        if (! is_dir(public_path('files'))) {
            mkdir(public_path('files'), 0777, true);
        }
        file_put_contents(public_path('files/SOP-Flowchart-SPJ-BPS-Kolaka-Utara.pdf'), $response->getContent());
    }
}
