<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = [
        'create_url',
        'edit_url',
    ];

    public function createUrl(): Attribute
    {
        return Attribute::get(fn() => route('students.create'));
    }

    public function editUrl(): Attribute
    {
        return Attribute::get(fn() => route('students.edit', $this->id));
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
}
