<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $primarykey = 'productID';

    protected $fillable = [
        'categoryID',
        'productName',
        'productCalories',
        'productPrice'
    ];

    public function category():BelongsTo {
        return $this->belongsTo(
            Category::class, 'categoryID', 'categoryID'
        );
    }
}
