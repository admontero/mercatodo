<?php

namespace Tests\Unit\Models;

use Domain\Country\Models\Country;
use Domain\CustomerProfile\Models\CustomerProfile;
use Domain\State\Models\State;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CountryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_country_has_many_customer_profiles(): void
    {
        $country = Country::factory()->create();

        CustomerProfile::factory()->create([
            'country_id' => $country->id,
        ]);

        $this->assertInstanceOf(CustomerProfile::class, $country->customer_profiles()->first());
    }

    /**
     * @test
     */
    public function a_country_has_many_states(): void
    {
        $country = Country::factory()->create();

        State::factory()->create([
            'country_id' => $country->id,
        ]);

        $this->assertInstanceOf(State::class, $country->states()->first());
    }
}
