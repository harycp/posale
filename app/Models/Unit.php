<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'units';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'short_name',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
