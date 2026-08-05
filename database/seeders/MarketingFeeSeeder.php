<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MarketingFee;
use App\Models\Marketer;
use App\Models\User;
use Carbon\Carbon;

class MarketingFeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        $userId = $user ? $user->id : 1;

        $marketers = Marketer::all();

        if ($marketers->isEmpty()) {
            return;
        }

        // Generate marketing fees over the last 3 months
        for ($i = 2; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);

            foreach ($marketers as $marketer) {
                // Not all marketers get fees every month
                if (rand(0, 10) > 4) {
                    $projectValue = rand(5000000, 20000000);
                    $feePercentage = $marketer->default_fee_percentage ?? 10.00;
                    $feeAmount = ($projectValue * $feePercentage) / 100;
                    $status = rand(0, 10) > 3 ? 'Paid' : 'Pending';

                    $day = rand(1, 28);
                    $createdAt = Carbon::create($monthDate->year, $monthDate->month, $day, 10, 0, 0);

                    MarketingFee::create([
                        'user_id' => $userId,
                        'marketer_id' => $marketer->id,
                        'project_value' => $projectValue,
                        'fee_percentage' => $feePercentage,
                        'fee_amount' => $feeAmount,
                        'status' => $status,
                        'payment_date' => $status === 'Paid' ? $createdAt->addDays(rand(1, 5))->toDateString() : null,
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);
                }
            }
        }
    }
}
