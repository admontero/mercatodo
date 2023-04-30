<?php

namespace Tests\Unit\Models;

use App\Models\CustomerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_customer_profile_belongs_to_user(): void
    {
        $profile = CustomerProfile::factory()->create();

        User::factory()->create([
            'profileable_type' => CustomerProfile::class,
            'profileable_id' => $profile->id,
        ]);

        $this->assertInstanceOf(User::class, $profile->user);
    }
}
