<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    // Tell Laravel the name of your custom primary key
    protected $primaryKey = 'supplierID';

    // Allow these columns to be mass-assigned
    protected $fillable = [
        'supplierName',
        'supplierContact',
        'supplierStreet',
        'supplierCity'
    ];
}