<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Recipe extends Model
{
    protected $primaryKey = 'recipeID';
    protected $fillable = [
        'productID',
        'ingredientID',
        'qtyUsed'
    ];

    public function product():BelongsTo{
        return $this->belongsTo(
            Product::class, 'productID', 'productID'
        );
    }


    public function ingredient(): BelongsTo { 
        return $this->belongsTo(Ingredient::class, 'ingredientID', 'ingredientID');
    }
}
