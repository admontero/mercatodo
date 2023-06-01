<?php

namespace Tests\Unit\Http\Resources;

use App\ApiAdmin\User\Resources\CustomerResource;
use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_customer_resource_must_have_the_required_fields(): void
    {
        $customer = User::factory()
            ->customer()
            ->withCustomerProfile()
            ->create();

        $customerResource = CustomerResource::make($customer)->resolve();

        $this->assertEquals($customer->id, $customerResource['id']);
        $this->assertEquals($customer->email, $customerResource['email']);
        $this->assertEquals($customer->state, $customerResource['state']);
        $this->assertEquals($customer->profileable?->first_name, $customerResource['first_name']);
        $this->assertEquals($customer->profileable?->last_name, $customerResource['last_name']);
        $this->assertEquals($customer->profileable?->document_type, $customerResource['document_type']);
        $this->assertEquals($customer->profileable?->document, $customerResource['document']);
        $this->assertEquals($customer->profileable?->country_id, $customerResource['country_id']);
        $this->assertEquals($customer->profileable?->state_id, $customerResource['state_id']);
        $this->assertEquals($customer->profileable?->city_id, $customerResource['city_id']);
        $this->assertEquals($customer->profileable?->address, $customerResource['address']);
        $this->assertEquals($customer->profileable?->phone, $customerResource['phone']);
        $this->assertEquals($customer->profileable?->cell_phone, $customerResource['cell_phone']);
        $this->assertEquals($customer->created_at->diffForHumans(), $customerResource['ago']);
    }
}
