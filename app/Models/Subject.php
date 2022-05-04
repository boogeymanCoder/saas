<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Classroom;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "code",
    ];

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }
}
