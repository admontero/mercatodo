<?php

namespace Tests\Unit\Http\Controllers\Admin;

use App\Models\User;
use Database\Seeders\RoleSeeder;
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
        $this->seed(RoleSeeder::class);

        $admin = User::factory()->admin()->create();

        Passport::actingAs($admin);

        $response = $this->get('/admin/customers');

        $response->assertSuccessful();

        $response->assertViewIs('backoffice.customers.index');
    }

    /**
     * @test
     */
    public function edit_method_must_returns_the_customer_edit_view(): void
    {
        $this->withoutVite();
        $this->seed(RoleSeeder::class);

        $admin = User::factory()->admin()->create();
        $customer = User::factory()->create();

        Passport::actingAs($admin);

        $response = $this->get("/admin/customers/{$customer->id}/edit");

        $response->assertSuccessful();

        $response->assertViewIs('backoffice.customers.edit');

        $response->assertViewHas('customer');
    }
}
