<?php

namespace Tests\Feature;

use Domain\Country\Models\Country;
use Domain\State\Models\State;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListStatesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_user_can_get_all_states_name(): void
    {
        $country = Country::factory()->create();

        $states = State::factory(10)->create([
            'country_id' => $country->id
        ]);

        $response = $this->getJson(route('api.countries.states', $country->id));

        $response
            ->assertOk()
            ->assertJsonCount(10)
            ->assertJsonFragment([
                'id' => $states->first()->id,
                'name' => $states->first()->name,
            ]);
    }
}
