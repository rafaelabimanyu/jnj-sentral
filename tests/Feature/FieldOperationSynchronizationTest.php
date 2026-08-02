<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Employee;
use App\Models\FieldOperation;

class FieldOperationSynchronizationTest extends TestCase
{
    use RefreshDatabase;

    protected $adminOps;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminOps = User::factory()->create([
            'role' => 'admin_ops',
        ]);
    }

    public function test_field_operation_create_page_loads_only_active_technicians()
    {
        $activeSenior = Employee::create(['name' => 'Active Senior Tech', 'role' => 'Teknisi', 'level' => 'Senior', 'status' => 'Active']);
        $inactiveSenior = Employee::create(['name' => 'Inactive Senior Tech', 'role' => 'Teknisi', 'level' => 'Senior', 'status' => 'Inactive']);
        $adminStaff = Employee::create(['name' => 'Admin Staff', 'role' => 'Admin', 'level' => 'Senior', 'status' => 'Active']);

        $response = $this->actingAs($this->adminOps)->get(route('admin_ops.field_operations.create'));

        $response->assertStatus(200);
        $response->assertSee('Active Senior Tech');
        $response->assertDontSee('Inactive Senior Tech');
        $response->assertDontSee('Admin Staff');
    }

    public function test_store_field_operation_with_employees()
    {
        $tech1 = Employee::create(['name' => 'Tech One', 'role' => 'Teknisi', 'level' => 'Senior', 'status' => 'Active']);
        $tech2 = Employee::create(['name' => 'Tech Two', 'role' => 'Teknisi', 'level' => 'Junior', 'status' => 'Active']);

        $response = $this->actingAs($this->adminOps)->post(route('admin_ops.field_operations.store'), [
            'operation_date' => '2026-08-02',
            'description' => 'Test field operation flushing',
            'bensin_parkir_fee' => 50000,
            'entertain_fee' => 0,
            'bonus_fee' => 0,
            'technicians' => [
                ['technician_id' => $tech1->id, 'wage_amount' => 300000],
                ['technician_id' => $tech2->id, 'wage_amount' => 200000],
            ],
        ]);

        $response->assertRedirect(route('admin_ops.field_operations.index'));
        $this->assertDatabaseHas('field_operations', [
            'description' => 'Test field operation flushing',
        ]);
        $this->assertDatabaseHas('field_operation_technicians', [
            'employee_id' => $tech1->id,
            'wage_amount' => 300000,
        ]);
    }
}
