<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

class Student extends Model
{
    use HasFactory, HasRoles;
    protected $guarded = ['id'];
    protected $guard_name = 'api';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function studentCourse(): HasMany
    {
        return $this->hasMany(StudentCourse::class);
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'student_courses', 'studentId', 'courseId');
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
        return $this->hasMany(Result::class, 'studentId');
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(UserFacultyDepartment::class, 'userId');
    }

    public function gpas() : HasMany
    {
        return $this->hasMany(GPA::class, 'studentId');
    }
}
