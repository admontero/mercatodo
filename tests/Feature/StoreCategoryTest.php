<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

        $this->seed(RoleSeeder::class);

        $admin = User::factory()->admin()->create();

        $data = [
            'name' => 'Tecnología',
        ];

        Passport::actingAs($admin);

        $response = $this->postJson("/api/categories", $data);

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
        $this->seed(RoleSeeder::class);

        $admin = User::factory()->admin()->create();

        $data = [
            'name' => '',
        ];

        Passport::actingAs($admin);

        $response = $this->postJson("/api/categories", $data);

        $response->assertStatus(422);

        $response->assertJsonValidationErrorFor('name');
    }

    /**
     * @test
     */
    public function category_name_must_be_a_string(): void
    {
        $this->seed(RoleSeeder::class);

        $admin = User::factory()->admin()->create();

        $data = [
            'name' => 123,
        ];

        Passport::actingAs($admin);

        $response = $this->postJson("/api/categories", $data);

        $response->assertStatus(422);

        $response->assertJsonValidationErrorFor('name');
    }

    /**
     * @test
     */
    public function category_name_length_must_be_less_than_101_characters(): void
    {
        $this->seed(RoleSeeder::class);

        $admin = User::factory()->admin()->create();

        $data = [
            'name' => $this->getRandomStringOnlyLetters(101),
        ];

        Passport::actingAs($admin);

        $response = $this->postJson("/api/categories", $data);

        $response->assertStatus(422);

        $response->assertJsonValidationErrorFor('name');
    }

    /**
     * @test
     */
    public function category_name_must_be_unique(): void
    {
        $this->seed(RoleSeeder::class);

        $admin = User::factory()->admin()->create();

        Category::factory()->create([
            'name' => 'Deportes',
        ]);

        $data = [
            'name' => 'Deportes',
        ];

        Passport::actingAs($admin);

        $response = $this->postJson("/api/categories", $data);

        $response->assertStatus(422);

        $response->assertJsonValidationErrorFor('name');
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
