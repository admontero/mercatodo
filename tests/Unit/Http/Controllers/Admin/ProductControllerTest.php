<?php

namespace Tests\Unit\Http\Controllers\Admin;

use App\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    /**
     * @test
     */
    public function index_method_must_returns_the_post_index_view(): void
    {
        $this->withoutVite();

        $admin = User::factory()->admin()->create();

        Passport::actingAs($admin);

        $response = $this->get(route('admin.products.index'));

        $response
            ->assertSuccessful()
            ->assertViewIs('backoffice.categories.index');
    }
}
