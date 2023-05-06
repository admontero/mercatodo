<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UpdateProductTest extends TestCase
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
    public function admin_can_update_a_product(): void
    {
        $this->withoutExceptionHandling();

        $admin = User::factory()->admin()->create();

        $product = Product::factory()->create($this->getProductValidData());

        $previousImage = $product->image;

        $categoryNew = Category::factory()->create();

        $data = $this->getProductValidData([
            'name' => 'Pista Hot Wheels',
            'code' => 'PHW-23423',
            'description' => '',
            'price' => 159999,
            'stock' => 6,
            'image' => UploadedFile::fake()->image('image-2.jpg', 640, 480),
            'category_id' => $categoryNew->id,
        ]);

        Passport::actingAs($admin);

        $response = $this->putJson(route('api.admin.products.update', $product), $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('products', [
            'name' => 'Pista Hot Wheels',
            'code' => 'PHW-23423',
            'description' => null,
            'price' => 159999,
            'category_id' => $categoryNew->id,
        ]);

        $product->refresh();

        Storage::disk('public')->assertExists($product->image);
        Storage::disk('public')->assertMissing($previousImage);
    }

    /**
     * @test
     */
    public function product_name_is_required_if_you_want_updated_it(): void
    {
        $admin = User::factory()->admin()->create();

        $product = Product::factory()->create($this->getProductValidData());

        $data = $this->getProductValidData([
            'name' => ''
        ]);

        Passport::actingAs($admin);

        $response = $this->putJson(route('api.admin.products.update', $product), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('name');
    }

    /**
     * @test
     */
    public function product_name_must_be_a_string_if_you_want_updated_it(): void
    {
        $admin = User::factory()->admin()->create();

        $product = Product::factory()->create($this->getProductValidData());

        $data = $this->getProductValidData([
            'name' => 3897438
        ]);

        Passport::actingAs($admin);

        $response = $this->putJson(route('api.admin.products.update', $product), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('name');
    }

    /**
     * @test
     */
    public function product_name_length_must_contains_at_least_3_characters_if_you_want_updated_it(): void
    {
        $admin = User::factory()->admin()->create();

        $product = Product::factory()->create($this->getProductValidData());

        $data = $this->getProductValidData([
            'name' => 'ok'
        ]);

        Passport::actingAs($admin);

        $response = $this->putJson(route('api.admin.products.update', $product), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('name');
    }

    /**
     * @test
     */
    public function product_name_length_must_be_less_than_121_characters_if_you_want_updated_it(): void
    {
        $admin = User::factory()->admin()->create();

        $product = Product::factory()->create($this->getProductValidData());

        $data = $this->getProductValidData([
            'name' => $this->getRandomStringOnlyLetters(121)
        ]);

        Passport::actingAs($admin);

        $response = $this->putJson(route('api.admin.products.update', $product), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('name');
    }

    /**
     * @test
     */
    public function product_code_must_be_required_if_you_want_updated_it(): void
    {
        $admin = User::factory()->admin()->create();

        $product = Product::factory()->create($this->getProductValidData());

        $data = $this->getProductValidData([
            'code' => ''
        ]);

        Passport::actingAs($admin);

        $response = $this->putJson(route('api.admin.products.update', $product), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('code');
    }

    /**
     * @test
     */
    public function product_code_must_be_a_string_if_you_want_updated_it(): void
    {
        $admin = User::factory()->admin()->create();

        $product = Product::factory()->create($this->getProductValidData());

        $data = $this->getProductValidData([
            'code' => 3897438
        ]);

        Passport::actingAs($admin);

        $response = $this->putJson(route('api.admin.products.update', $product), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('code');
    }

    /**
     * @test
     */
    public function product_code_length_must_be_less_than_31_characters_if_you_want_updated_it(): void
    {
        $admin = User::factory()->admin()->create();

        $product = Product::factory()->create($this->getProductValidData());

        $data = $this->getProductValidData([
            'code' => $this->getRandomStringOnlyLetters(31)
        ]);

        Passport::actingAs($admin);

        $response = $this->putJson(route('api.admin.products.update', $product), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('code');
    }

    /**
     * @test
     */
    public function product_code_must_be_unique_if_you_want_updated_it_except_itself(): void
    {
        $admin = User::factory()->admin()->create();

        Product::factory()->create([
            'code' => 'XAS-2393'
        ]);

        $product = Product::factory()->create($this->getProductValidData([
            'code' => 'GX-080'
        ]));

        $data = $this->getProductValidData([
            'code' => 'XAS-2393'
        ]);

        Passport::actingAs($admin);

        $response = $this->putJson(route('api.admin.products.update', $product), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('code');
    }

    /**
     * @test
     */
    public function product_description_must_be_a_string_if_you_want_updated_it(): void
    {
        $admin = User::factory()->admin()->create();

        $product = Product::factory()->create($this->getProductValidData());

        $data = $this->getProductValidData([
            'description' => 123456789
        ]);

        Passport::actingAs($admin);

        $response = $this->putJson(route('api.admin.products.update', $product), $data);

        $response->assertStatus(422);

        $response->assertJsonValidationErrorFor('description');
    }

    /**
     * @test
     */
    public function product_description_length_must_contains_at_least_10_characters_if_you_want_updated_it(): void
    {
        $admin = User::factory()->admin()->create();

        $product = Product::factory()->create($this->getProductValidData());

        $data = $this->getProductValidData([
            'description' => 'hola'
        ]);

        Passport::actingAs($admin);

        $response = $this->putJson(route('api.admin.products.update', $product), $data);

        $response->assertStatus(422);

        $response->assertJsonValidationErrorFor('description');
    }

    /**
     * @test
     */
    public function product_price_must_be_required_if_you_want_updated_it(): void
    {
        $admin = User::factory()->admin()->create();

        $product = Product::factory()->create($this->getProductValidData());

        $data = $this->getProductValidData([
            'price' => ''
        ]);

        Passport::actingAs($admin);

        $response = $this->putJson(route('api.admin.products.update', $product), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('price');
    }

    /**
     * @test
     */
    public function product_price_must_be_numeric_if_you_want_updated_it(): void
    {
        $admin = User::factory()->admin()->create();

        $product = Product::factory()->create($this->getProductValidData());

        $data = $this->getProductValidData([
            'price' => 'Doscientos'
        ]);

        Passport::actingAs($admin);

        $response = $this->putJson(route('api.admin.products.update', $product), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('price');
    }

    /**
     * @test
     */
    public function product_stock_must_be_numeric_if_you_want_updated_it(): void
    {
        $admin = User::factory()->admin()->create();

        $product = Product::factory()->create($this->getProductValidData());

        $data = $this->getProductValidData([
            'stock' => 'Tres'
        ]);

        Passport::actingAs($admin);

        $response = $this->putJson(route('api.admin.products.update', $product), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('stock');
    }

    /**
     * @test
     */
    public function product_stock_must_be_greater_or_equal_to_zero_if_you_want_updated_it(): void
    {
        $admin = User::factory()->admin()->create();

        $product = Product::factory()->create($this->getProductValidData());

        $data = $this->getProductValidData([
            'stock' => -8
        ]);

        Passport::actingAs($admin);

        $response = $this->putJson(route('api.admin.products.update', $product), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('stock');
    }

    /**
     * @test
     */
    public function product_image_must_be_a_image_if_you_want_updated_it(): void
    {
        $admin = User::factory()->admin()->create();

        $product = Product::factory()->create($this->getProductValidData());

        $data = $this->getProductValidData([
            'image' => UploadedFile::fake()->create('document.pdf')
        ]);

        Passport::actingAs($admin);

        $response = $this->putJson(route('api.admin.products.update', $product), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('image');
    }

    /**
     * @test
     */
    public function product_image_must_be_has_a_640_px_min_width_if_you_want_updated_it(): void
    {
        $admin = User::factory()->admin()->create();

        $product = Product::factory()->create($this->getProductValidData());

        $data = $this->getProductValidData([
            'image' => UploadedFile::fake()->image('image.jpg', 500, 480)
        ]);

        Passport::actingAs($admin);

        $response = $this->putJson(route('api.admin.products.update', $product), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('image');
    }

    /**
     * @test
     */
    public function product_image_must_be_has_a_480_px_min_height_if_you_want_updated_it(): void
    {
        $admin = User::factory()->admin()->create();

        $product = Product::factory()->create($this->getProductValidData());

        $data = $this->getProductValidData([
            'image' => UploadedFile::fake()->image('image.jpg', 640, 320)
        ]);

        Passport::actingAs($admin);

        $response = $this->putJson(route('api.admin.products.update', $product), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('image');
    }

    /**
     * @test
     */
    public function product_category_id_must_be_required_if_you_want_updated_it(): void
    {
        $admin = User::factory()->admin()->create();

        $product = Product::factory()->create($this->getProductValidData());

        $data = $this->getProductValidData([
            'category_id' => ''
        ]);

        Passport::actingAs($admin);

        $response = $this->putJson(route('api.admin.products.update', $product), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('category_id');
    }

    /**
     * @test
     */
    public function product_category_id_must_be_numeric_if_you_want_updated_it(): void
    {
        $admin = User::factory()->admin()->create();

        $product = Product::factory()->create($this->getProductValidData());

        $data = $this->getProductValidData([
            'category_id' => 'Deportes'
        ]);

        Passport::actingAs($admin);

        $response = $this->putJson(route('api.admin.products.update', $product), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('category_id');
    }

    /**
     * @test
     */
    public function product_category_id_must_exists_on_categories_table_if_you_want_updated_it(): void
    {
        $admin = User::factory()->admin()->create();

        $product = Product::factory()->create($this->getProductValidData());

        $data = $this->getProductValidData([
            'category_id' => 8
        ]);

        Passport::actingAs($admin);

        $response = $this->putJson(route('api.admin.products.update', $product), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('category_id');
    }

    protected function getProductValidData(array $invalidData = []): array
    {
        $validData = [
            'name' => 'PC DELL 14" Procesador i3 250GB SSD',
            'code' => 'XE2031',
            'description' => 'El computador que has estado esperando para trabajar desde el lugar que quieras con todas las mejores referencias del mercado.',
            'price' => 3799999,
            'stock' => 6,
            'image' => UploadedFile::fake()->image('image.jpg', 640, 480),
            'category_id' => $this->category->id,
        ];

        return array_merge($validData, $invalidData);
    }
}
