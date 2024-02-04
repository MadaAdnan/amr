<?php

namespace App\Models;

use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole
{
    protected $guarded = [];    
    public function users()
    {
        return $this->hasMany(User::class, 'id', 'role_id');
    }

}