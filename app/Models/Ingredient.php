<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ingredient extends Model
{
    protected $primaryKey = 'ingredientID';

    protected $fillable = [
        'ingredientName',
        'ingredientType',
        'cost',
        'stockQty'
    ];

    public function recipes():HasMany {
        return $this->hasMany(
            Recipe::class, 'ingredientID', 'ingredientID'
        );
    }


}
