<?php

namespace Tests\Unit\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
    public function no_category_cannot_be_viewed_by_a_customer(): void
    {
        $this->be($this->customer);

        $this->assertFalse(auth()->user()->can('viewAny', new Category()));
    }

    /**
     * @test
     */
    public function a_category_cannot_be_created_by_a_customer(): void
    {
        $this->be($this->customer);

        $this->assertFalse(auth()->user()->can('create', new Category()));
    }

    /**
     * @test
     */
    public function a_category_cannot_be_updated_by_a_customer(): void
    {
        $category = Category::factory()->create();

        $this->be($this->customer);

        $this->assertFalse(auth()->user()->can('update', $category));
    }
}
