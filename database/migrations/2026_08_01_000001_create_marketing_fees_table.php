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
        Schema::create('marketing_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('marketer_name');
            $table->string('client_name');
            $table->decimal('project_value', 12, 2);
            $table->decimal('fee_percentage', 5, 2);
            $table->decimal('fee_amount', 12, 2);
            $table->enum('status', ['Pending', 'Paid'])->default('Pending');
            $table->date('payment_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_fees');
    }
};
