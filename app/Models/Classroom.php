<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Teacher;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "code",
        "teacher_id",
        "subject_id"
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
