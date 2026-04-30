<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    // Our custom primary key
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

    // Relationship: An event belongs to an Organization
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }
}