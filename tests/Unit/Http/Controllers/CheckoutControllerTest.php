<?php

namespace Tests\Unit\Http\Controllers;

use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CheckoutControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_method_returns_the_checkout_cart_view(): void
    {
        $this->withoutVite();

        $customer = User::factory()->customer()->withCustomerProfile()->create();

        Passport::actingAs($customer);

        $response = $this->get(route('checkout.index'));

        $response
            ->assertSuccessful()
            ->assertViewIs('checkout.index');
    }
}
