<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\UserResource;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_user_resource_must_have_the_required_fields(): void
    {
        $this->seed(RoleSeeder::class);

        $user = User::factory()->create();

        $userResource = UserResource::make($user)->resolve();

        $this->assertEquals($user->id, $userResource['id']);
        $this->assertEquals($user->first_name, $userResource['first_name']);
        $this->assertEquals($user->last_name, $userResource['last_name']);
        $this->assertEquals($user->email, $userResource['email']);
        $this->assertEquals((string) $user->status, $userResource['status']);
        $this->assertEquals($user->created_at->diffForHumans(), $userResource['ago']);
    }
}
