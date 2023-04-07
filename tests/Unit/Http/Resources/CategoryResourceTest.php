<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_category_resource_must_have_the_required_fields(): void
    {
        $this->seed(RoleSeeder::class);

        $category = Category::factory()->create();

        $categoryResource = CategoryResource::make($category)->resolve();

        $this->assertEquals($category->id, $categoryResource['id']);
        $this->assertEquals($category->name, $categoryResource['name']);
        $this->assertEquals($category->slug, $categoryResource['slug']);
        $this->assertEquals($category->created_at->diffForHumans(), $categoryResource['ago']);
    }
}
