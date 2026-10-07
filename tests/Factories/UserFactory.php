<?php

namespace AlexRoden\LibraryApiPhp\Tests\Factories;

use AlexRoden\LibraryApiPhp\Models\User;

class UserFactory extends Factory
{
    public string $plainPassword = 'password';

    protected function model(): User
    {
        return new User();
    }

    protected function definition(): array
    {
        return [
            'email' => $this->faker->email(),
            'password' => $this->plainPassword,
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
        ];
    }
}
