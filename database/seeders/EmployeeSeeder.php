<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            // Admin & Office Staff
            ['name' => 'Wibowo Pratikno', 'role' => 'Management', 'level' => 'Lead', 'status' => 'Active'],
            ['name' => 'Siti Rahma', 'role' => 'Admin', 'level' => 'Staff', 'status' => 'Active'],
            ['name' => 'Dewi Lestari', 'role' => 'Customer Service', 'level' => 'Staff', 'status' => 'Active'],
            ['name' => 'Rian Hidayat', 'role' => 'Marketing', 'level' => 'Staff', 'status' => 'Active'],

            // Senior Leads (Teknisi)
            ['name' => 'Ardy', 'role' => 'Teknisi', 'level' => 'Senior', 'status' => 'Active'],
            ['name' => 'Budi', 'role' => 'Teknisi', 'level' => 'Senior', 'status' => 'Active'],
            ['name' => 'Dedi', 'role' => 'Teknisi', 'level' => 'Senior', 'status' => 'Active'],
            ['name' => 'Fajar', 'role' => 'Teknisi', 'level' => 'Senior', 'status' => 'Active'],

            // Junior Helpers (Teknisi)
            ['name' => 'Abi', 'role' => 'Teknisi', 'level' => 'Junior', 'status' => 'Active'],
            ['name' => 'Candra', 'role' => 'Teknisi', 'level' => 'Junior', 'status' => 'Active'],
            ['name' => 'Eko', 'role' => 'Teknisi', 'level' => 'Junior', 'status' => 'Active'],
            ['name' => 'Gilang', 'role' => 'Teknisi', 'level' => 'Junior', 'status' => 'Active'],
        ];

        foreach ($employees as $emp) {
            Employee::updateOrCreate(
                ['name' => $emp['name']],
                [
                    'role'   => $emp['role'],
                    'level'  => $emp['level'],
                    'status' => $emp['status'],
                ]
            );
        }
    }
}
