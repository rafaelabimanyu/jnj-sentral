<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FieldOperation;
use App\Models\Employee;
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

        // Fetch seeded employee models by name for easy lookup
        $ardy = Employee::where('name', 'Ardy')->first();
        $abi = Employee::where('name', 'Abi')->first();
        $budi = Employee::where('name', 'Budi')->first();
        $candra = Employee::where('name', 'Candra')->first();
        $dedi = Employee::where('name', 'Dedi')->first();
        $eko = Employee::where('name', 'Eko')->first();
        $fajar = Employee::where('name', 'Fajar')->first();
        $gilang = Employee::where('name', 'Gilang')->first();

        $operations = [
            [
                'operation_date' => '2026-08-01',
                'bensin_parkir_fee' => 175000.00,
                'entertain_fee' => 0.00,
                'bonus_fee' => 0.00,
                'description' => 'Pembersihan & flushing saluran pipa utama gedung (RS Abdi Waluyo).',
                'technicians' => [
                    ['employee_id' => $ardy ? $ardy->id : 5, 'wage_amount' => 300000.00],
                    ['employee_id' => $abi ? $abi->id : 9, 'wage_amount' => 200000.00],
                ]
            ],
            [
                'operation_date' => '2026-08-01',
                'bensin_parkir_fee' => 85000.00,
                'entertain_fee' => 350000.00,
                'bonus_fee' => 150000.00,
                'description' => 'Meeting akuisisi contract maintenance + pengerjaan lembur grease trap dapur.',
                'technicians' => [
                    ['employee_id' => $budi ? $budi->id : 6, 'wage_amount' => 350000.00],
                    ['employee_id' => $candra ? $candra->id : 10, 'wage_amount' => 180000.00],
                ]
            ],
            [
                'operation_date' => '2026-08-02',
                'bensin_parkir_fee' => 120000.00,
                'entertain_fee' => 0.00,
                'bonus_fee' => 200000.00,
                'description' => 'Pengerjaan 2 lokasi tambahan manhole dapur resto.',
                'technicians' => [
                    ['employee_id' => $dedi ? $dedi->id : 7, 'wage_amount' => 300000.00],
                    ['employee_id' => $eko ? $eko->id : 11, 'wage_amount' => 200000.00],
                ]
            ],
            [
                'operation_date' => '2026-08-02',
                'bensin_parkir_fee' => 250000.00,
                'entertain_fee' => 275000.00,
                'bonus_fee' => 300000.00,
                'description' => 'Shift malam lembur mampet parah saluran limbah utama.',
                'technicians' => [
                    ['employee_id' => $ardy ? $ardy->id : 5, 'wage_amount' => 400000.00],
                    ['employee_id' => $fajar ? $fajar->id : 8, 'wage_amount' => 250000.00],
                    ['employee_id' => $gilang ? $gilang->id : 12, 'wage_amount' => 180000.00],
                ]
            ],
            [
                'operation_date' => '2026-08-02',
                'bensin_parkir_fee' => 150000.00,
                'entertain_fee' => 150000.00,
                'bonus_fee' => 0.00,
                'description' => 'Inspection & preventive maintenance bulanan area basement hospital.',
                'technicians' => [
                    ['employee_id' => $budi ? $budi->id : 6, 'wage_amount' => 300000.00],
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
