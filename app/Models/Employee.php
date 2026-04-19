<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function orders(): HasMany {
        return $this->hasMany(
            Order::class, 'employeeID', "employeeID"
        );
    }
}
