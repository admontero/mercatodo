<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\CustomerResource;
use App\Models\Customer;
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
        $customer = Customer::factory()->create();

        $customerResource = CustomerResource::make($customer)->resolve();

        $this->assertEquals($customer->id, $customerResource['id']);
        $this->assertEquals($customer->first_name, $customerResource['first_name']);
        $this->assertEquals($customer->last_name, $customerResource['last_name']);
        $this->assertEquals($customer->document_type, $customerResource['document_type']);
        $this->assertEquals($customer->document, $customerResource['document']);
        $this->assertEquals($customer->address, $customerResource['address']);
        $this->assertEquals($customer->phone, $customerResource['phone']);
        $this->assertEquals($customer->cell_phone, $customerResource['cell_phone']);
    }
}
