<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'product_code',
        'name',
        'image',
        'unit_id',
        'purchase_price',
        'selling_price',
        'stock',
    ];

    // Relasi: Satu produk memiliki satu satuan
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    
    // Relasi: Satu produk bisa ada di banyak detail transaksi
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
    
    // Relasi: Satu produk bisa memiliki banyak catatan pengeluaran/stok masuk
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
