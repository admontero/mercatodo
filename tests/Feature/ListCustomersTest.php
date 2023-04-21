<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

        Customer::factory()->create(['created_at' => now()->subDays(4)]);
        Customer::factory()->create(['created_at' => now()->subDays(3)]);
        Customer::factory()->create(['created_at' => now()->subDays(2)]);
        Customer::factory()->create(['created_at' => now()->subDays(1)]);
        $customer5 = Customer::factory()->create();

        Passport::actingAs($admin);

        $response = $this->getJson(route('api.customers.index'));

        $response
            ->assertSuccessful()
            ->assertJson([
                'meta' => ['total' => 5],
            ])
            ->assertJsonStructure([
                'data', 'links',
            ]);

        $this->assertEquals($response['data'][0]['user']['email'], $customer5->user->email);
    }

    /**
     * @test
     */
    public function guest_user_can_not_get_all_customers(): void
    {
        Customer::factory(10)->create();

        $response = $this->getJson(route('api.customers.index'));

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function customer_can_not_get_all_customers(): void
    {
        $customer = Customer::factory()->create();

        Customer::factory(10)->create();

        Passport::actingAs($customer->user);

        $response = $this->getJson(route('api.customers.index'));

        $response->assertStatus(403);
    }
}
