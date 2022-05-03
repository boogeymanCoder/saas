<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradingSystemCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "percentage",
        "grading_system_id"
    ];

    public function grading_system()
    {
        return $this->belongsTo(GradingSystemCategory::class);
    }
}
