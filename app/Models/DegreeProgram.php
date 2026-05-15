<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/* degprogs table */
class DegreeProgram extends Model
{
    protected $table = 'degprogs';
    protected $primaryKey = 'degprog_id';
    public $incrementing = true;

    protected $fillable = [
        'degprog_id',
        'degprog_name',
        'college_id',
    ];

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class, 'college_id', 'college_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'degprog_id', 'degprog_id');
    }
}