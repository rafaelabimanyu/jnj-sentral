<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('overhead_expenses', function (Blueprint $table) {
            $table->boolean('is_prorated')->default(false);
            $table->integer('proration_days')->nullable();
            $table->decimal('daily_amount', 15, 2)->nullable();
            $table->date('proration_start_date')->nullable();
            $table->date('proration_end_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('overhead_expenses', function (Blueprint $table) {
            $table->dropColumn([
                'is_prorated',
                'proration_days',
                'daily_amount',
                'proration_start_date',
                'proration_end_date',
            ]);
        });
    }
};
