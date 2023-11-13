<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Permission\Traits\HasRoles;

class Course extends Model
{
    use HasFactory, HasRoles;
    protected $guarded = ['id'];

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class, 'facultyId');
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class, 'courseId');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_courses', 'courseId', 'studentId');
    }

    public function caMarks(): HasMany
    {
        return $this->hasMany(CA::class);
    }

    public function studentCodes(): HasMany
    {
        return $this->hasMany(StudentCode::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');
    }
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class, 'semesterId');
    }

    public function courseSummary(): HasOne
    {
        return $this->hasOne(CourseSummary::class, 'courseId');
    }
}