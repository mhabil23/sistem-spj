<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutoLogoutSessionTimeoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_session_configuration_lifetime_is_sixty_minutes(): void
    {
        $this->assertSame(60, config('session.lifetime'));
    }

    public function test_active_user_session_is_maintained_when_idle_time_is_under_one_hour(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);

        $response = $this->actingAs($admin)
            ->withSession(['last_active_at' => time() - 1800]) // 30 menit yang lalu
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $this->assertAuthenticatedAs($admin);
        $this->assertTrue(session('last_active_at') >= time() - 5);
    }

    public function test_admin_is_automatically_logged_out_when_idle_for_more_than_one_hour(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);

        $response = $this->actingAs($admin)
            ->withSession(['last_active_at' => time() - 3605]) // 1 jam 5 detik yang lalu
            ->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('warning', 'Sesi Anda telah berakhir');
        $this->assertGuest();
    }

    public function test_teknis_user_is_automatically_logged_out_when_idle_for_more_than_one_hour(): void
    {
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $response = $this->actingAs($teknis)
            ->withSession(['last_active_at' => time() - 3700])
            ->get(route('teknis.dashboard'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('warning');
        $this->assertGuest();
    }

    public function test_bendahara_user_is_automatically_logged_out_when_idle_for_more_than_one_hour(): void
    {
        $bendahara = User::factory()->create(['role' => 'bendahara', 'status' => 'aktif']);

        $response = $this->actingAs($bendahara)
            ->withSession(['last_active_at' => time() - 4000])
            ->get(route('bendahara.dashboard'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('warning');
        $this->assertGuest();
    }

    public function test_umum_user_is_automatically_logged_out_when_idle_for_more_than_one_hour(): void
    {
        $umum = User::factory()->create(['role' => 'umum', 'status' => 'aktif']);

        $response = $this->actingAs($umum)
            ->withSession(['last_active_at' => time() - 3601])
            ->get(route('umum.dashboard'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('warning');
        $this->assertGuest();
    }

    public function test_logout_idle_route_logs_out_user_and_redirects_to_login_with_warning(): void
    {
        $user = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);

        $response = $this->actingAs($user)->get(route('logout.idle'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('warning', 'Sesi Anda telah berakhir');
        $this->assertGuest();
    }

    public function test_ping_session_route_updates_last_active_at_for_authenticated_user(): void
    {
        $user = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);

        $response = $this->actingAs($user)->getJson(route('session.ping'));

        $response->assertStatus(200);
        $response->assertJson(['status' => 'active']);
        $this->assertNotNull(session('last_active_at'));
    }

    public function test_ping_session_returns_401_for_guest(): void
    {
        $response = $this->getJson(route('session.ping'));

        $response->assertStatus(401);
        $response->assertJson(['status' => 'unauthenticated']);
    }

    public function test_all_authenticated_layouts_render_session_timeout_monitor_component(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);
        $bendahara = User::factory()->create(['role' => 'bendahara', 'status' => 'aktif']);
        $teknis = User::factory()->create(['role' => 'teknis', 'status' => 'aktif']);
        $umum = User::factory()->create(['role' => 'umum', 'status' => 'aktif']);

        // Admin Dashboard
        $resAdmin = $this->actingAs($admin)->get(route('admin.dashboard'));
        $resAdmin->assertSee('sessionTimeoutModal');
        $resAdmin->assertSee('sessionCountdownTimer');
        $resAdmin->assertSee('MAX_IDLE_SECONDS = 3600', false);

        // Bendahara Dashboard
        $resBendahara = $this->actingAs($bendahara)->get(route('bendahara.dashboard'));
        $resBendahara->assertSee('sessionTimeoutModal');
        $resBendahara->assertSee('MAX_IDLE_SECONDS = 3600', false);

        // Teknis Dashboard
        $resTeknis = $this->actingAs($teknis)->get(route('teknis.dashboard'));
        $resTeknis->assertSee('sessionTimeoutModal');
        $resTeknis->assertSee('MAX_IDLE_SECONDS = 3600', false);

        // Umum Dashboard
        $resUmum = $this->actingAs($umum)->get(route('umum.dashboard'));
        $resUmum->assertSee('sessionTimeoutModal');
        $resUmum->assertSee('MAX_IDLE_SECONDS = 3600', false);
    }
}
