<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Technician;

class TechnicianManagementTest extends TestCase
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

    public function test_admin_ops_can_view_technicians_index()
    {
        Technician::create(['name' => 'Budi Santoso', 'level' => 'Senior']);

        $response = $this->actingAs($this->adminOps)->get(route('admin_ops.technicians.index'));

        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertSee('Senior');
    }

    public function test_admin_ops_can_create_technician()
    {
        $response = $this->actingAs($this->adminOps)->post(route('admin_ops.technicians.store'), [
            'name' => 'Candra Wijaya',
            'level' => 'Junior',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('technicians', [
            'name' => 'Candra Wijaya',
            'level' => 'Junior',
        ]);
    }

    public function test_validation_fails_if_technician_data_is_invalid()
    {
        $response = $this->actingAs($this->adminOps)->post(route('admin_ops.technicians.store'), [
            'name' => '',
            'level' => 'InvalidLevel',
        ]);

        $response->assertSessionHasErrors(['name', 'level']);
    }

    public function test_admin_ops_can_update_technician()
    {
        $tech = Technician::create(['name' => 'Old Name', 'level' => 'Junior']);

        $response = $this->actingAs($this->adminOps)->put(route('admin_ops.technicians.update', $tech->id), [
            'name' => 'New Name',
            'level' => 'Senior',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('technicians', [
            'id' => $tech->id,
            'name' => 'New Name',
            'level' => 'Senior',
        ]);
    }

    public function test_admin_ops_can_delete_technician()
    {
        $tech = Technician::create(['name' => 'Delete Me', 'level' => 'Junior']);

        $response = $this->actingAs($this->adminOps)->delete(route('admin_ops.technicians.destroy', $tech->id));

        $response->assertRedirect();
        $this->assertSoftDeleted('technicians', [
            'id' => $tech->id,
        ]);
    }
}
