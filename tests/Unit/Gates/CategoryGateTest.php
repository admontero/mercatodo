<?php

namespace Tests\Unit\Gates;

use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CategoryGateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function manage_category_gate(): void
    {
        $this->withoutVite();

        $customer = User::factory()->customer()->create();
        $admin = User::factory()->admin()->create();

        Passport::actingAs($admin);

        $this->assertTrue(Gate::check('access-category-views'));

        Passport::actingAs($customer);

        $this->assertFalse(Gate::check('access-category-views'));
    }
}
