<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Semester extends Model
{
    use HasFactory;

    public function examiners(): HasMany
    {
        return $this->hasMany(Examiner::class);
    }

    public function studentCourses(): HasMany
    {
        return $this->hasMany(StudentCourse::class);
    }

    public function caMarks(): HasMany
    {
        return $this->hasMany(CA::class);
    }

    public function studentCodes(): HasMany
    {
        return $this->hasMany(StudentCode::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class, 'semesterId');
    }
    public function gpas(): HasMany
    {
        return $this->hasMany(Result::class, 'semesterId');
    }
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'semesterId');
    }
    public function courseSummaries(): HasMany
    {
        return $this->hasMany(CourseSummary::class, 'semesterId');
    }
}
