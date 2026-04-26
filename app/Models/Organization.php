<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Organization extends Authenticatable
{
    use HasApiTokens;

    protected $primaryKey = 'org_id'; 
    protected $fillable = [
        'org_name', 
        'category', 
        'description', 
        'password'
    ];
    protected $hidden = ['password'];
    public $incrementing = true;
    /**
     * Tells Laravel to use 'org_id' for authentication instead of 'email'
     */
    public function getAuthIdentifierName()
    {
        return 'org_id';
    }

    public function getUsernameAttribute()
    {
        return $this->org_id;
    }
}
