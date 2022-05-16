<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        Student::factory(100)->create();
        Teacher::factory(100)->create();
        Subject::factory(100)->create();
        Classroom::factory(100)->create();

        // populating classroom - subject relationship
        $students = Student::all();
        // Populate the pivot table
        Classroom::all()->each(function ($classroom) use ($students) {
            $classroom->students()->attach(
                $students->random(30)->pluck('id')
            );
        });
    }
}
