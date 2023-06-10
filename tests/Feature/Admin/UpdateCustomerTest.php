<?php

namespace Tests\Feature\Admin;

use Domain\City\Models\City;
use Domain\Country\Models\Country;
use Domain\State\Models\State;
use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UpdateCustomerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected $customer;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->admin()->create();

        $this->customer = User::factory()
            ->customer()
            ->withCustomerProfile([
                'first_name' => 'Usuario',
                'last_name' => 'Nuevo',
            ])
            ->create();
    }

    /**
     * @test
     */
    public function admin_can_update_a_customer_data(): void
    {
        $this->withoutExceptionHandling();

        $country = Country::factory()->create();
        $state = State::factory()->create(['country_id' => $country->id]);
        $city = City::factory()->create(['state_id' => $state->id]);

        $data = [
            'first_name' => 'Cliente',
            'last_name' => 'Actualizado',
            'document_type' => 'CC',
            'document' => '12345678',
            'country_id' => $country->id,
            'state_id' => $state->id,
            'city_id' => $city->id,
        ];

        Passport::actingAs($this->admin);

        $response = $this->putJson(route('api.admin.customers.update', $this->customer), $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'id' => $this->customer->id,
        ]);

        $this->assertDatabaseHas('customer_profiles', [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'document_type' => $data['document_type'],
            'document' => $data['document'],
            'country_id' => $data['country_id'],
            'state_id' => $data['state_id'],
            'city_id' => $data['city_id'],
        ]);
    }

    /**
     * @test
     */
    public function customer_first_name_must_be_required(): void
    {
        $data = [
            'first_name' => '',
            'last_name' => 'Actualizado',
            'document_type' => 'CC',
            'document' => '12345678',
        ];

        Passport::actingAs($this->admin);

        $response = $this->putJson(route('api.admin.customers.update', $this->customer), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('first_name');
    }

    /**
     * @test
     */
    public function customer_first_name_must_be_a_string(): void
    {
        $data = [
            'first_name' => 1231232,
            'last_name' => 'Actualizado',
            'document_type' => 'CC',
            'document' => '12345678',
        ];

        Passport::actingAs($this->admin);

        $response = $this->putJson(route('api.admin.customers.update', $this->customer), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('first_name');
    }

    /**
     * @test
     */
    public function customer_first_name_length_must_be_less_than_61_characters(): void
    {
        $data = [
            'first_name' => $this->getRandomStringOnlyLetters(61),
            'last_name' => 'Actualizado',
            'document_type' => 'CC',
            'document' => '12345678',
        ];

        Passport::actingAs($this->admin);

        $response = $this->putJson(route('api.admin.customers.update', $this->customer), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('first_name');
    }

    /**
     * @test
     */
    public function customer_last_name_must_be_a_required(): void
    {
        $data = [
            'first_name' => 'Usuario',
            'last_name' => '',
            'document_type' => 'CC',
            'document' => '12345678',
        ];

        Passport::actingAs($this->admin);

        $response = $this->putJson(route('api.admin.customers.update', $this->customer), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('last_name');
    }

    /**
     * @test
     */
    public function customer_last_name_must_be_a_string(): void
    {
        $data = [
            'first_name' => 'Usuario',
            'last_name' => 273982,
            'document_type' => 'CC',
            'document' => '12345678',
        ];

        Passport::actingAs($this->admin);

        $response = $this->putJson(route('api.admin.customers.update', $this->customer), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('last_name');
    }

    /**
     * @test
     */
    public function customer_last_name_length_must_be_less_than_81_characters(): void
    {
        $data = [
            'first_name' => 'Usuario',
            'last_name' => $this->getRandomStringOnlyLetters(81),
            'document_type' => 'CC',
            'document' => '12345678',
        ];

        Passport::actingAs($this->admin);

        $response = $this->putJson(route('api.admin.customers.update', $this->customer), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('last_name');
    }

    /**
     * @test
     */
    public function customer_document_type_must_be_a_string(): void
    {
        $data = [
            'first_name' => 'Usuario',
            'last_name' => 'Actualizado',
            'document_type' => 2983782,
            'document' => '12345678',
        ];

        Passport::actingAs($this->admin);

        $response = $this->putJson(route('api.admin.customers.update', $this->customer), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('document_type');
    }

    /**
     * @test
     */
    public function customer_document_type_is_required_when_document_is_not_empty(): void
    {
        $data = [
            'first_name' => 'Usuario',
            'last_name' => 'Actualizado',
            'document_type' => '',
            'document' => '103478232',
        ];

        Passport::actingAs($this->admin);

        $response = $this->putJson(route('api.admin.customers.update', $this->customer), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('document_type');
    }

    /**
     * @test
     */
    public function customer_document_must_be_a_string(): void
    {
        $data = [
            'first_name' => 'Usuario',
            'last_name' => 'Actualizado',
            'document_type' => 'CC',
            'document' => 12345678,
        ];

        Passport::actingAs($this->admin);

        $response = $this->putJson(route('api.admin.customers.update', $this->customer), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('document');
    }

    /**
     * @test
     */
    public function customer_document_length_must_be_less_than_31_characters(): void
    {
        $data = [
            'first_name' => 'Usuario',
            'last_name' => 'Actualizado',
            'document_type' => 'CC',
            'document' => $this->getRandomStringOnlyLetters(31),
        ];

        Passport::actingAs($this->admin);

        $response = $this->putJson(route('api.admin.customers.update', $this->customer), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('document');
    }

    /**
     * @test
     */
    public function customer_document_is_required_when_document_type_is_not_empty(): void
    {
        $data = [
            'first_name' => 'Usuario',
            'last_name' => 'Actualizado',
            'document_type' => 'CC',
            'document' => '',
        ];

        Passport::actingAs($this->admin);

        $response = $this->putJson(route('api.admin.customers.update', $this->customer), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('document');
    }

    /**
     * @test
     */
    public function country_id_must_be_numeric(): void
    {
        $data = [
            'first_name' => 'Usuario',
            'last_name' => 'Actualizado',
            'document_type' => 'CC',
            'document' => '19328472',
            'country_id' => 'Colombia',
        ];

        Passport::actingAs($this->admin);

        $response = $this->putJson(route('api.admin.customers.update', $this->customer), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('country_id');
    }

    /**
     * @test
     */
    public function country_id_must_exists_at_the_countries_table(): void
    {
        $data = [
            'first_name' => 'Usuario',
            'last_name' => 'Actualizado',
            'document_type' => 'CC',
            'document' => '19328472',
            'country_id' => '0',
        ];

        Passport::actingAs($this->admin);

        $response = $this->putJson(route('api.admin.customers.update', $this->customer), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('country_id');
    }

    /**
     * @test
     */
    public function state_id_must_be_numeric(): void
    {
        $data = [
            'first_name' => 'Usuario',
            'last_name' => 'Actualizado',
            'document_type' => 'CC',
            'document' => '19328472',
            'country_id' => '1',
            'state_id' => 'Valle del Cauca',
        ];

        Passport::actingAs($this->admin);

        $response = $this->putJson(route('api.admin.customers.update', $this->customer), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('state_id');
    }

    /**
     * @test
     */
    public function state_id_must_exists_at_the_states_table(): void
    {
        $data = [
            'first_name' => 'Usuario',
            'last_name' => 'Actualizado',
            'document_type' => 'CC',
            'document' => '19328472',
            'country_id' => '1',
            'state_id' => '0',
        ];

        Passport::actingAs($this->admin);

        $response = $this->putJson(route('api.admin.customers.update', $this->customer), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('state_id');
    }

    /**
     * @test
     */
    public function city_id_must_be_numeric(): void
    {
        $data = [
            'first_name' => 'Usuario',
            'last_name' => 'Actualizado',
            'document_type' => 'CC',
            'document' => '19328472',
            'country_id' => '1',
            'state_id' => '5',
            'city_id' => 'Cali',
        ];

        Passport::actingAs($this->admin);

        $response = $this->putJson(route('api.admin.customers.update', $this->customer), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('city_id');
    }

    /**
     * @test
     */
    public function city_id_must_exists_at_the_cities_table(): void
    {
        $data = [
            'first_name' => 'Usuario',
            'last_name' => 'Actualizado',
            'document_type' => 'CC',
            'document' => '19328472',
            'country_id' => '1',
            'state_id' => '5',
            'city_id' => '0',
        ];

        Passport::actingAs($this->admin);

        $response = $this->putJson(route('api.admin.customers.update', $this->customer), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('city_id');
    }

    /**
     * @test
     */
    public function customer_address_must_be_a_string(): void
    {
        $data = [
            'first_name' => 'Usuario',
            'last_name' => 'Actualizado',
            'document_type' => 'CC',
            'document' => '12345678',
            'address' => 2873822,
        ];

        Passport::actingAs($this->admin);

        $response = $this->putJson(route('api.admin.customers.update', $this->customer), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('address');
    }

    /**
     * @test
     */
    public function customer_address_length_must_be_less_than_121_characters(): void
    {
        $data = [
            'first_name' => 'Usuario',
            'last_name' => 'Actualizado',
            'address' => $this->getRandomStringOnlyLetters(121),
        ];

        Passport::actingAs($this->admin);

        $response = $this->putJson(route('api.admin.customers.update', $this->customer), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('address');
    }

    /**
     * @test
     */
    public function customer_phone_must_be_a_string(): void
    {
        $data = [
            'first_name' => 'Usuario',
            'last_name' => 'Actualizado',
            'document_type' => 'CC',
            'document' => '12345678',
            'phone' => 23287923,
        ];

        Passport::actingAs($this->admin);

        $response = $this->putJson(route('api.admin.customers.update', $this->customer), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('phone');
    }

    /**
     * @test
     */
    public function customer_phone_length_must_be_less_than_21_characters(): void
    {
        $data = [
            'first_name' => 'Usuario',
            'last_name' => 'Actualizado',
            'phone' => $this->getRandomStringOnlyLetters(21),
        ];

        Passport::actingAs($this->admin);

        $response = $this->putJson(route('api.admin.customers.update', $this->customer), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('phone');
    }

    /**
     * @test
     */
    public function customer_cell_phone_must_be_a_string(): void
    {
        $data = [
            'first_name' => 'Usuario',
            'last_name' => 'Actualizado',
            'document_type' => 'CC',
            'document' => '12345678',
            'cell_phone' => 23287923,
        ];

        Passport::actingAs($this->admin);

        $response = $this->putJson(route('api.admin.customers.update', $this->customer), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('cell_phone');
    }

    /**
     * @test
     */
    public function customer_cell_phone_length_must_be_less_than_26_characters(): void
    {
        $data = [
            'first_name' => 'Usuario',
            'last_name' => 'Actualizado',
            'cell_phone' => $this->getRandomStringOnlyLetters(26),
        ];

        Passport::actingAs($this->admin);

        $response = $this->putJson(route('api.admin.customers.update', $this->customer), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('cell_phone');
    }
}
