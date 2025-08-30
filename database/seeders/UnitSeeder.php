<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Unit::create(['name' => 'Sak', 'short_name' => 'sak']);
        Unit::create(['name' => 'Pcs', 'short_name' => 'pcs']);
        Unit::create(['name' => 'Batang', 'short_name' => 'btg']);
        Unit::create(['name' => 'Meter Kubik', 'short_name' => 'm3']);
        Unit::create(['name' => 'Roll', 'short_name' => 'roll']);
    }
}
