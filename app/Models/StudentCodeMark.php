<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentCodeMark extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function studentCode(): BelongsTo
    {
        return $this->belongsTo(StudentCode::class);
    }
}
