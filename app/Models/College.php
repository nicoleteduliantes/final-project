<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class College extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'colleges';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'college_id';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = true;

    protected $fillable = [
        'college_id',
        'college_name',
    ];

    /**
     * Get all degree programs offered by this college.
     */
    public function degreePrograms(): HasMany
    {
        // Foreign Key: college_id on the degprogs table
        // Local Key: college_id on this colleges table
        return $this->hasMany(DegreeProgram::class, 'college_id', 'college_id');
    }
}