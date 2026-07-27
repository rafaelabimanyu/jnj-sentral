<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Klien 1 (B2B)
        Client::create([
            'name' => 'RS Abdi Waluyo',
            'type' => 'b2b',
            'contact_info' => '021-3144921',
            'address' => 'Jl. Hos. Cokroaminoto No.31-33, Menteng, Jakarta Pusat',
        ]);

        // Klien 2 (B2B)
        Client::create([
            'name' => 'Sushi Tei',
            'type' => 'b2b',
            'contact_info' => '021-57973000',
            'address' => 'Plaza Indonesia Lt. 4, Jl. M.H. Thamrin No.28-30, Jakarta Pusat',
        ]);

        // Klien 3 (Residensial)
        Client::create([
            'name' => 'Bapak Yunedi',
            'type' => 'residential',
            'contact_info' => '0812-3456-7890',
            'address' => 'Perumahan Green Garden Blok A3 No. 12, Jakarta Barat',
        ]);
    }
}
