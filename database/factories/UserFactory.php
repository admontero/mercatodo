<?php

namespace Database\Factories;

use Domain\CustomerProfile\Models\CustomerProfile;
use Domain\User\Models\User;
use Domain\User\States\ActiveStatus;
use Domain\User\States\InactiveStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Syncs role/s of user with passed role/s.
     *
     * @param  array|Role|string  ...$roles
     */
    private function assignRole(...$roles): self
    {
        return $this->afterCreating(function (User $user) use ($roles) {
            return $user->syncRoles($roles);
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user is an admin.
     */
    public function admin(): self
    {
        return $this->assignRole('admin');
    }

    /**
     * Indicate that the user is a customer.
     */
    public function customer(): self
    {
        return $this->assignRole('customer');
    }

    /**
     * Indicate that the user is a activated.
     */
    public function activated(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => ActiveStatus::class,
            ];
        });
    }

    /**
     * Indicate that the user is a inactivated.
     */
    public function inactivated(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => InactiveStatus::class,
            ];
        });
    }

    /**
     * Indicate that the user is a inactivated.
     */
    public function withCustomerProfile(array $data = []): self
    {
        return $this->state(function (array $attributes) use ($data) {
            return [
                'profileable_type' => CustomerProfile::class,
                'profileable_id' => CustomerProfile::factory()->create($data),
            ];
        });
    }
}
