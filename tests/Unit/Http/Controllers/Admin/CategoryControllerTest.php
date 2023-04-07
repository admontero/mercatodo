<?php

namespace Tests\Unit\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function index_method_must_returns_the_category_index_view(): void
    {
        $this->withoutVite();
        $this->seed(RoleSeeder::class);

        $admin = User::factory()->admin()->create();

        Passport::actingAs($admin);

        $response = $this->get('/admin/categories');

        $response->assertSuccessful();

        $response->assertViewIs('backoffice.categories.index');
    }

    /**
     * @test
     */
    public function create_method_must_returns_the_category_create_view(): void
    {
        $this->withoutVite();
        $this->seed(RoleSeeder::class);

        $admin = User::factory()->admin()->create();

        Passport::actingAs($admin);

        $response = $this->get("/admin/categories/create");

        $response->assertSuccessful();

        $response->assertViewIs('backoffice.categories.create');
    }

    /**
     * @test
     */
    public function edit_method_must_returns_the_category_edit_view(): void
    {
        $this->withoutVite();
        $this->seed(RoleSeeder::class);

        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();

        Passport::actingAs($admin);

        $response = $this->get("/admin/categories/{$category->slug}/edit");

        $response->assertSuccessful();

        $response->assertViewIs('backoffice.categories.edit');

        $response->assertViewHas('category');
    }
}
