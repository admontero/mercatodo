<?php

namespace Tests\Unit\Models;

use Domain\City\Models\City;
use Domain\Country\Models\Country;
use Domain\State\Models\State;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_state_belongs_to_country(): void
    {
        $country = Country::factory()->create();

        $state = State::factory()->create([
            'country_id' => $country->id,
        ]);

        $this->assertInstanceOf(Country::class, $state->country);
    }

    /**
     * @test
     */
    public function a_state_has_many_cities(): void
    {
        $state = State::factory()->create();

        City::factory()->create([
            'state_id' => $state->id,
        ]);

        $this->assertInstanceOf(City::class, $state->cities()->first());
    }
}
