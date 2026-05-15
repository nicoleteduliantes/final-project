<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/* events table */
class Event extends Model
{
    protected $primaryKey = 'event_id';

    protected $fillable = [
        'org_id',
        'event_name',
        'event_host',
        'date',
        'location',
        'description',
        'event_logo'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }
}