<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/* application_details table */
class ApplicationDetail extends Model
{   
    protected $table = 'application_details';
    protected $primaryKey = 'application_id';

    protected $fillable = [
        'membership_id',
        'cover_letter',
        'skills',
        'previous_experience',
        'applied_committee',
        'date_applied',
    ];

    protected $casts = [
        'date_applied' => 'date',
    ];

    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class, 'membership_id', 'membership_id');
    }
}