<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['mark_grade'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    protected function markGrade(): Attribute
    {
        return Attribute::make(
            get:function ($value, $attributes) {
                $obtainedMarks = $attributes['obtained_marks'];
                $markGrade = MarkGrading::where('lower_mark_limit', '<=', $obtainedMarks)
                    ->where('upper_mark_limit', '>=', $obtainedMarks)
                    ->first();
                return $markGrade;
            }
        );
    }
}
