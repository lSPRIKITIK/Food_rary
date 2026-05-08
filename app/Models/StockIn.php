<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
{
    use HasFactory;

    protected $primaryKey = 'stockID';

    protected $fillable = [
        'ingredientID',
        'supplierID',
        'quantity',
        'unitCost',
        'deliveryDate'
    ];
}