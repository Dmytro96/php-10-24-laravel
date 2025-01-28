<?php

namespace Database\Factories;

use App\Enums\RoleEnum;
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
            'name'              => fake()->name(),
            'lastname'          => fake()->lastName(),
            'email'             => fake()->unique()->safeEmail(),
            'phone'             => fake()->unique()->e164PhoneNumber(),
            'birthdate'         => fake()->unique()
                ->dateTimeBetween('-50 years', '-18 years')
                ->format('Y-m-d'),
            'email_verified_at' => now(),
            'password'          => static::$password ??= Hash::make('password'),
            'remember_token'    => Str::random(10),
        ];
    }
    
    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
    
    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            if (!$user->hasAnyRole(RoleEnum::values())) {
                $user->assignRole(RoleEnum::CUSTOMER->value);
            }
        });
    }
    
    public function admin(): static
    {
        return $this->state(fn ($attributes) => [
            'email' => 'admin@admin.com',
        ])->afterCreating(function (User $user) {
            $user->syncRoles(RoleEnum::ADMIN->value);
        });
    }
    
    public function moderator(): static
    {
        return $this->afterCreating(function (User $user) {
            $user->syncRoles(RoleEnum::MODERATOR->value);
        });
    }
    
    public function withEmail(string $email): static
    {
        return $this->state(fn (string $email) => [
            'email' => $email,
        ]);
    }
}
