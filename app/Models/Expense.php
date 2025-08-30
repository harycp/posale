<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
        'cost_per_item',
        'total_cost',
        'description',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
