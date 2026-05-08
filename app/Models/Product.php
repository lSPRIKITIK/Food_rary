<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $primaryKey = 'productID';

    protected $fillable = [
        'categoryID',
        'productName',
        'productCalories',
        'productPrice',
        'productImage'
    ];

    public function category():BelongsTo {
        return $this->belongsTo(
            Category::class, 'categoryID', 'categoryID'
        );
    }
    public function recipes():HasMany {
        return $this->hasMany(Recipe::class, 'productID', 'productID');
    }

    public function ingredients(): \Illuminate\Database\Eloquent\Relations\BelongsToMany {
        return $this->belongsToMany(Ingredient::class, 'recipes', 'productID', 'ingredientID')
                    ->withPivot('qtyUsed');
    }
}
