<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UpdateCategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function admin_can_update_a_category_name(): void
    {
        $this->withoutExceptionHandling();

        $admin = User::factory()->admin()->create();

        $category = Category::factory()->create([
            'name' => 'Tecnología',
        ]);

        $data = [
            'name' => 'Hogar y decoración',
        ];

        Passport::actingAs($admin);

        $response = $this->putJson(route('api.categories.update', $category), $data);

        $response->assertStatus(201);

        $category->refresh();

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => $data['name'],
            'slug' => $category->slug,
        ]);
    }

    /**
     * @test
     */
    public function category_name_must_be_required(): void
    {
        $admin = User::factory()->admin()->create();

        $category = Category::factory()->create([
            'name' => 'Hogar y decoración',
        ]);

        $data = [
            'name' => '',
        ];

        Passport::actingAs($admin);

        $response = $this->putJson(route('api.categories.update', $category), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('name');
    }

    /**
     * @test
     */
    public function category_name_must_be_a_string(): void
    {
        $admin = User::factory()->admin()->create();

        $category = Category::factory()->create([
            'name' => 'Deporte',
        ]);

        $data = [
            'name' => 45,
        ];

        Passport::actingAs($admin);

        $response = $this->putJson(route('api.categories.update', $category), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('name');
    }

    /**
     * @test
     */
    public function category_name_length_must_contains_at_least_3_characters(): void
    {
        $admin = User::factory()->admin()->create();

        $category = Category::factory()->create([
            'name' => 'Deporte',
        ]);

        $data = [
            'name' => 'ao',
        ];

        Passport::actingAs($admin);

        $response = $this->putJson(route('api.categories.update', $category), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('name');
    }

    /**
     * @test
     */
    public function category_name_length_must_be_less_than_101_characters(): void
    {
        $admin = User::factory()->admin()->create();

        $category = Category::factory()->create([
            'name' => 'Juguetería',
        ]);

        $data = [
            'name' => $this->getRandomStringOnlyLetters(101),
        ];

        Passport::actingAs($admin);

        $response = $this->putJson(route('api.categories.update', $category), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('name');
    }

    /**
     * @test
     */
    public function category_name_must_be_unique(): void
    {
        $admin = User::factory()->admin()->create();

        Category::factory()->create([
            'name' => 'Deportes',
        ]);

        $category = Category::factory()->create([
            'name' => 'Tecnología',
        ]);

        $data = [
            'name' => 'Deportes',
        ];

        Passport::actingAs($admin);

        $response = $this->putJson(route('api.categories.update', $category), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('name');
    }

    public function getRandomStringOnlyLetters($n = 1)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }
}
