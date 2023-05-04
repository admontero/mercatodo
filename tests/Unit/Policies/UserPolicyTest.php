<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPolicyTest extends TestCase
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
    public function no_customer_cannot_be_viewed_by_a_customer(): void
    {
        $this->be($this->customer);

        $this->assertFalse(auth()->user()->can('viewAny', new User()));
    }

    /**
     * @test
     */
    public function a_customer_cannot_be_created_by_a_customer(): void
    {
        $this->be($this->customer);

        $this->assertFalse(auth()->user()->can('create', new User()));
    }

    /**
     * @test
     */
    public function a_customer_cannot_be_updated_by_a_customer(): void
    {
        $customer = User::factory()->customer()->withCustomerProfile()->create();

        $this->be($this->customer);

        $this->assertFalse(auth()->user()->can('update', $customer));
    }

    /**
     * @test
     */
    public function a_customer_status_cannot_be_updated_by_a_customer(): void
    {
        $customer = User::factory()->customer()->withCustomerProfile()->create();

        $this->be($this->customer);

        $this->assertFalse(auth()->user()->can('updateStatus', $customer));
    }

    /**
     * @test
     */
    public function a_customer_cannot_view_a_customer_different_to_itself(): void
    {
        $customer = User::factory()->customer()->withCustomerProfile()->create();

        $this->be($this->customer);

        $this->assertFalse(auth()->user()->can('viewCustomer', $customer));
    }
}
