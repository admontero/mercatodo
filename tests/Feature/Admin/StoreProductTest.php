<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Tests\TestCase;

class StoreProductTest extends TestCase
{
    use RefreshDatabase;

    protected $category;

    public function setUp(): void
    {
        parent::setUp();

        $this->category = Category::factory()->create();
    }

    /**
     * @test
     */
    public function admin_can_create_a_product(): void
    {
        $this->withoutExceptionHandling();

        $admin = User::factory()->admin()->create();

        $data = $this->getProductValidData();

        Passport::actingAs($admin);

        $response = $this->postJson(route('api.admin.products.store'), $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('products', [
            'name' => 'PC DELL 14" Procesador i3 250GB SSD',
            'description' => 'El computador que has estado esperando para trabajar desde el lugar que quieras con todas las mejores referencias del mercado.',
            'price' => 3799999.00,
            'category_id' => $this->category->id,
        ]);

        $product = Product::first();

        Storage::disk('public')->assertExists($product->image);

        Storage::disk('public')->delete($product->image);
    }

    /**
     * @test
     */
    public function product_name_must_be_required(): void
    {
        $admin = User::factory()->admin()->create();

        $data = $this->getProductValidData([
            'name' => ''
        ]);

        Passport::actingAs($admin);

        $response = $this->postJson(route('api.admin.products.store'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('name');
    }

    /**
     * @test
     */
    public function product_name_must_be_a_string(): void
    {
        $admin = User::factory()->admin()->create();

        $data = $this->getProductValidData([
            'name' => 3897438
        ]);

        Passport::actingAs($admin);

        $response = $this->postJson(route('api.admin.products.store'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('name');
    }

    /**
     * @test
     */
    public function product_name_length_must_contains_at_least_3_characters(): void
    {
        $admin = User::factory()->admin()->create();

        $data = $this->getProductValidData([
            'name' => 'ok'
        ]);

        Passport::actingAs($admin);

        $response = $this->postJson(route('api.admin.products.store'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('name');
    }

    /**
     * @test
     */
    public function product_name_length_must_be_less_than_121_characters(): void
    {
        $admin = User::factory()->admin()->create();

        $data = $this->getProductValidData([
            'name' => $this->getRandomStringOnlyLetters(121)
        ]);

        Passport::actingAs($admin);

        $response = $this->postJson(route('api.admin.products.store'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('name');
    }

    /**
     * @test
     */
    public function product_code_must_be_required(): void
    {
        $admin = User::factory()->admin()->create();

        $data = $this->getProductValidData([
            'code' => ''
        ]);

        Passport::actingAs($admin);

        $response = $this->postJson(route('api.admin.products.store'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('code');
    }

    /**
     * @test
     */
    public function product_code_must_be_a_string(): void
    {
        $admin = User::factory()->admin()->create();

        $data = $this->getProductValidData([
            'code' => 3897438
        ]);

        Passport::actingAs($admin);

        $response = $this->postJson(route('api.admin.products.store'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('code');
    }

    /**
     * @test
     */
    public function product_code_length_must_be_less_than_31_characters(): void
    {
        $admin = User::factory()->admin()->create();

        $data = $this->getProductValidData([
            'code' => $this->getRandomStringOnlyLetters(31)
        ]);

        Passport::actingAs($admin);

        $response = $this->postJson(route('api.admin.products.store'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('code');
    }

    /**
     * @test
     */
    public function product_code_must_be_unique(): void
    {
        $admin = User::factory()->admin()->create();

        Product::factory()->create([
            'code' => 'XAS-2393'
        ]);

        $data = $this->getProductValidData([
            'code' => 'XAS-2393'
        ]);

        Passport::actingAs($admin);

        $response = $this->postJson(route('api.admin.products.store'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('code');
    }

    /**
     * @test
     */
    public function product_description_must_be_a_string(): void
    {
        $admin = User::factory()->admin()->create();

        $data = $this->getProductValidData([
            'description' => 123456789
        ]);

        Passport::actingAs($admin);

        $response = $this->postJson(route('api.admin.products.store'), $data);

        $response->assertStatus(422);

        $response->assertJsonValidationErrorFor('description');
    }

    /**
     * @test
     */
    public function product_description_length_must_contains_at_least_10_characters(): void
    {
        $admin = User::factory()->admin()->create();

        $data = $this->getProductValidData([
            'description' => 'hola'
        ]);

        Passport::actingAs($admin);

        $response = $this->postJson(route('api.admin.products.store'), $data);

        $response->assertStatus(422);

        $response->assertJsonValidationErrorFor('description');
    }

    /**
     * @test
     */
    public function product_price_must_be_required(): void
    {
        $admin = User::factory()->admin()->create();

        $data = $this->getProductValidData([
            'price' => ''
        ]);

        Passport::actingAs($admin);

        $response = $this->postJson(route('api.admin.products.store'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('price');
    }

    /**
     * @test
     */
    public function product_price_must_be_numeric(): void
    {
        $admin = User::factory()->admin()->create();

        $data = $this->getProductValidData([
            'price' => 'Doscientos'
        ]);

        Passport::actingAs($admin);

        $response = $this->postJson(route('api.admin.products.store'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('price');
    }

    /**
     * @test
     */
    public function product_image_must_be_required(): void
    {
        $admin = User::factory()->admin()->create();

        $data = $this->getProductValidData([
            'image' => ''
        ]);

        Passport::actingAs($admin);

        $response = $this->postJson(route('api.admin.products.store'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('image');
    }

    /**
     * @test
     */
    public function product_image_must_be_a_image(): void
    {
        $admin = User::factory()->admin()->create();

        $data = $this->getProductValidData([
            'image' => UploadedFile::fake()->create('document.pdf')
        ]);

        Passport::actingAs($admin);

        $response = $this->postJson(route('api.admin.products.store'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('image');
    }

    /**
     * @test
     */
    public function product_image_must_be_has_a_640_px_min_width(): void
    {
        $admin = User::factory()->admin()->create();

        $data = $this->getProductValidData([
            'image' => UploadedFile::fake()->image('image.jpg', 500, 480)
        ]);

        Passport::actingAs($admin);

        $response = $this->postJson(route('api.admin.products.store'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('image');
    }

    /**
     * @test
     */
    public function product_image_must_be_has_a_480_px_min_height(): void
    {
        $admin = User::factory()->admin()->create();

        $data = $this->getProductValidData([
            'image' => UploadedFile::fake()->image('image.jpg', 640, 320)
        ]);

        Passport::actingAs($admin);

        $response = $this->postJson(route('api.admin.products.store'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('image');
    }

    /**
     * @test
     */
    public function product_category_id_must_be_required(): void
    {
        $admin = User::factory()->admin()->create();

        $data = $this->getProductValidData([
            'category_id' => ''
        ]);

        Passport::actingAs($admin);

        $response = $this->postJson(route('api.admin.products.store'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('category_id');
    }

    /**
     * @test
     */
    public function product_category_id_must_be_numeric(): void
    {
        $admin = User::factory()->admin()->create();

        $data = $this->getProductValidData([
            'category_id' => 'Deportes'
        ]);

        Passport::actingAs($admin);

        $response = $this->postJson(route('api.admin.products.store'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('category_id');
    }

    /**
     * @test
     */
    public function product_category_id_must_exists_on_categories_table(): void
    {
        $admin = User::factory()->admin()->create();

        $data = $this->getProductValidData([
            'category_id' => 8
        ]);

        Passport::actingAs($admin);

        $response = $this->postJson(route('api.admin.products.store'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('category_id');
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

    protected function getProductValidData(array $invalidData = []): array
    {
        $validData = [
            'name' => 'PC DELL 14" Procesador i3 250GB SSD',
            'code' => 'XE2031',
            'description' => 'El computador que has estado esperando para trabajar desde el lugar que quieras con todas las mejores referencias del mercado.',
            'price' => 3799999,
            'image' => UploadedFile::fake()->image('image.jpg', 640, 480),
            'category_id' => $this->category->id,
        ];

        return array_merge($validData, $invalidData);
    }
}
