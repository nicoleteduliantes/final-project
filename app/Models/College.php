<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/* colleges table */
class College extends Model
{
    protected $table = 'colleges';
    protected $primaryKey = 'college_id';
    public $incrementing = true;

    protected $fillable = [
        'college_id',
        'college_name',
    ];

    public function degreePrograms(): HasMany
    {
        return $this->hasMany(DegreeProgram::class, 'college_id', 'college_id');
    }
}