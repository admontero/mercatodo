<?php

namespace Tests\Unit\Policies;

use Domain\User\Models\User;
use Domain\User\Policies\UserPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
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
    public function a_customer_cannot_be_viewed_by_another_customer(): void
    {
        $customer = User::factory()->customer()->create();

        Passport::actingAs($this->customer);

        $policy = New UserPolicy();

        $canViewCustomer = $policy->viewAnyCustomer($this->customer, $customer);

        $this->assertFalse($canViewCustomer);
    }

    /**
     * @test
     */
    public function a_customer_cannot_be_updated_by_another_customer(): void
    {
        $customer = User::factory()->customer()->withCustomerProfile()->create();

        Passport::actingAs($this->customer);

        $policy = New UserPolicy();

        $canUpdateCustomer = $policy->updateCustomer($this->customer, $customer);

        $this->assertFalse($canUpdateCustomer);
    }

    /**
     * @test
     */
    public function a_customer_status_cannot_be_updated_by_another_customer(): void
    {
        $customer = User::factory()->customer()->withCustomerProfile()->create();

        Passport::actingAs($this->customer);

        $policy = New UserPolicy();

        $canUpdateStateCustomer = $policy->updateStatus($this->customer, $customer);

        $this->assertFalse($canUpdateStateCustomer);
    }

    /**
     * @test
     */
    public function a_customer_cannot_view_a_customer_different_to_itself(): void
    {
        $customer = User::factory()->customer()->withCustomerProfile()->create();

        Passport::actingAs($this->customer);

        $policy = New UserPolicy();

        $canViewCustomer = $policy->viewCustomer($this->customer, $customer);

        $this->assertFalse($canViewCustomer);
    }

    /**
     * @test
     */
    public function a_customer_cannot_update_a_customer_profile_different_to_itself(): void
    {
        $customer = User::factory()->customer()->withCustomerProfile()->create();

        Passport::actingAs($this->customer);

        $policy = New UserPolicy();

        $canUpdateCustomerProfile = $policy->updateCustomerProfile($this->customer, $customer);

        $this->assertFalse($canUpdateCustomerProfile);
    }
}
