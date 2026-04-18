<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class Employee extends Authenticatable
{
    
    protected $primaryKey = 'employeeID';

    protected $fillable = [
        'username',
        'firstName',
        'middleName',
        'lastName',
        'position',
        'password',
        
    ];
    protected $hidden = [
        'password'
    ];

    public function setPasswordAttribute($value) {
        $this->attributes['password'] = Hash::make($value);
    }
}
