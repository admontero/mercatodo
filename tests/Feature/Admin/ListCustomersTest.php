<?php

namespace Tests\Feature\Admin;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ListCustomersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function admin_can_get_all_customers(): void
    {
        $admin = User::factory()->admin()->create();

        User::factory()->customer()->withCustomerProfile()->create(['created_at' => now()->subDays(4)]);
        User::factory()->customer()->withCustomerProfile()->create(['created_at' => now()->subDays(3)]);
        User::factory()->customer()->withCustomerProfile()->create(['created_at' => now()->subDays(2)]);
        User::factory()->customer()->withCustomerProfile()->create(['created_at' => now()->subDays(1)]);
        $customer5 = User::factory()->customer()->withCustomerProfile()->create();

        Passport::actingAs($admin);

        $response = $this->getJson(route('api.admin.customers.index'));

        $response
            ->assertSuccessful()
            ->assertJson([
                'meta' => ['total' => 5],
            ])
            ->assertJsonStructure([
                'data', 'links',
            ]);

        $this->assertEquals($response['data'][0]['email'], $customer5->email);
    }

    /**
     * @test
     */
    public function guest_user_can_not_get_all_customers(): void
    {
        User::factory(10)->customer()->withCustomerProfile()->create();

        $response = $this->getJson(route('api.admin.customers.index'));

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function customer_can_not_get_all_customers(): void
    {
        $customer = User::factory()->customer()->withCustomerProfile()->create();

        User::factory(10)->customer()->withCustomerProfile()->create();

        Passport::actingAs($customer);

        $response = $this->getJson(route('api.admin.customers.index'));

        $response->assertStatus(403);
    }
}
