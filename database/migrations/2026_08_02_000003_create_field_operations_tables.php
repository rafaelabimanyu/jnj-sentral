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
        Schema::create('field_operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('operation_date');
            $table->decimal('bensin_parkir_fee', 15, 2)->default(0);
            $table->decimal('entertain_fee', 15, 2)->default(0);
            $table->decimal('bonus_fee', 15, 2)->default(0);
            $table->text('description');
            $table->string('receipt_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('field_operation_technicians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('field_operation_id')->constrained('field_operations')->onDelete('cascade');
            $table->foreignId('technician_id')->constrained('technicians')->onDelete('cascade');
            $table->decimal('wage_amount', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('field_operation_technicians');
        Schema::dropIfExists('field_operations');
    }
};
