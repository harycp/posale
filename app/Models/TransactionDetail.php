<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $fillable = [
        'transaction_id',
        'product_id',
        'quantity',
        'price_at_transaction',
        'subtotal',
    ];
    
    // Relasi: Detail ini milik satu transaksi
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
    
    // Relasi: Detail ini merujuk ke satu produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
