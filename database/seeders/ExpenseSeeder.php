<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expense;
use App\Models\Income;
use App\Models\User;
use Carbon\Carbon;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        $userId = $user ? $user->id : 1;

        // Categories list
        $categories = [
            ['cat' => 'fuel_parking', 'desc' => 'Bensin operasional dan parkir tim lapangan', 'min' => 150000.00, 'max' => 300000.00],
            ['cat' => 'technician_wage', 'desc' => 'Upah harian tim teknisi lapangan', 'min' => 400000.00, 'max' => 800000.00],
            ['cat' => 'entertain', 'desc' => 'Makan siang & koordinasi klien', 'min' => 200000.00, 'max' => 500000.00],
            ['cat' => 'ads', 'desc' => 'Topup Google Ads marketing bulanan', 'min' => 1000000.00, 'max' => 2500000.00],
            ['cat' => 'bonus_location', 'desc' => 'Bonus lokasi tambahan pengerjaan pararel', 'min' => 100000.00, 'max' => 300000.00],
            ['cat' => 'bonus_night', 'desc' => 'Bonus lembur shift malam (Urgent)', 'min' => 150000.00, 'max' => 400000.00],
            ['cat' => 'unexpected', 'desc' => 'Pembelian suku cadang darurat & sewa pompa khusus', 'min' => 300000.00, 'max' => 1200000.00],
        ];

        // Seed over the last 6 months (matching IncomeSeeder)
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            
            // Get incomes for this month to optionally link them
            $incomesThisMonth = Income::whereMonth('service_date', $monthDate->month)
                ->whereYear('service_date', $monthDate->year)
                ->get();

            // Generate expenses
            $count = rand(6, 12);
            for ($j = 0; $j < $count; $j++) {
                $category = $categories[array_rand($categories)];
                $income = $incomesThisMonth->isNotEmpty() ? $incomesThisMonth->random() : null;

                $day = rand(1, 28);
                $createdAt = Carbon::create($monthDate->year, $monthDate->month, $day, rand(8, 20), rand(0, 59));

                Expense::create([
                    'user_id' => $userId,
                    'income_id' => $income ? $income->id : null,
                    'client_name' => $income ? $income->client_name : null,
                    'category' => $category['cat'],
                    'amount' => rand($category['min'], $category['max']),
                    'description' => $category['desc'] . ($income ? ' (' . $income->client_name . ')' : ' (Umum)'),
                    'status' => 'approved',
                    'approved_by' => $userId,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }

            // Also seed a few pending requests for the current month
            if ($i === 0) {
                for ($j = 0; $j < 2; $j++) {
                    $category = $categories[array_rand($categories)];
                    $income = $incomesThisMonth->isNotEmpty() ? $incomesThisMonth->random() : null;
                    
                    Expense::create([
                        'user_id' => $userId,
                        'income_id' => $income ? $income->id : null,
                        'client_name' => $income ? $income->client_name : null,
                        'category' => $category['cat'],
                        'amount' => rand($category['min'], $category['max']),
                        'description' => '[PENDING] Pengajuan biaya: ' . $category['desc'],
                        'status' => 'pending',
                        'created_at' => Carbon::now()->subHours(rand(1, 12)),
                    ]);
                }
            }
        }
    }
}
