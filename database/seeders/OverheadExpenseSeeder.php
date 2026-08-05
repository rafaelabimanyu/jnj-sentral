<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OverheadExpense;
use App\Models\User;
use Carbon\Carbon;

class OverheadExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        $userId = $user ? $user->id : 1;

        $today = Carbon::today();

        // 1. Wifi Kantor (Prorated, Active)
        $amount1 = 1500000.00;
        $days1 = 30;
        $daily1 = $amount1 / $days1;
        OverheadExpense::create([
            'user_id' => $userId,
            'is_prorated' => true,
            'proration_days' => $days1,
            'daily_amount' => $daily1,
            'amount' => $amount1,
            'proration_start_date' => $today->copy()->startOfMonth()->toDateString(),
            'proration_end_date' => $today->copy()->startOfMonth()->addDays(29)->toDateString(),
            'expense_date' => $today->copy()->startOfMonth()->toDateString(),
            'category' => 'Infrastruktur (WiFi, Listrik, Kantor)',
            'title' => 'Biznet Office Wifi',
            'description' => 'Langganan Internet Dedicated Biznet Office 50 Mbps',
        ]);

        // 2. Listrik & Air (Prorated, Active)
        $amount2 = 2600000.00;
        $days2 = 26;
        $daily2 = $amount2 / $days2;
        OverheadExpense::create([
            'user_id' => $userId,
            'is_prorated' => true,
            'proration_days' => $days2,
            'daily_amount' => $daily2,
            'amount' => $amount2,
            'proration_start_date' => $today->copy()->startOfMonth()->toDateString(),
            'proration_end_date' => $today->copy()->startOfMonth()->addDays(25)->toDateString(),
            'expense_date' => $today->copy()->startOfMonth()->toDateString(),
            'category' => 'Infrastruktur (WiFi, Listrik, Kantor)',
            'title' => 'Tagihan Listrik & PAM',
            'description' => 'Biaya Listrik PLN Pascabayar & PAM Kantor Utama',
        ]);

        // 3. Sewa Ruko Kantor (Prorated, Active)
        $amount3 = 6000000.00;
        $days3 = 30;
        $daily3 = $amount3 / $days3;
        OverheadExpense::create([
            'user_id' => $userId,
            'is_prorated' => true,
            'proration_days' => $days3,
            'daily_amount' => $daily3,
            'amount' => $amount3,
            'proration_start_date' => $today->copy()->startOfMonth()->toDateString(),
            'proration_end_date' => $today->copy()->startOfMonth()->addDays(29)->toDateString(),
            'expense_date' => $today->copy()->startOfMonth()->toDateString(),
            'category' => 'Infrastruktur (WiFi, Listrik, Kantor)',
            'title' => 'Sewa Ruko Bulanan',
            'description' => 'Sewa Ruko Operasional Lantai 1 & 2',
        ]);

        // 4. Kopi & Air Galon Karyawan (Non-Prorated, Today)
        OverheadExpense::create([
            'user_id' => $userId,
            'is_prorated' => false,
            'amount' => 450000.00,
            'expense_date' => $today->toDateString(),
            'category' => 'Kesejahteraan (Family Gathering dll)',
            'title' => 'Kebutuhan Dapur Kantor',
            'description' => 'Pembelian Kopi, Teh, dan Galon Aqua untuk Dapur Kantor',
        ]);

        // 5. Perbaikan AC Rusak (Non-Prorated, Today, Emergency)
        OverheadExpense::create([
            'user_id' => $userId,
            'is_prorated' => false,
            'amount' => 750000.00,
            'expense_date' => $today->toDateString(),
            'category' => 'Biaya Tak Terduga (Darurat)',
            'title' => 'Perbaikan AC CS',
            'description' => 'Service & Isi Freon AC Ruang CS',
        ]);

        // Seed older historical overheads (last 3 months)
        for ($i = 3; $i >= 1; $i--) {
            $monthDate = $today->copy()->subMonths($i);
            
            // Historic Wifi
            OverheadExpense::create([
                'user_id' => $userId,
                'is_prorated' => true,
                'proration_days' => 30,
                'daily_amount' => 1200000.00 / 30,
                'amount' => 1200000.00,
                'proration_start_date' => $monthDate->copy()->startOfMonth()->toDateString(),
                'proration_end_date' => $monthDate->copy()->startOfMonth()->addDays(29)->toDateString(),
                'expense_date' => $monthDate->copy()->startOfMonth()->toDateString(),
                'category' => 'Infrastruktur (WiFi, Listrik, Kantor)',
                'title' => 'Biznet Office Wifi',
                'description' => 'Langganan Internet Dedicated Biznet Office 50 Mbps',
            ]);

            // Historic Rent
            OverheadExpense::create([
                'user_id' => $userId,
                'is_prorated' => true,
                'proration_days' => 30,
                'daily_amount' => 6000000.00 / 30,
                'amount' => 6000000.00,
                'proration_start_date' => $monthDate->copy()->startOfMonth()->toDateString(),
                'proration_end_date' => $monthDate->copy()->startOfMonth()->addDays(29)->toDateString(),
                'expense_date' => $monthDate->copy()->startOfMonth()->toDateString(),
                'category' => 'Infrastruktur (WiFi, Listrik, Kantor)',
                'title' => 'Sewa Ruko Bulanan',
                'description' => 'Sewa Ruko Operasional Lantai 1 & 2',
            ]);
        }
    }
}
