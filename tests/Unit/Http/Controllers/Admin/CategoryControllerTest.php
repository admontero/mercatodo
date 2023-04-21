<?php

namespace Tests\Unit\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\User;
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

        $admin = User::factory()->admin()->create();

        Passport::actingAs($admin);

        $response = $this->get(route('admin.categories.index'));

        $response
            ->assertSuccessful()
            ->assertViewIs('backoffice.categories.index');
    }

    /**
     * @test
     */
    public function create_method_must_returns_the_category_create_view(): void
    {
        $this->withoutVite();

        $admin = User::factory()->admin()->create();

        Passport::actingAs($admin);

        $response = $this->get(route('admin.categories.create'));

        $response
            ->assertSuccessful()
            ->assertViewIs('backoffice.categories.create');
    }

    /**
     * @test
     */
    public function edit_method_must_returns_the_category_edit_view(): void
    {
        $this->withoutVite();

        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();

        Passport::actingAs($admin);

        $response = $this->get(route('admin.categories.edit', $category));

        $response
            ->assertSuccessful()
            ->assertViewIs('backoffice.categories.edit')
            ->assertViewHas('category');
    }
}
