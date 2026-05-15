<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/* students table */
class Student extends Authenticatable
{
    use HasApiTokens;
    protected $table = 'students';
    protected $primaryKey = 'student_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'student_id',
        'last_name',
        'first_name',
        'up_email',
        'degprog_id', 
        'password',
    ];

    protected $hidden = ['password'];

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function degreeProgram(): BelongsTo
    {
        return $this->belongsTo(DegreeProgram::class, 'degprog_id', 'degprog_id');
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class, 'student_id');
    }
}