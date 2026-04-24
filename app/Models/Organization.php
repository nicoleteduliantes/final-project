<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Organization extends Authenticatable
{
    protected $primaryKey = 'org_id';
    
    protected $fillable = [
        'org_name', 
        'category', 
        'description', 
        'password'
    ];
    protected $hidden = ['password'];
}
