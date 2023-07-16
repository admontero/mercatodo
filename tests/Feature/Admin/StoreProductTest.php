<?php

namespace Tests\Feature\Admin;

use Domain\Category\Models\Category;
use Domain\Product\Models\Product;
use Domain\Product\Services\ProductService;
use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Mockery;
use Tests\TestCase;

class StoreProductTest extends TestCase
{
    use RefreshDatabase;

    protected $category;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

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
            'stock' => 10,
            'category_id' => $this->category->id,
        ]);

        $product = Product::first();

        Storage::disk('public')->assertExists($product->image);
    }

    /**
     * @test
     */
    public function product_name_must_be_required(): void
    {
        $admin = User::factory()->admin()->create();

        $data = $this->getProductValidData([
            'name' => '',
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
            'name' => 3897438,
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
            'name' => 'ok',
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
            'name' => $this->getRandomStringOnlyLetters(121),
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
            'code' => '',
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
            'code' => 3897438,
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
            'code' => $this->getRandomStringOnlyLetters(31),
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
            'code' => 'XAS-2393',
        ]);

        $data = $this->getProductValidData([
            'code' => 'XAS-2393',
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
            'description' => 123456789,
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
            'description' => 'hola',
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
            'price' => '',
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
            'price' => 'Doscientos',
        ]);

        Passport::actingAs($admin);

        $response = $this->postJson(route('api.admin.products.store'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('price');
    }

    /**
     * @test
     */
    public function product_stock_must_be_numeric(): void
    {
        $admin = User::factory()->admin()->create();

        $data = $this->getProductValidData([
            'stock' => 'Tres',
        ]);

        Passport::actingAs($admin);

        $response = $this->postJson(route('api.admin.products.store'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('stock');
    }

    /**
     * @test
     */
    public function product_stock_must_be_greater_or_equal_to_zero(): void
    {
        $admin = User::factory()->admin()->create();

        $data = $this->getProductValidData([
            'stock' => -8,
        ]);

        Passport::actingAs($admin);

        $response = $this->postJson(route('api.admin.products.store'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('stock');
    }

    /**
     * @test
     */
    public function product_image_must_be_required(): void
    {
        $admin = User::factory()->admin()->create();

        $data = $this->getProductValidData([
            'image' => '',
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
            'image' => UploadedFile::fake()->create('document.pdf'),
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
            'image' => UploadedFile::fake()->image('image.jpg', 500, 480),
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
            'image' => UploadedFile::fake()->image('image.jpg', 640, 320),
        ]);

        Passport::actingAs($admin);

        $response = $this->postJson(route('api.admin.products.store'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('image');
    }

    /**
     * @test
     */
    public function product_image_weight_cannot_be_greater_than_2_mb(): void
    {
        $admin = User::factory()->admin()->create();

        $data = $this->getProductValidData([
            'image' => UploadedFile::fake()->image('image.jpg', 640, 480)->size(4096),
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
            'category_id' => '',
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
            'category_id' => 'Deportes',
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
            'category_id' => 8,
        ]);

        Passport::actingAs($admin);

        $response = $this->postJson(route('api.admin.products.store'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('category_id');
    }

    /** @test */
    public function it_returns_an_error_500_if_the_server_fail(): void
    {
        $admin = User::factory()->admin()->create();

        $data = $this->getProductValidData();

        Passport::actingAs($admin);

        $serviceMock = Mockery::mock(ProductService::class);
        $serviceMock->shouldReceive('uploadImage')
                    ->once()
                    ->andThrow(new \Exception());

        $this->app->instance(ProductService::class, $serviceMock);

        $this->postJson(route('api.admin.products.store'), $data)
            ->assertStatus(500)
            ->assertJsonFragment(['status' => 'error', 'errors' => 'Something went wrong.']);
    }

    /** @test */
    public function it_returns_an_error_429_if_the_rate_limit_is_exceeded(): void
    {
        $admin = User::factory()->admin()->create();

        $data = $this->getProductValidData();

        Passport::actingAs($admin);

        $serviceMock = Mockery::mock(ProductService::class);
        $serviceMock->shouldReceive('uploadImage')
                    ->once()
                    ->andThrow(new \Illuminate\Http\Exceptions\ThrottleRequestsException());

        $this->app->instance(ProductService::class, $serviceMock);

        $this->postJson(route('api.admin.products.store'), $data)
            ->assertStatus(429)
            ->assertJsonFragment(['status' => 'error', 'errors' => 'API Limit Reached.']);
    }

    protected function getProductValidData(array $invalidData = []): array
    {
        $validData = [
            'name' => 'PC DELL 14" Procesador i3 250GB SSD',
            'code' => 'XE2031',
            'description' => 'El computador que has estado esperando para trabajar desde el lugar que quieras con todas las mejores referencias del mercado.',
            'price' => 3799999,
            'stock' => 10,
            'image' => UploadedFile::fake()->image('image.jpg', 640, 480),
            'category_id' => $this->category->id,
        ];

        return array_merge($validData, $invalidData);
    }
}
