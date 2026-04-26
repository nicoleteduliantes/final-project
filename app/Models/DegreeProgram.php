<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DegreeProgram extends Model
{
    /**
     * The table associated with the model.
    */
    protected $table = 'degprogs';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'degprog_id';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = true;

    /**
     * 
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'degprog_id',
        'degprog_name',
        'college_id',
    ];

    /**
     * Get the college that the degree program belongs to.
     */
    public function college(): BelongsTo
    {
        // Foreign Key: college_id on degprogs table
        // Owner Key: college_id on colleges table
        return $this->belongsTo(College::class, 'college_id', 'college_id');
    }

    /**
     * Get the students for the degree program.
     */
    public function students(): HasMany
    {
        // Foreign Key: degprog_id on students table
        // Local Key: degprog_id on degprogs table
        return $this->hasMany(Student::class, 'degprog_id', 'degprog_id');
    }
}