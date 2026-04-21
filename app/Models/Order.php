<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $primaryKey = 'orderID';

    protected $fillable = [
        'employeeID',
        'orderDate'
    ];

    public function employee(): BelongsTo {
        return $this->belongsTo(
            Employee::class, 'employeeID', 'employeeID'
        );
    }

    public function orderDetails(): HasMany {
        return $this->hasMany(
            OrderDetail::class, 'orderID', 'orderID'
        );
    }

}
