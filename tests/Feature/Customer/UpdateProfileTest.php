<?php

namespace Tests\Feature\Customer;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UpdateProfileTest extends TestCase
{
    use RefreshDatabase;

    protected $customer;

    public function setUp(): void
    {
        parent::setUp();

        $this->customer = User::factory()
            ->customer()
            ->withCustomerProfile([
                'first_name' => 'Cliente',
                'last_name' => 'Nuevo',
            ])
            ->create();
    }

    /**
     * @test
     */
    public function a_customer_can_update_their_profile(): void
    {
        $this->withoutExceptionHandling();

        $data = [
            'first_name' => 'Cliente',
            'last_name' => 'Actualizado',
            'document_type' => 'CC',
            'document' => '12345678',
            'phone' => '60578236',
        ];

        Passport::actingAs($this->customer);

        $response = $this->putJson(route('api.customers.update-profile', $this->customer), $data);

        $response
            ->assertStatus(201)
            ->assertJson([
                'email' => $this->customer->email,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'document_type' => $data['document_type'],
                'document' => $data['document'],
                'phone' => $data['phone'],
            ]);

        $this->assertDatabaseHas('customer_profiles', [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'document_type' => $data['document_type'],
            'document' => $data['document'],
            'phone' => $data['phone'],
        ]);
    }
}
