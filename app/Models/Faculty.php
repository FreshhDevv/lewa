<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Faculty extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'facultyId');
    }

    public function users(): HasMany
    {
        return $this->hasMany(UserFacultyDepartment::class);
    }
}
