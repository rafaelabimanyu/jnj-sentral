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
        // 1. Rename table technicians to employees
        if (Schema::hasTable('technicians') && !Schema::hasTable('employees')) {
            Schema::rename('technicians', 'employees');
        }

        // 2. Update employees table columns
        if (Schema::hasTable('employees')) {
            Schema::table('employees', function (Blueprint $table) {
                if (!Schema::hasColumn('employees', 'role')) {
                    $table->string('role')->default('Teknisi')->after('name');
                }
                if (!Schema::hasColumn('employees', 'status')) {
                    $table->string('status')->default('Active')->after('level');
                }
            });
        }

        // 3. Rename foreign key column technician_id to employee_id in field_operation_technicians
        if (Schema::hasTable('field_operation_technicians')) {
            Schema::table('field_operation_technicians', function (Blueprint $table) {
                if (Schema::hasColumn('field_operation_technicians', 'technician_id') && !Schema::hasColumn('field_operation_technicians', 'employee_id')) {
                    $table->renameColumn('technician_id', 'employee_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('field_operation_technicians')) {
            Schema::table('field_operation_technicians', function (Blueprint $table) {
                if (Schema::hasColumn('field_operation_technicians', 'employee_id')) {
                    $table->renameColumn('employee_id', 'technician_id');
                }
            });
        }

        if (Schema::hasTable('employees')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropColumn(['role', 'status']);
            });

            if (!Schema::hasTable('technicians')) {
                Schema::rename('employees', 'technicians');
            }
        }
    }
};
