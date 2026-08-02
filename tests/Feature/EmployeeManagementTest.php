<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Employee;

class EmployeeManagementTest extends TestCase
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

    public function test_admin_ops_can_view_employees_index()
    {
        Employee::create(['name' => 'Budi Santoso', 'role' => 'Teknisi', 'level' => 'Senior', 'status' => 'Active']);
        Employee::create(['name' => 'Siti Rahma', 'role' => 'Admin', 'level' => 'Staff', 'status' => 'Active']);

        $response = $this->actingAs($this->adminOps)->get(route('admin_ops.employees.index'));

        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertSee('Siti Rahma');
    }

    public function test_admin_ops_can_filter_employees_by_role()
    {
        Employee::create(['name' => 'Teknisi One', 'role' => 'Teknisi', 'level' => 'Senior', 'status' => 'Active']);
        Employee::create(['name' => 'Admin One', 'role' => 'Admin', 'level' => 'Staff', 'status' => 'Active']);

        $response = $this->actingAs($this->adminOps)->get(route('admin_ops.employees.index', ['role' => 'Teknisi']));

        $response->assertStatus(200);
        $response->assertSee('Teknisi One');
        $response->assertDontSee('Admin One');
    }

    public function test_admin_ops_can_create_employee()
    {
        $response = $this->actingAs($this->adminOps)->post(route('admin_ops.employees.store'), [
            'name' => 'Candra Wijaya',
            'role' => 'Teknisi',
            'level' => 'Junior',
            'status' => 'Active',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('employees', [
            'name' => 'Candra Wijaya',
            'role' => 'Teknisi',
            'level' => 'Junior',
            'status' => 'Active',
        ]);
    }

    public function test_validation_fails_if_employee_data_is_invalid()
    {
        $response = $this->actingAs($this->adminOps)->post(route('admin_ops.employees.store'), [
            'name' => '',
            'role' => 'InvalidRole',
            'level' => 'InvalidLevel',
            'status' => 'InvalidStatus',
        ]);

        $response->assertSessionHasErrors(['name', 'role', 'level', 'status']);
    }

    public function test_admin_ops_can_update_employee()
    {
        $emp = Employee::create(['name' => 'Old Name', 'role' => 'Teknisi', 'level' => 'Junior', 'status' => 'Active']);

        $response = $this->actingAs($this->adminOps)->put(route('admin_ops.employees.update', $emp->id), [
            'name' => 'New Name',
            'role' => 'Admin',
            'level' => 'Lead',
            'status' => 'Active',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('employees', [
            'id' => $emp->id,
            'name' => 'New Name',
            'role' => 'Admin',
            'level' => 'Lead',
        ]);
    }

    public function test_admin_ops_can_delete_employee()
    {
        $emp = Employee::create(['name' => 'Delete Me', 'role' => 'Teknisi', 'level' => 'Junior', 'status' => 'Active']);

        $response = $this->actingAs($this->adminOps)->delete(route('admin_ops.employees.destroy', $emp->id));

        $response->assertRedirect();
        $this->assertSoftDeleted('employees', [
            'id' => $emp->id,
        ]);
    }
}
