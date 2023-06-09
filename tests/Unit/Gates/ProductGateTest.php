<?php

namespace Tests\Unit\Gates;

use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ProductGateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function manage_product_gate(): void
    {
        $this->withoutVite();

        $customer = User::factory()->customer()->create();
        $admin = User::factory()->admin()->create();

        Passport::actingAs($admin);

        $this->assertTrue(Gate::check('access-product-views'));

        Passport::actingAs($customer);

        $this->assertFalse(Gate::check('access-product-views'));
    }
}
