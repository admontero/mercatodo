<?php

namespace Tests\Feature;

use Domain\User\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function admin_can_login(): void
    {
        User::factory()->admin()->create([
            'email' => 'admin@test.com',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
    }

    /**
     * @test
     */
    public function customer_can_login(): void
    {
        User::factory()->create([
            'email' => 'customer@test.com',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'customer@test.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('products.index'));
    }

    /**
     * @test
     */
    public function user_can_confirm_the_password(): void
    {
        $customer = User::factory()->create([
            'email' => 'customer@test.com',
        ]);

        Passport::actingAs($customer);

        $response = $this->post(route('password.confirm'), [
            'password' => 'password',
        ]);

        $response->assertRedirect(route('products.index'));
    }

    /**
     * @test
     */
    public function user_can_verify_the_email(): void
    {
        $notification = new VerifyEmail();

        $customer = User::factory()->unverified()->create([
            'email' => 'customer@test.com',
        ]);

        $mail = $notification->toMail($customer);
        $uri = $mail->actionUrl;

        Passport::actingAs($customer);

        $response = $this->get($uri);

        $response->assertRedirect(route('products.index'));
    }
}
