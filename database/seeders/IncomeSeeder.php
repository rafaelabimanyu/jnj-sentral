<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Income;
use App\Models\User;
use Carbon\Carbon;

class IncomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        $userId = $user ? $user->id : 1;

        $clientCategories = [
            'RS Abdi Waluyo' => 'B2B - Hospital/Medis',
            'Sushi Tei Plaza Indonesia' => 'B2B - F&B',
            'Bapak Yunedi' => 'Residensial/Rumah Tangga',
            'Hotel Santika Jakarta' => 'B2B - F&B',
            'Resto Bumbu Desa' => 'B2B - F&B',
            'Ibu Ratna Menteng' => 'Residensial/Rumah Tangga',
            'Apartemen Sudirman Tower' => 'Residensial/Rumah Tangga',
            'Bapak Haryono Wijaya' => 'Residensial/Rumah Tangga',
            'Gedung Cyber 1' => 'B2B - Pemerintahan'
        ];

        $services = [
            ['detail' => 'Pembersihan grease trap dapur utama dan flushing pipa pembuangan.', 'amount' => 4500000.00],
            ['detail' => 'Flushing pipa mampet area toilet basement dan lobby.', 'amount' => 2500000.00],
            ['detail' => 'Deteksi kebocoran pipa air bersih dan perbaikan instalasi pipa PVC.', 'amount' => 1800000.00],
            ['detail' => 'Kuras bak kontrol luar ruangan dan perbaikan dinding retak.', 'amount' => 3200000.00],
            ['detail' => 'Maintenance contract bulanan pembersihan saluran limbah cair.', 'amount' => 15000000.00],
            ['detail' => 'Flushing total instalasi pipa gedung vertikal (riser) lantai 1-5.', 'amount' => 25000000.00],
            ['detail' => 'Emergency service wastafel mampet dan bau saluran.', 'amount' => 850000.00],
        ];

        // Seed over the last 6 months (including current month)
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            
            // Generate 4 to 7 incomes per month
            $count = rand(4, 7);
            for ($j = 0; $j < $count; $j++) {
                $client = array_rand($clientCategories);
                $category = $clientCategories[$client];
                $service = $services[array_rand($services)];
                
                // Random day in that month
                $day = rand(1, 28);
                $serviceDate = Carbon::create($monthDate->year, $monthDate->month, $day)->toDateString();

                Income::create([
                    'client_name' => $client,
                    'client_category' => $category,
                    'user_id' => $userId,
                    'service_date' => $serviceDate,
                    'service_detail' => $service['detail'] . ' (' . $client . ')',
                    'gross_amount' => $service['amount'] * (rand(90, 110) / 100), // add slight variation
                ]);
            }
        }
    }
}
