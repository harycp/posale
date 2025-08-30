<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run(): void
    {
        Product::create([
            'product_code' => 'SMN-001',
            'name' => 'Semen Tiga Roda 40kg',
            'unit_id' => 1, // Sak
            'purchase_price' => 55000,
            'selling_price' => 62000,
            'stock' => 100,
        ]);

        Product::create([
            'product_code' => 'BSI-001',
            'name' => 'Besi Beton 8mm',
            'unit_id' => 3, // Batang
            'purchase_price' => 35000,
            'selling_price' => 42000,
            'stock' => 250,
        ]);
        
        Product::create([
            'product_code' => 'BTA-001',
            'name' => 'Bata Merah Press',
            'unit_id' => 2, // Pcs
            'purchase_price' => 800,
            'selling_price' => 1100,
            'stock' => 5000,
        ]);
        
        // Produk yang hampir habis untuk testing fitur
         Product::create([
            'product_code' => 'CAT-001',
            'name' => 'Cat Tembok Putih 5kg',
            'unit_id' => 2, // Pcs
            'purchase_price' => 75000,
            'selling_price' => 90000,
            'stock' => 15, // Stok sedikit
        ]);
    }
}
