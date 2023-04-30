<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ListProductsTest extends TestCase
{
    use RefreshDatabase;

    protected $product6;

    public function setUp(): void
    {
        parent::setUp();

        Product::factory()->create(['created_at' => now()->subDays(5)]);
        Product::factory()->create(['created_at' => now()->subDays(4)]);
        Product::factory()->create(['created_at' => now()->subDays(3)]);
        Product::factory()->create(['created_at' => now()->subDays(2)]);
        Product::factory()->create(['created_at' => now()->subDays(1)]);
        Product::factory(2)->inactivated()->create();
        $this->product6 = Product::factory()->create();
    }

    /**
     * @test
     */
    public function admin_can_get_all_products(): void
    {
        $admin = User::factory()->admin()->create();

        Passport::actingAs($admin);

        $response = $this->getJson(route('api.products.index'));

        $response
            ->assertSuccessful()
            ->assertJson([
                'meta' => ['total' => 6],
            ])
            ->assertJsonStructure([
                'data', 'links',
            ]);

        $this->assertEquals($response['data'][0]['code'], $this->product6->code);
    }

    /**
     * @test
     */
    public function customer_can_get_all_products(): void
    {
        $customer = User::factory()
            ->customer()
            ->withCustomerProfile()
            ->create();

        Passport::actingAs($customer);

        $response = $this->getJson(route('api.products.index'));

        $response
            ->assertSuccessful()
            ->assertJson([
                'meta' => ['total' => 6],
            ])
            ->assertJsonStructure([
                'data', 'links',
            ]);

        $this->assertEquals($response['data'][0]['code'], $this->product6->code);
    }

    /**
     * @test
     */
    public function guest_user_can_get_all_products(): void
    {
        $response = $this->getJson(route('api.products.index'));

        $response
            ->assertSuccessful()
            ->assertJson([
                'meta' => ['total' => 6],
            ])
            ->assertJsonStructure([
                'data', 'links',
            ]);

        $this->assertEquals($response['data'][0]['code'], $this->product6->code);
    }
}
