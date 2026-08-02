<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Marketer;

class MarketerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $marketers = [
            'JAWA JABODETABEK',
            'JAYA-BOGOR',
            'JAYA-JABODETABEK',
            'JAYA-SOSMED',
            'MARKETING-ARIES',
            'MARKETING-ATRA',
            'MARKETING-LOLI',
            'MARKETING-PKL',
            'MARKETING-RIDWAN',
            'NO PRIBADI',
        ];

        foreach ($marketers as $name) {
            Marketer::updateOrCreate(
                ['name' => $name],
                ['default_fee_percentage' => 10.00]
            );
        }
    }
}
