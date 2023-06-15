<?php

namespace Tests\Unit\Http\Middlewares;

use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class RedirectIfCustomerIsDisabledTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function redirect_customer_to_login_if_customer_is_disabled(): void
    {
        $customer = User::factory()->customer()->inactivated()->create([
            'email' => 'customer@test.com',
            'password' => bcrypt('12345678'),
        ]);

        Passport::actingAs($customer, [], 'web');

        $response = $this->get(route('profile.edit'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', __('The user has been disabled, try again later.'));
    }
}
