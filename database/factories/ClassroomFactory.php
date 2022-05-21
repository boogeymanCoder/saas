<?php

namespace Database\Factories;

use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $name = $this->faker->lastName();
        $digit = $this->faker->unique()->randomNumber(4);

        return [
            'name' => $name . "-" . $digit,
            'code' => Str::random(10),
            "teacher_id" => Teacher::all()->random()->id,
            "subject_id" => Subject::all()->random()->id
        ];
    }
}
