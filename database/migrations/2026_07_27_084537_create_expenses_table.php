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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('income_id')->nullable()->constrained('incomes')->onDelete('restrict');
            $table->string('client_name')->nullable();
            $table->enum('category', [
                'ads',
                'entertain',
                'infrastructure',
                'fuel_parking',
                'technician_wage',
                'bonus_location',
                'bonus_night',
                'marketing_fee',
                'welfare',
                'unexpected'
            ]);
            $table->decimal('amount', 12, 2);
            $table->text('description');
            $table->enum('status', ['approved', 'pending', 'rejected'])->default('approved');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
