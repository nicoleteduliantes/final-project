<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    // The primary key associated with the table
    protected $primaryKey = 'student_id';

    // Indicates if the IDs are auto-incrementing
    public $incrementing = false;

    // The "type" of the primary key ID
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id',
        'first_name',
        'last_name',
        'up_email',
        'admission_date',
        'course_id',
    ];

    /**
     * Get the course that the student belongs to.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }
}