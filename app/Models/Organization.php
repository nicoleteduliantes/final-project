<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

/* organizations table */
class Organization extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'organizations'; 
    protected $primaryKey = 'org_id'; 

    protected $fillable = [
        'org_name', 
        'category', 
        'description', 
        'expiration',
        'status',
        'application_status',
        'password'
    ];
    protected $hidden = ['password'];

    protected $attributes = [
        'application_status' => 'closed',
    ];
    
    public $incrementing = true;
   
    public function getAuthIdentifierName()
    {
        return 'org_id';
    }

    public function getUsernameAttribute()
    {
        return $this->org_id;
    }

    public function announcements() {
        return $this->hasMany(Announcement::class, 'org_id');
    }

    public function isAcceptingApplications(): bool {
        return $this->application_status === 'open' && $this->status === 'registered';
    }
}
