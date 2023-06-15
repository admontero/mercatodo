<?php

namespace Tests\Feature;

use Domain\City\Models\City;
use Domain\State\Models\State;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListCitiesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_user_can_get_all_cities_name(): void
    {
        $state = State::factory()->create();

        $cities = City::factory(35)->create([
            'state_id' => $state->id,
        ]);

        $response = $this->getJson(route('api.states.cities', $state->id));

        $response
            ->assertOk()
            ->assertJsonCount(35)
            ->assertJsonFragment([
                'id' => $cities->first()->id,
                'name' => $cities->first()->name,
            ]);
    }
}
