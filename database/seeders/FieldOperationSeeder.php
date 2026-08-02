<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FieldOperation;
use App\Models\Technician;
use App\Models\User;

class FieldOperationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        $userId = $user ? $user->id : null;

        // Fetch seeded technician models by name for easy lookup
        $ardy = Technician::where('name', 'Ardy')->first();
        $abi = Technician::where('name', 'Abi')->first();
        $budi = Technician::where('name', 'Budi')->first();
        $candra = Technician::where('name', 'Candra')->first();
        $dedi = Technician::where('name', 'Dedi')->first();
        $eko = Technician::where('name', 'Eko')->first();
        $fajar = Technician::where('name', 'Fajar')->first();
        $gilang = Technician::where('name', 'Gilang')->first();

        $operations = [
            [
                'operation_date' => '2026-08-01',
                'bensin_parkir_fee' => 175000.00,
                'entertain_fee' => 0.00,
                'bonus_fee' => 0.00,
                'description' => 'Pembersihan & flushing saluran pipa utama gedung (RS Abdi Waluyo).',
                'technicians' => [
                    ['technician_id' => $ardy ? $ardy->id : 1, 'wage_amount' => 300000.00],
                    ['technician_id' => $abi ? $abi->id : 5, 'wage_amount' => 200000.00],
                ]
            ],
            [
                'operation_date' => '2026-08-01',
                'bensin_parkir_fee' => 85000.00,
                'entertain_fee' => 350000.00,
                'bonus_fee' => 150000.00,
                'description' => 'Meeting akuisisi contract maintenance + pengerjaan lembur grease trap dapur.',
                'technicians' => [
                    ['technician_id' => $budi ? $budi->id : 2, 'wage_amount' => 350000.00],
                    ['technician_id' => $candra ? $candra->id : 6, 'wage_amount' => 180000.00],
                ]
            ],
            [
                'operation_date' => '2026-08-02',
                'bensin_parkir_fee' => 120000.00,
                'entertain_fee' => 0.00,
                'bonus_fee' => 200000.00,
                'description' => 'Pengerjaan 2 lokasi tambahan manhole dapur resto.',
                'technicians' => [
                    ['technician_id' => $dedi ? $dedi->id : 3, 'wage_amount' => 300000.00],
                    ['technician_id' => $eko ? $eko->id : 7, 'wage_amount' => 200000.00],
                ]
            ],
            [
                'operation_date' => '2026-08-02',
                'bensin_parkir_fee' => 250000.00,
                'entertain_fee' => 275000.00,
                'bonus_fee' => 300000.00,
                'description' => 'Shift malam lembur mampet parah saluran limbah utama.',
                'technicians' => [
                    ['technician_id' => $ardy ? $ardy->id : 1, 'wage_amount' => 400000.00],
                    ['technician_id' => $fajar ? $fajar->id : 4, 'wage_amount' => 250000.00],
                    ['technician_id' => $gilang ? $gilang->id : 8, 'wage_amount' => 180000.00],
                ]
            ],
            [
                'operation_date' => '2026-08-02',
                'bensin_parkir_fee' => 150000.00,
                'entertain_fee' => 150000.00,
                'bonus_fee' => 0.00,
                'description' => 'Inspection & preventive maintenance bulanan area basement hospital.',
                'technicians' => [
                    ['technician_id' => $budi ? $budi->id : 2, 'wage_amount' => 300000.00],
                ]
            ]
        ];

        foreach ($operations as $opData) {
            $techs = $opData['technicians'];
            unset($opData['technicians']);

            $operation = FieldOperation::create(array_merge($opData, [
                'user_id' => $userId,
            ]));

            foreach ($techs as $tech) {
                $operation->technicians()->create($tech);
            }
        }
    }
}
