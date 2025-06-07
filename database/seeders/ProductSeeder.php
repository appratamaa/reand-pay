<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::create(['name' => 'Kaos Polos', 'price' => 50000]);
        Product::create(['name' => 'Topi Trucker', 'price' => 35000]);
        Product::create(['name' => 'Jaket Hoodie', 'price' => 150000]);
    }
}
