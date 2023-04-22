<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class StoreCategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function admin_can_create_a_category(): void
    {
        $this->withoutExceptionHandling();

        $admin = User::factory()->admin()->create();

        $data = [
            'name' => 'Tecnología',
        ];

        Passport::actingAs($admin);

        $response = $this->postJson(route('api.categories.store'), $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('categories', [
            'name' => 'Tecnología',
            'slug' => 'tecnologia',
        ]);
    }

    /**
     * @test
     */
    public function category_name_must_be_required(): void
    {
        $admin = User::factory()->admin()->create();

        $data = [
            'name' => '',
        ];

        Passport::actingAs($admin);

        $response = $this->postJson(route('api.categories.store'), $data);

        $response->assertStatus(422);

        $response->assertJsonValidationErrorFor('name');
    }

    /**
     * @test
     */
    public function category_name_must_be_a_string(): void
    {
        $admin = User::factory()->admin()->create();

        $data = [
            'name' => 123,
        ];

        Passport::actingAs($admin);

        $response = $this->postJson(route('api.categories.store'), $data);

        $response->assertStatus(422);

        $response->assertJsonValidationErrorFor('name');
    }

    /**
     * @test
     */
    public function category_name_length_must_contains_at_least_3_characters(): void
    {
        $admin = User::factory()->admin()->create();

        $data = [
            'name' => 'ao',
        ];

        Passport::actingAs($admin);

        $response = $this->postJson(route('api.categories.store'), $data);

        $response->assertStatus(422);

        $response->assertJsonValidationErrorFor('name');
    }

    /**
     * @test
     */
    public function category_name_length_must_be_less_than_101_characters(): void
    {
        $admin = User::factory()->admin()->create();

        $data = [
            'name' => $this->getRandomStringOnlyLetters(101),
        ];

        Passport::actingAs($admin);

        $response = $this->postJson(route('api.categories.store'), $data);

        $response->assertStatus(422);

        $response->assertJsonValidationErrorFor('name');
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

        $data = [
            'name' => 'Deportes',
        ];

        Passport::actingAs($admin);

        $response = $this->postJson(route('api.categories.store'), $data);

        $response->assertStatus(422);

        $response->assertJsonValidationErrorFor('name');
    }

    public function getRandomStringOnlyLetters(int $n = 1): string
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
