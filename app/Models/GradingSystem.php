<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GradingSystemCategory;

class GradingSystem extends Model
{
    use HasFactory;

    protected $fillable = ["name"];

    public function grading_system_categories()
    {
        return $this->hasMany(GradingSystemCategory::class);
    }
}
