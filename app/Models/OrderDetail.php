<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    protected $primaryKey = 'detailID';

    protected $fillable = [
        'orderID',
        'productID',
        'quantity',
        'unitPrice',
        'subTotal',
        'ingredientCost' 
    ];

    public function order():BelongsTo {
        return $this->belongsTo(
            Order::class, 'orderID', 'orderID'
            );
        
    }
}
