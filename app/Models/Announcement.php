<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/* announcement table */
class Announcement extends Model
{
    protected $primaryKey = 'announcement_id';

    protected $fillable = [
    'title',
    'content',
    'audience',
    'date_posted',
    'org_id',
    'osa_id',
];

    public function organization() {
        return $this->belongsTo(Organization::class, 'org_id');
    }

    public function osa() {
        return $this->belongsTo(OSA::class, 'osa_id');
    }
}
