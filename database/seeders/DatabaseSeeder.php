<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat User Admin untuk Login API
        User::factory()->create([
            'name' => 'Admin Gudang',
            'email' => 'admin@gudang.com',
            'password' => Hash::make('password'),
        ]);

        // 2.Data Kategori Dummy
        $elektronik = Category::create(['name' => 'Elektronik']);
        $furniture = Category::create(['name' => 'Furniture']);

        // 3.Data Barang Dummy
        Item::create([
            'category_id' => $elektronik->id,
            'item_code' => 'BRG-001',
            'name' => 'Laptop ASUS ROG',
            'stock' => 15,
            'location' => 'Gudang A-1'
        ]);

        Item::create([
            'category_id' => $furniture->id,
            'item_code' => 'BRG-002',
            'name' => 'Kursi Kantor Ergotec',
            'stock' => 20,
            'location' => 'Gudang B-3'
        ]);
    }
}