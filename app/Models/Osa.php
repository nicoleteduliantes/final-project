<?php

namespace App\Models;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Osa extends Authenticatable
{
    use HasApiTokens;
    protected $table = 'osa';  
    protected $primaryKey = 'osa_id'; 
    public $incrementing = false; 
    
    protected $fillable = ['osa_id', 'password'];
    protected $hidden = ['password'];

    public function getAuthIdentifierName()
    {
        return 'osa_id';
    }

    public function getUsernameAttribute()
    {
        return $this->osa_id;
    }
    
}

