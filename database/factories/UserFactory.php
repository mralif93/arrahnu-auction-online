<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'username' => fake()->unique()->userName(),
            'full_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone_number' => fake()->optional()->phoneNumber(),
            'password_hash' => static::$password ??= Hash::make('password'),
            'is_email_verified' => fake()->boolean(),
            'is_phone_verified' => fake()->boolean(),
            'role' => fake()->randomElement([
                User::ROLE_MAKER,
                User::ROLE_CHECKER,
                User::ROLE_BIDDER
            ]),
            'status' => fake()->randomElement([
                User::STATUS_ACTIVE,
                User::STATUS_INACTIVE,
                User::STATUS_PENDING_APPROVAL
            ]),
            'is_admin' => false,
            'is_staff' => false,
            'avatar_path' => null,
            'preferences' => null,
            'last_login_at' => fake()->optional()->dateTimeBetween('-1 month', 'now'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => User::ROLE_MAKER, // Use maker role for admin users
            'is_admin' => true,
            'is_staff' => true,
            'status' => User::STATUS_ACTIVE,
        ]);
    }

    public function bidder(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => User::ROLE_BIDDER,
            'is_admin' => false,
            'is_staff' => false,
        ]);
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => User::STATUS_ACTIVE,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => User::STATUS_INACTIVE,
        ]);
    }

    public function pendingApproval(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => User::STATUS_PENDING_APPROVAL,
        ]);
    }



    public function emailVerified(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_email_verified' => true,
        ]);
    }

    public function emailUnverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_email_verified' => false,
        ]);
    }
}
