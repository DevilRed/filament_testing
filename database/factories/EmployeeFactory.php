<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->name(),
            'last_name' => $this->faker->lastName() ,
            'email' => $this->faker->email(),
            'phone' => $this->faker->phoneNumber(),
            'position' => $this->faker->randomElement(['PHP', 'Javascript']),
            'salary' =>$this->faker->numberBetween(1000, 9999),
        ];
    }
}
