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
        "password"
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }
}
