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
    
    public function getActiveCostAttribute()
    {
        $activeBatch = \App\Models\StockIn::where('ingredientID', $this->ingredientID)
            ->where('remainingQty', '>', 0)
            ->orderBy('deliveryDate', 'asc')
            ->first();

        return $activeBatch ? $activeBatch->unitCost : $this->cost;
    }


}
