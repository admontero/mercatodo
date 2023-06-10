<?php

namespace Tests\Unit\Policies;

use Domain\Category\Models\Category;
use Domain\Category\Policies\CategoryPolicy;
use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CategoryPolicyTest extends TestCase
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
    public function a_category_cannot_be_viewed_by_any_customer(): void
    {
        Passport::actingAs($this->customer);

        $policy = new CategoryPolicy();

        $canViewAny = $policy->viewAny($this->customer);

        $this->assertFalse($canViewAny);
    }

    /**
     * @test
     */
    public function a_category_cannot_be_viewed_by_a_customer(): void
    {
        Passport::actingAs($this->customer);

        $policy = new CategoryPolicy();

        $canViewCategory = $policy->view($this->customer, new Category());

        $this->assertFalse($canViewCategory);
    }

    /**
     * @test
     */
    public function a_category_cannot_be_created_by_a_customer(): void
    {
        Passport::actingAs($this->customer);

        $policy = new CategoryPolicy();

        $canCreateCategory = $policy->create($this->customer, new Category());

        $this->assertFalse($canCreateCategory);
    }

    /**
     * @test
     */
    public function a_category_cannot_be_updated_by_a_customer(): void
    {
        $category = Category::factory()->create();

        Passport::actingAs($this->customer);

        $policy = new CategoryPolicy();

        $canUpdateCategory = $policy->update($this->customer, $category);

        $this->assertFalse($canUpdateCategory);
    }
}
