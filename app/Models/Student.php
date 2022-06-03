<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }

    public function attendanceSummary()
    {
        return $this->hasMany(AttendanceSummary::class);
    }
}
