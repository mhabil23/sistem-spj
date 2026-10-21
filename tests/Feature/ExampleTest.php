<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('SPJ/2026/04/012');
        $response->assertDontSee('100% Tervalidasi');
        $response->assertDontSee('Simulasi Otomatis Aktif');
        $response->assertDontSee('btnToggleSim');
        $response->assertDontSee('simPlayPauseIcon');
    }
}
