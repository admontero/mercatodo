<?php

namespace Tests\Feature\Api;

use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCanLogoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function user_can_logout_when_it_is_authenticated(): void
    {
        $admin = User::factory()->admin()->create([
            'password' => bcrypt('12345678'),
        ]);

        $responseLogin = $this->postJson(route('api.login'), [
            'email' => $admin->email,
            'password' => '12345678',
        ]);

        $token = $responseLogin->getData()->token;

        $responseCustomers = $this->getJson(route('api.admin.customers.index'), [
            'Authorization' => 'Bearer '.$token,
        ]);

        $responseCustomers->assertStatus(200);

        $responseLogout = $this->postJson(route('api.logout'), [], [
            'Authorization' => 'Bearer '.$token,
        ]);

        $responseLogout->assertJsonFragment([
            'success' => 'Logout Succesfully',
        ]);
    }
}
