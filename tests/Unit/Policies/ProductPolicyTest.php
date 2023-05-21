<?php

namespace Tests\Unit\Policies;

use Domain\Product\Models\Product;
use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected $customer;

    public function setUp(): void
    {
        parent::setUp();

        $this->customer = User::factory()->customer()->withCustomerProfile()->create();
    }

    /**
     * @test
     */
    public function no_product_cannot_be_viewed_by_a_customer(): void
    {
        $this->be($this->customer);

        $this->assertFalse(auth()->user()->can('viewAny', new Product()));
    }

    /**
     * @test
     */
    public function a_product_cannot_be_created_by_a_customer(): void
    {
        $this->be($this->customer);

        $this->assertFalse(auth()->user()->can('create', new Product()));
    }

    /**
     * @test
     */
    public function a_product_cannot_be_updated_by_a_customer(): void
    {
        $product = Product::factory()->create();

        $this->be($this->customer);

        $this->assertFalse(auth()->user()->can('update', $product));
    }

    /**
     * @test
     */
    public function a_product_status_cannot_be_updated_by_a_customer(): void
    {
        $product = Product::factory()->create();

        $this->be($this->customer);

        $this->assertFalse(auth()->user()->can('updateStatus', $product));
    }
}
