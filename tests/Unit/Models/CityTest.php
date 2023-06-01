<?php

namespace Tests\Unit\Models;

use Domain\City\Models\City;
use Domain\State\Models\State;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_city_belongs_to_state(): void
    {
        $state = State::factory()->create();

        $city = City::factory()->create([
            'state_id' => $state->id,
        ]);

        $this->assertInstanceOf(State::class, $city->state);
    }
}
