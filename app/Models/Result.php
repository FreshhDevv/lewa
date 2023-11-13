<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'courseId');
    }
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'studentId');
    }
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class, 'semesterId');
    }
}
