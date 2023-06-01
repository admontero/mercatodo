<?php

namespace Tests\Unit\Models;

use Domain\City\Models\City;
use Domain\Country\Models\Country;
use Domain\CustomerProfile\Models\CustomerProfile;
use Domain\State\Models\State;
use Domain\User\Models\User;
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

    /**
     * @test
     */
    public function a_customer_profile_belongs_to_country(): void
    {
        $customer_profile = CustomerProfile::factory()->create();

        $this->assertInstanceOf(Country::class, $customer_profile->country);
    }

    /**
     * @test
     */
    public function a_customer_profile_belongs_to_state(): void
    {
        $customer_profile = CustomerProfile::factory()->create();

        $this->assertInstanceOf(State::class, $customer_profile->state);
    }

    /**
     * @test
     */
    public function a_customer_profile_belongs_to_city(): void
    {
        $customer_profile = CustomerProfile::factory()->create();

        $this->assertInstanceOf(City::class, $customer_profile->city);
    }
}
