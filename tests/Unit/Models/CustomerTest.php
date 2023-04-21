<?php

namespace Tests\Unit\Models;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_customer_belongs_to_user(): void
    {
        $customer = Customer::factory()->create();

        $this->assertInstanceOf(User::class, $customer->user);
    }
}
