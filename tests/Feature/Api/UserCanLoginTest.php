<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserCanLoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function user_can_login_from_api_route(): void
    {
        $admin = User::factory()->admin()->create([
            'password' => bcrypt('12345678'),
        ]);

        $response = $this->postJson(route('api.login'), [
            'email' => $admin->email,
            'password' => '12345678',
        ]);

        $response->assertSuccessful()
            ->assertStatus(200)
            ->assertJsonStructure(['token']);

        $this->assertNotNull(auth()->user());
        $this->assertInstanceOf(User::class, auth()->user());
    }
}
