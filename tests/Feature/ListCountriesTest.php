<?php

namespace Tests\Feature;

use Domain\Country\Models\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListCountriesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_user_can_get_all_countries_name(): void
    {
        $countries = Country::factory(20)->create();

        $response = $this->getJson(route('api.countries'));

        $response
            ->assertOk()
            ->assertJsonCount(21)
            ->assertJsonFragment([
                'id' => $countries->first()->id,
                'name' => $countries->first()->name,
            ]);
    }
}
