<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    protected $primaryKey = 'student_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'student_id',
        'last_name',
        'first_name',
        'up_email',
        'admission_date',
        'degprog_id', 
        'password',
    ];

    protected $hidden = ['password'];

    /**
     * Relationship to the DegreeProgram model
     */
    public function degreeProgram(): BelongsTo
    {
        // Foreign Key: degprog_id (on students table)
        // Owner Key: degprog_id (on degree_programs table)
        return $this->belongsTo(DegreeProgram::class, 'degprog_id', 'degprog_id');
    }
}