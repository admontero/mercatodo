<?php

namespace Tests\Unit\Http\Controllers\Admin;

use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function index_method_must_returns_the_customer_index_view(): void
    {
        $this->withoutVite();

        $admin = User::factory()->admin()->create();

        Passport::actingAs($admin);

        $response = $this->get(route('admin.customers.index'));

        $response
            ->assertSuccessful()
            ->assertViewIs('backoffice.customers.index');
    }

    /**
     * @test
     */
    public function edit_method_must_returns_the_customer_edit_view(): void
    {
        $this->withoutVite();

        $admin = User::factory()->admin()->create();
        $customer = User::factory()
            ->customer()
            ->withCustomerProfile()
            ->create();

        Passport::actingAs($admin);

        $response = $this->get(route('admin.customers.edit', $customer));

        $response
            ->assertSuccessful()
            ->assertViewIs('backoffice.customers.edit')
            ->assertViewHas('customer');
    }
}
