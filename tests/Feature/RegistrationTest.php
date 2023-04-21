<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function users_can_register(): void
    {
        $userData = $this->getUserValidData();

        $response = $this->post(route('register'), $userData);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('users', [
            'email' => 'arara@test.com',
        ]);

        $this->assertDatabaseHas('customers', [
            'first_name' => 'Arantxa',
            'last_name' => 'Arango',
        ]);

        $this->assertTrue(
            Hash::check(
                'A12345678a',
                User::first()->password
            ),
            'The password must be encrypt'
        );
    }

    /**
     * @test
     */
    public function user_first_name_is_required(): void
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
    public function user_first_name_must_be_a_string(): void
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
    public function user_first_name_length_must_be_less_than_61_characters(): void
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
    public function user_last_name_is_required(): void
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
    public function user_last_name_must_be_a_string(): void
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
    public function user_last_name_length_must_be_less_than_81_characters(): void
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
    public function user_email_is_required(): void
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
    public function user_email_must_be_a_string(): void
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
    public function user_email_must_be_a_valid_email_format(): void
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
    public function user_email_length_must_be_less_than_101_characters(): void
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
    public function user_email_must_be_unique(): void
    {
        User::factory()->create([
            'email' => 'arara@test.com',
        ]);

        $userData = $this->getUserValidData();

        $response = $this->post(route('register'), $userData);

        $response->assertSessionHasErrors('email');

        $this->assertDatabaseCount('users', 1);
    }

    /**
     * @test
     */
    public function user_password_is_required(): void
    {
        $userData = $this->getUserValidData([
            'password' => '',
        ]);

        $response = $this->post(route('register'), $userData);

        $response->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', [
            'first_name' => 'Arantxa',
            'last_name' => 'Arango',
            'email' => 'arara@test.com',
        ]);
    }

    /**
     * @test
     */
    public function user_password_must_be_a_string(): void
    {
        $userData = $this->getUserValidData([
            'password' => 1200,
        ]);

        $response = $this->post(route('register'), $userData);

        $response->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', [
            'first_name' => 'Arantxa',
            'last_name' => 'Arango',
            'email' => 'arara@test.com',
        ]);
    }

    /**
     * @test
     */
    public function user_password_must_be_contains_at_least_8_characters(): void
    {
        $userData = $this->getUserValidData([
            'password' => 'A18a30',
        ]);

        $response = $this->post(route('register'), $userData);

        $response->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', [
            'first_name' => 'Arantxa',
            'last_name' => 'Arango',
            'email' => 'arara@test.com',
        ]);
    }

    /**
     * @test
     */
    public function user_password_must_be_contains_at_least_1_lowercase_letter(): void
    {
        $userData = $this->getUserValidData([
            'password' => 'A1830BEA',
        ]);

        $response = $this->post(route('register'), $userData);

        $response->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', [
            'first_name' => 'Arantxa',
            'last_name' => 'Arango',
            'email' => 'arara@test.com',
        ]);
    }

    /**
     * @test
     */
    public function user_password_must_be_contains_at_least_1_uppercase_letter(): void
    {
        $userData = $this->getUserValidData([
            'password' => 'a1830bea',
        ]);

        $response = $this->post(route('register'), $userData);

        $response->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', [
            'first_name' => 'Arantxa',
            'last_name' => 'Arango',
            'email' => 'arara@test.com',
        ]);
    }

    /**
     * @test
     */
    public function user_password_must_be_contains_at_least_1_number(): void
    {
        $userData = $this->getUserValidData([
            'password' => 'PasswordStrong',
        ]);

        $response = $this->post(route('register'), $userData);

        $response->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', [
            'first_name' => 'Arantxa',
            'last_name' => 'Arango',
            'email' => 'arara@test.com',
        ]);
    }

    /**
     * @test
     */
    public function user_password_must_be_confirmed(): void
    {
        $userData = $this->getUserValidData([
            'password_confirmation' => 'A123456a',
        ]);

        $response = $this->post(route('register'), $userData);

        $response->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', [
            'first_name' => 'Arantxa',
            'last_name' => 'Arango',
            'email' => 'arara@test.com',
        ]);
    }

    public function getUserValidData($invalidData = []): array
    {
        $validData = [
            'first_name' => 'Arantxa',
            'last_name' => 'Arango',
            'email' => 'arara@test.com',
            'password' => 'A12345678a',
            'password_confirmation' => 'A12345678a',
        ];

        return array_merge($validData, $invalidData);
    }

    public function getRandomStringOnlyLetters($n = 1)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }
}
