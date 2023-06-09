<?php

namespace Tests\Unit\Http\Controllers\Admin;

use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_returns_the_admin_dashboard_view(): void
    {
        $this->withoutVite();

        $admin = User::factory()->admin()->create();

        Passport::actingAs($admin);

        $response = $this->get(route('admin.dashboard'));

        $response
            ->assertSuccessful()
            ->assertViewIs('backoffice.dashboard');
    }
}
