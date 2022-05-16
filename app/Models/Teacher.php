<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Classroom;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        "middle_name",
        "last_name",
        "address",
        "birthday",
        "gender",
        "number",
        "email",
    ];


    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }
}
