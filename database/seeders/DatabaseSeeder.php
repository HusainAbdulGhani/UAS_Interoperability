<?php
namespace Database\Seeders;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use App\Models\StockLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder {
    public function run() {
        User::create([
            'name' => 'Admin Gudang',
            'email' => 'admin@gudang.com',
            'password' => Hash::make('password'),
            'api_token' => Str::random(60),
        ]);
        $elektronik = Category::create(['name' => 'Elektronik']);
        $furniture = Category::create(['name' => 'Furniture']);
        $kategoriIds = [$elektronik->id, $furniture->id];

        $faker = Faker::create('id_ID');

        $namaElektronik = ['Laptop', 'Smartphone', 'Monitor', 'Keyboard Mechanical', 'Mouse Wireless', 'Headset Gaming', 'Printer', 'Kabel HDMI', 'Powerbank', 'Speaker'];
        $namaFurniture = ['Meja Kerja', 'Kursi Kantor', 'Lemari Pakaian', 'Rak Buku', 'Sofa Minimalis', 'Meja Makan', 'Tempat Tidur', 'Lampu Hias', 'Buffet TV', 'Kursi Lipat'];

        for ($i = 1; $i <= 100; $i++) {

            $isElektronik = $faker->boolean();
            $kategoriId = $isElektronik ? $elektronik->id : $furniture->id;

            $namaDasar = $isElektronik ? $faker->randomElement($namaElektronik) : $faker->randomElement($namaFurniture);
            $namaLengkap = $namaDasar . ' ' . $faker->company();

            $item = Item::create([
                'category_id' => $kategoriId,
                'item_code'   => 'BRG-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'name'        => $namaLengkap,
                'stock'       => $faker->numberBetween(1, 50),
                'location'    => 'Gudang ' . $faker->randomElement(['A', 'B', 'C']) . '-' . $faker->numberBetween(1, 10)
            ]);
            
            StockLog::create([
                'item_id'     => $item->id,
                'type'        => 'in',
                'amount'      => $item->stock,
                'description' => 'Stok barang ditambahkan'
            ]);
        }
    }
}