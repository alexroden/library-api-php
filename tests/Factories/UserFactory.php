<?php

namespace Tests\Factories;

use App\Models\User;

class UserFactory extends Factory
{
    protected function model(): User
    {
        return new User();
    }

    protected function definition(): array
    {
        return [
            'email' => $this->faker->email(),
            'password' => password_hash('password', PASSWORD_ARGON2ID),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
        ];
    }
}