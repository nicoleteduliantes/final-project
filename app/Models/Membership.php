<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Membership extends Model
{
    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'membership_id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'org_id',
        'student_id',
        'position',
        'expiration',
        'status',
        'assigned_committee',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'expiration' => 'date',
    ];

    /**
     * Get the organization that owns the membership.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'org_id', 'org_id');
    }

    /**
     * Get the student associated with the membership.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    /**
     * Get the application details associated with the membership.
     */
    public function applicationDetail(): HasOne
    {
        return $this->hasOne(ApplicationDetail::class, 'membership_id', 'membership_id');
    }
}