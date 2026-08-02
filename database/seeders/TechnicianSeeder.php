<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Technician;

class TechnicianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $technicians = [
            // Senior Leads
            ['name' => 'Ardy', 'level' => 'Senior'],
            ['name' => 'Budi', 'level' => 'Senior'],
            ['name' => 'Dedi', 'level' => 'Senior'],
            ['name' => 'Fajar', 'level' => 'Senior'],

            // Junior Helpers
            ['name' => 'Abi', 'level' => 'Junior'],
            ['name' => 'Candra', 'level' => 'Junior'],
            ['name' => 'Eko', 'level' => 'Junior'],
            ['name' => 'Gilang', 'level' => 'Junior'],
        ];

        foreach ($technicians as $tech) {
            Technician::updateOrCreate(
                ['name' => $tech['name']],
                ['level' => $tech['level']]
            );
        }
    }
}
