<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Osa extends Authenticatable
{
    protected $table = 'osa';  
    protected $primaryKey = 'osa_id'; 
    public $incrementing = false; 
    
    protected $fillable = ['osa_id', 'password'];
    protected $hidden = ['password'];
}
