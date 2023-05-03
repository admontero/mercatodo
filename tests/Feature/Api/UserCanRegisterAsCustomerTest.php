<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserCanRegisterAsCustomerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function users_can_register_from_api_route(): void
    {
        $userData = $this->getUserValidData();

        $response = $this->postJson(route('api.register'), $userData);

        $response->assertSuccessful()
            ->assertStatus(200)
            ->assertJsonStructure(['token']);

        $user = User::first();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'aaron@test.com',
        ]);

        $this->assertDatabaseHas('customer_profiles', [
            'first_name' => 'Aaron',
            'last_name' => 'Aranda',
        ]);

        $this->assertTrue(
            Hash::check(
                'a87654321A',
                User::first()->password
            ),
            'The password must be encrypt'
        );

        $this->assertTrue($user->hasRole('customer'));
    }

    /**
     * @test
     */
    public function user_first_name_is_required_from_api_route(): void
    {
        $userData = $this->getUserValidData([
            'first_name' => '',
        ]);

        $response = $this->post(route('register'), $userData);

        $response->assertSessionHasErrors('first_name');

        $this->assertDatabaseMissing('users', [
            'first_name' => '',
            'last_name' => 'Arango',
            'email' => 'arara@test.com',
        ]);
    }

    /**
     * @test
     */
    public function user_first_name_must_be_a_string_from_api_route(): void
    {
        $userData = $this->getUserValidData([
            'first_name' => 3000,
        ]);

        $response = $this->post(route('register'), $userData);

        $response->assertSessionHasErrors('first_name');

        $this->assertDatabaseMissing('users', [
            'first_name' => 3000,
            'last_name' => 'Arango',
            'email' => 'arara@test.com',
        ]);
    }

    /**
     * @test
     */
    public function user_first_name_length_must_be_less_than_61_characters_from_api_route(): void
    {
        $first_name = $this->getRandomStringOnlyLetters(61);

        $userData = $this->getUserValidData([
            'first_name' => $first_name,
        ]);

        $response = $this->post(route('register'), $userData);

        $response->assertSessionHasErrors('first_name');

        $this->assertDatabaseMissing('users', [
            'first_name' => $first_name,
            'last_name' => 'Arango',
            'email' => 'arara@test.com',
        ]);
    }

    /**
     * @test
     */
    public function user_last_name_is_required_from_api_route(): void
    {
        $userData = $this->getUserValidData([
            'last_name' => '',
        ]);

        $response = $this->post(route('register'), $userData);

        $response->assertSessionHasErrors('last_name');

        $this->assertDatabaseMissing('users', [
            'first_name' => 'Arantxa',
            'last_name' => '',
            'email' => 'arara@test.com',
        ]);
    }

    /**
     * @test
     */
    public function user_last_name_must_be_a_string_from_api_route(): void
    {
        $userData = $this->getUserValidData([
            'last_name' => 500,
        ]);

        $response = $this->post(route('register'), $userData);

        $response->assertSessionHasErrors('last_name');

        $this->assertDatabaseMissing('users', [
            'first_name' => 'Arantxa',
            'last_name' => 500,
            'email' => 'arara@test.com',
        ]);
    }

    /**
     * @test
     */
    public function user_last_name_length_must_be_less_than_81_characters_from_api_route(): void
    {
        $last_name = $this->getRandomStringOnlyLetters(81);

        $userData = $this->getUserValidData([
            'last_name' => $last_name,
        ]);

        $response = $this->post(route('register'), $userData);

        $response->assertSessionHasErrors('last_name');

        $this->assertDatabaseMissing('users', [
            'first_name' => 'Arantxa',
            'last_name' => $last_name,
            'email' => 'arara@test.com',
        ]);
    }

    /**
     * @test
     */
    public function user_email_is_required_from_api_route(): void
    {
        $userData = $this->getUserValidData([
            'email' => '',
        ]);

        $response = $this->post(route('register'), $userData);

        $response->assertSessionHasErrors('email');

        $this->assertDatabaseMissing('users', [
            'first_name' => 'Arantxa',
            'last_name' => 'Arango',
            'email' => '',
        ]);
    }

    /**
     * @test
     */
    public function user_email_must_be_a_string_from_api_route(): void
    {
        $userData = $this->getUserValidData([
            'email' => 2534389,
        ]);

        $response = $this->post(route('register'), $userData);

        $response->assertSessionHasErrors('email');

        $this->assertDatabaseMissing('users', [
            'first_name' => 'Arantxa',
            'last_name' => 'Arango',
            'email' => 2534389,
        ]);
    }

    /**
     * @test
     */
    public function user_email_must_be_a_valid_email_format_from_api_route(): void
    {
        $userData = $this->getUserValidData([
            'email' => 'asdcjka',
        ]);

        $response = $this->post(route('register'), $userData);

        $response->assertSessionHasErrors('email');

        $this->assertDatabaseMissing('users', [
            'first_name' => 'Arantxa',
            'last_name' => 'Arango',
            'email' => 'asdcjka',
        ]);
    }

    /**
     * @test
     */
    public function user_email_length_must_be_less_than_101_characters_from_api_route(): void
    {
        $email = $this->getRandomStringOnlyLetters(92).'@test.com';

        $userData = $this->getUserValidData([
            'email' => $email,
        ]);

        $response = $this->post(route('register'), $userData);

        $response->assertSessionHasErrors('email');

        $this->assertDatabaseMissing('users', [
            'first_name' => 'Arantxa',
            'last_name' => 'Arango',
            'email' => $email,
        ]);
    }

    /**
     * @test
     */
    public function user_email_must_be_unique_from_api_route(): void
    {
        User::factory()->create([
            'email' => 'aaron@test.com',
        ]);

        $userData = $this->getUserValidData();

        $response = $this->post(route('register'), $userData);

        $response->assertSessionHasErrors('email');

        $this->assertDatabaseCount('users', 1);
    }

    /**
     * @test
     */
    public function user_password_is_required_from_api_route(): void
    {
        $userData = $this->getUserValidData([
            'password' => '',
        ]);

        $response = $this->post(route('register'), $userData);

        $response->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', [
            'first_name' => 'Aaron',
            'last_name' => 'Aranda',
            'email' => 'aaron@test.com',
        ]);
    }

    /**
     * @test
     */
    public function user_password_must_be_a_string_from_api_route(): void
    {
        $userData = $this->getUserValidData([
            'password' => 1200,
        ]);

        $response = $this->post(route('register'), $userData);

        $response->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', [
            'first_name' => 'Aaron',
            'last_name' => 'Aranda',
            'email' => 'aaron@test.com',
        ]);
    }

    /**
     * @test
     */
    public function user_password_must_be_contains_at_least_8_characters_from_api_route(): void
    {
        $userData = $this->getUserValidData([
            'password' => 'A18a30',
        ]);

        $response = $this->post(route('register'), $userData);

        $response->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', [
            'first_name' => 'Aaron',
            'last_name' => 'Aranda',
            'email' => 'aaron@test.com',
        ]);
    }

    /**
     * @test
     */
    public function user_password_must_be_contains_at_least_1_lowercase_letter_from_api_route(): void
    {
        $userData = $this->getUserValidData([
            'password' => 'A1830BEA',
        ]);

        $response = $this->post(route('register'), $userData);

        $response->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', [
            'first_name' => 'Aaron',
            'last_name' => 'Aranda',
            'email' => 'aaron@test.com',
        ]);
    }

    /**
     * @test
     */
    public function user_password_must_be_contains_at_least_1_uppercase_letter_from_api_route(): void
    {
        $userData = $this->getUserValidData([
            'password' => 'a1830bea',
        ]);

        $response = $this->post(route('register'), $userData);

        $response->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', [
            'first_name' => 'Aaron',
            'last_name' => 'Aranda',
            'email' => 'aaron@test.com',
        ]);
    }

    /**
     * @test
     */
    public function user_password_must_be_contains_at_least_1_number_from_api_route(): void
    {
        $userData = $this->getUserValidData([
            'password' => 'PasswordStrong',
        ]);

        $response = $this->post(route('register'), $userData);

        $response->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', [
            'first_name' => 'Aaron',
            'last_name' => 'Aranda',
            'email' => 'aaron@test.com',
        ]);
    }

    /**
     * @test
     */
    public function user_password_must_be_confirmed_from_api_route(): void
    {
        $userData = $this->getUserValidData([
            'password_confirmation' => 'A123456a',
        ]);

        $response = $this->post(route('register'), $userData);

        $response->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', [
            'first_name' => 'Aaron',
            'last_name' => 'Aranda',
            'email' => 'aaron@test.com',
        ]);
    }

    public function getUserValidData($invalidData = []): array
    {
        $validData = [
            'first_name' => 'Aaron',
            'last_name' => 'Aranda',
            'email' => 'aaron@test.com',
            'password' => 'a87654321A',
            'password_confirmation' => 'a87654321A',
        ];

        return array_merge($validData, $invalidData);
    }
}
