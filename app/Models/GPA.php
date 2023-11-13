<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GPA extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'studentId');
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class, 'semesterId');
    }

}
