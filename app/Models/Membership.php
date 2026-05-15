<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/* memberships table */
class Membership extends Model
{
    protected $primaryKey = 'membership_id';
    public $incrementing = true; 
    protected $keyType = 'int';   

    protected $fillable = [
        'org_id',
        'student_id',
        'position',
        'expiration',
        'status',
        'assigned_committee',
    ];

    protected $casts = [
        'expiration' => 'date',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'org_id', 'org_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function applicationDetail(): HasOne
    {
        return $this->hasOne(ApplicationDetail::class, 'membership_id', 'membership_id');
    }
}