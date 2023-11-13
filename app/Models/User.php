<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'roles'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'userId');
    }

    public function examiners(): HasMany
    {
        return $this->hasMany(Examiner::class);
    }

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'user_faculty_departments', 'userId', 'departmentId');
    }
    public function faculties(): BelongsToMany
    {
        return $this->belongsToMany(Faculty::class, 'user_faculty_departments', 'userId', 'facultyId');
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'userId');
    }
}
