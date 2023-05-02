<?php

namespace Tests\Unit\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_return_the_profile_edit_view(): void
    {
        $this->withoutVite();

        $customer = User::factory()->customer()->withCustomerProfile()->create();

        Passport::actingAs($customer);

        $response = $this->get(route('profile.edit'));

        $response
            ->assertSuccessful()
            ->assertViewIs('profile.edit');
    }
}
