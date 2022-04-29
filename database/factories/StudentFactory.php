<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->name(),
            'middle_name' => $this->faker->lastName(),
            'last_name' => $this->faker->lastName(),
            'address' => $this->faker->address(),
            'birthday' => $this->faker->date(),
            'gender' => $this->faker->randomElement(["Male", "Female"]),
            'number' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
        ];
    }
}
