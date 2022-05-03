<?php

namespace Database\Factories;

use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Classroom>
 */
class ClassroomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        return [
            'name' => $this->faker->unique()->lastName(),
            'code' => $this->faker->unique()->numberBetween(1000, 9999),
            "teacher_id" => Teacher::all()->random()->id,
            "subject_id" => Subject::all()->random()->id
        ];
    }
}
