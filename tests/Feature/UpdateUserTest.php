<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function admin_can_update_a_customer_data(): void
    {
        $this->withoutExceptionHandling();

        $this->seed(RoleSeeder::class);

        $admin = User::factory()->admin()->create();

        $customer = User::factory()->create([
            'first_name' => 'Usuario',
            'last_name' => 'Nuevo',
        ]);

        $data = [
            'first_name' => 'Cliente',
            'last_name' => 'Actualizado',
            'document_type' => 'CC',
            'document' => '12345678',
        ];

        Passport::actingAs($admin);

        $response = $this->putJson("/api/customers/{$customer->id}", $data);

        $response->assertStatus(201);

        $customer->refresh();

        $this->assertDatabaseHas('users', [
            'id' => $customer->id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
        ]);
    }

    /**
     * @test
     */
    public function customer_first_name_must_be_required(): void
    {
        $this->seed(RoleSeeder::class);

        $admin = User::factory()->admin()->create();

        $customer = User::factory()->create([
            'first_name' => 'Usuario',
            'last_name' => 'Nuevo',
        ]);

        $data = [
            'first_name' => '',
            'last_name' => 'Actualizado',
            'document_type' => 'CC',
            'document' => '12345678',
        ];

        Passport::actingAs($admin);

        $response = $this->putJson("/api/customers/{$customer->id}", $data);

        $response->assertStatus(422);

        $customer->refresh();

        $response->assertJsonValidationErrorFor('first_name');
    }

    /**
     * @test
     */
    public function customer_first_name_must_be_a_string(): void
    {
        $this->seed(RoleSeeder::class);

        $admin = User::factory()->admin()->create();

        $customer = User::factory()->create([
            'first_name' => 'Usuario',
            'last_name' => 'Nuevo',
        ]);

        $data = [
            'first_name' => 1231232,
            'last_name' => 'Actualizado',
            'document_type' => 'CC',
            'document' => '12345678',
        ];

        Passport::actingAs($admin);

        $response = $this->putJson("/api/customers/{$customer->id}", $data);

        $response->assertStatus(422);

        $customer->refresh();

        $response->assertJsonValidationErrorFor('first_name');
    }

    /**
     * @test
     */
    public function customer_first_name_length_must_be_less_than_61_characters(): void
    {
        $this->seed(RoleSeeder::class);

        $admin = User::factory()->admin()->create();

        $customer = User::factory()->create([
            'first_name' => 'Usuario',
            'last_name' => 'Nuevo',
        ]);

        $data = [
            'first_name' => $this->getRandomStringOnlyLetters(61),
            'last_name' => 'Actualizado',
            'document_type' => 'CC',
            'document' => '12345678',
        ];

        Passport::actingAs($admin);

        $response = $this->putJson("/api/customers/{$customer->id}", $data);

        $response->assertStatus(422);

        $customer->refresh();

        $response->assertJsonValidationErrorFor('first_name');
    }

    /**
     * @test
     */
    public function customer_last_name_must_be_a_required(): void
    {
        $this->seed(RoleSeeder::class);

        $admin = User::factory()->admin()->create();

        $customer = User::factory()->create([
            'first_name' => 'Usuario',
            'last_name' => 'Nuevo',
        ]);

        $data = [
            'first_name' => 'Usuario',
            'last_name' => '',
            'document_type' => 'CC',
            'document' => '12345678',
        ];

        Passport::actingAs($admin);

        $response = $this->putJson("/api/customers/{$customer->id}", $data);

        $response->assertStatus(422);

        $customer->refresh();

        $response->assertJsonValidationErrorFor('last_name');
    }

    /**
     * @test
     */
    public function customer_last_name_must_be_a_string(): void
    {
        $this->seed(RoleSeeder::class);

        $admin = User::factory()->admin()->create();

        $customer = User::factory()->create([
            'first_name' => 'Usuario',
            'last_name' => 'Nuevo',
        ]);

        $data = [
            'first_name' => 'Usuario',
            'last_name' => 273982,
            'document_type' => 'CC',
            'document' => '12345678',
        ];

        Passport::actingAs($admin);

        $response = $this->putJson("/api/customers/{$customer->id}", $data);

        $response->assertStatus(422);

        $customer->refresh();

        $response->assertJsonValidationErrorFor('last_name');
    }

    /**
     * @test
     */
    public function customer_last_name_length_must_be_less_than_81_characters(): void
    {
        $this->seed(RoleSeeder::class);

        $admin = User::factory()->admin()->create();

        $customer = User::factory()->create([
            'first_name' => 'Usuario',
            'last_name' => 'Nuevo',
        ]);

        $data = [
            'first_name' => 'Usuario',
            'last_name' => $this->getRandomStringOnlyLetters(81),
            'document_type' => 'CC',
            'document' => '12345678',
        ];

        Passport::actingAs($admin);

        $response = $this->putJson("/api/customers/{$customer->id}", $data);

        $response->assertStatus(422);

        $customer->refresh();

        $response->assertJsonValidationErrorFor('last_name');
    }

    /**
     * @test
     */
    public function customer_document_type_must_be_a_string(): void
    {
        $this->seed(RoleSeeder::class);

        $admin = User::factory()->admin()->create();

        $customer = User::factory()->create([
            'first_name' => 'Usuario',
            'last_name' => 'Nuevo',
        ]);

        $data = [
            'first_name' => 'Usuario',
            'last_name' => 'Actualizado',
            'document_type' => 2983782,
            'document' => '12345678',
        ];

        Passport::actingAs($admin);

        $response = $this->putJson("/api/customers/{$customer->id}", $data);

        $response->assertStatus(422);

        $customer->refresh();

        $response->assertJsonValidationErrorFor('document_type');
    }

    /**
     * @test
     */
    public function customer_document_type_is_required_when_document_is_not_empty(): void
    {
        $this->seed(RoleSeeder::class);

        $admin = User::factory()->admin()->create();

        $customer = User::factory()->create([
            'first_name' => 'Usuario',
            'last_name' => 'Nuevo',
        ]);

        $data = [
            'first_name' => 'Usuario',
            'last_name' => 'Actualizado',
            'document_type' => '',
            'document' => '103478232',
        ];

        Passport::actingAs($admin);

        $response = $this->putJson("/api/customers/{$customer->id}", $data);

        $response->assertStatus(422);

        $customer->refresh();

        $response->assertJsonValidationErrorFor('document_type');
    }

    /**
     * @test
     */
    public function customer_document_must_be_a_string(): void
    {
        $this->seed(RoleSeeder::class);

        $admin = User::factory()->admin()->create();

        $customer = User::factory()->create([
            'first_name' => 'Usuario',
            'last_name' => 'Nuevo',
        ]);

        $data = [
            'first_name' => 'Usuario',
            'last_name' => 'Actualizado',
            'document_type' => 'CC',
            'document' => 12345678,
        ];

        Passport::actingAs($admin);

        $response = $this->putJson("/api/customers/{$customer->id}", $data);

        $response->assertStatus(422);

        $customer->refresh();

        $response->assertJsonValidationErrorFor('document');
    }

    /**
     * @test
     */
    public function customer_document_length_must_be_less_than_31_characters(): void
    {
        $this->seed(RoleSeeder::class);

        $admin = User::factory()->admin()->create();

        $customer = User::factory()->create([
            'first_name' => 'Usuario',
            'last_name' => 'Nuevo',
        ]);

        $data = [
            'first_name' => 'Usuario',
            'last_name' => 'Actualizado',
            'document_type' => 'CC',
            'document' => $this->getRandomStringOnlyLetters(31),
        ];

        Passport::actingAs($admin);

        $response = $this->putJson("/api/customers/{$customer->id}", $data);

        $response->assertStatus(422);

        $customer->refresh();

        $response->assertJsonValidationErrorFor('document');
    }

    /**
     * @test
     */
    public function customer_document_is_required_when_document_type_is_not_empty(): void
    {
        $this->seed(RoleSeeder::class);

        $admin = User::factory()->admin()->create();

        $customer = User::factory()->create([
            'first_name' => 'Usuario',
            'last_name' => 'Nuevo',
        ]);

        $data = [
            'first_name' => 'Usuario',
            'last_name' => 'Actualizado',
            'document_type' => 'CC',
            'document' => '',
        ];

        Passport::actingAs($admin);

        $response = $this->putJson("/api/customers/{$customer->id}", $data);

        $response->assertStatus(422);

        $customer->refresh();

        $response->assertJsonValidationErrorFor('document');
    }

    /**
     * @test
     */
    public function customer_address_must_be_a_string(): void
    {
        $this->seed(RoleSeeder::class);

        $admin = User::factory()->admin()->create();

        $customer = User::factory()->create([
            'first_name' => 'Usuario',
            'last_name' => 'Nuevo',
        ]);

        $data = [
            'first_name' => 'Usuario',
            'last_name' => 'Actualizado',
            'document_type' => 'CC',
            'document' => '12345678',
            'address' => 2873822,
        ];

        Passport::actingAs($admin);

        $response = $this->putJson("/api/customers/{$customer->id}", $data);

        $response->assertStatus(422);

        $customer->refresh();

        $response->assertJsonValidationErrorFor('address');
    }

    /**
     * @test
     */
    public function customer_address_length_must_be_less_than_121_characters(): void
    {
        $this->seed(RoleSeeder::class);

        $admin = User::factory()->admin()->create();

        $customer = User::factory()->create([
            'first_name' => 'Usuario',
            'last_name' => 'Nuevo',
        ]);

        $data = [
            'first_name' => 'Usuario',
            'last_name' => 'Actualizado',
            'address' => $this->getRandomStringOnlyLetters(121),
        ];

        Passport::actingAs($admin);

        $response = $this->putJson("/api/customers/{$customer->id}", $data);

        $response->assertStatus(422);

        $customer->refresh();

        $response->assertJsonValidationErrorFor('address');
    }

    /**
     * @test
     */
    public function customer_phone_must_be_a_string(): void
    {
        $this->seed(RoleSeeder::class);

        $admin = User::factory()->admin()->create();

        $customer = User::factory()->create([
            'first_name' => 'Usuario',
            'last_name' => 'Nuevo',
        ]);

        $data = [
            'first_name' => 'Usuario',
            'last_name' => 'Actualizado',
            'document_type' => 'CC',
            'document' => '12345678',
            'phone' => 23287923,
        ];

        Passport::actingAs($admin);

        $response = $this->putJson("/api/customers/{$customer->id}", $data);

        $response->assertStatus(422);

        $customer->refresh();

        $response->assertJsonValidationErrorFor('phone');
    }

    /**
     * @test
     */
    public function customer_phone_length_must_be_less_than_21_characters(): void
    {
        $this->seed(RoleSeeder::class);

        $admin = User::factory()->admin()->create();

        $customer = User::factory()->create([
            'first_name' => 'Usuario',
            'last_name' => 'Nuevo',
        ]);

        $data = [
            'first_name' => 'Usuario',
            'last_name' => 'Actualizado',
            'phone' => $this->getRandomStringOnlyLetters(21),
        ];

        Passport::actingAs($admin);

        $response = $this->putJson("/api/customers/{$customer->id}", $data);

        $response->assertStatus(422);

        $customer->refresh();

        $response->assertJsonValidationErrorFor('phone');
    }

    /**
     * @test
     */
    public function customer_cell_phone_must_be_a_string(): void
    {
        $this->seed(RoleSeeder::class);

        $admin = User::factory()->admin()->create();

        $customer = User::factory()->create([
            'first_name' => 'Usuario',
            'last_name' => 'Nuevo',
        ]);

        $data = [
            'first_name' => 'Usuario',
            'last_name' => 'Actualizado',
            'document_type' => 'CC',
            'document' => '12345678',
            'cell_phone' => 23287923,
        ];

        Passport::actingAs($admin);

        $response = $this->putJson("/api/customers/{$customer->id}", $data);

        $response->assertStatus(422);

        $customer->refresh();

        $response->assertJsonValidationErrorFor('cell_phone');
    }

    /**
     * @test
     */
    public function customer_cell_phone_length_must_be_less_than_26_characters(): void
    {
        $this->seed(RoleSeeder::class);

        $admin = User::factory()->admin()->create();

        $customer = User::factory()->create([
            'first_name' => 'Usuario',
            'last_name' => 'Nuevo',
        ]);

        $data = [
            'first_name' => 'Usuario',
            'last_name' => 'Actualizado',
            'cell_phone' => $this->getRandomStringOnlyLetters(26),
        ];

        Passport::actingAs($admin);

        $response = $this->putJson("/api/customers/{$customer->id}", $data);

        $response->assertStatus(422);

        $customer->refresh();

        $response->assertJsonValidationErrorFor('cell_phone');
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
