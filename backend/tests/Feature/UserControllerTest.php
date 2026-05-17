<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    private $roleAdmin;
    private $roleDoctor;
    private $roleReceptionist;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        $this->roleAdmin = Role::create(['name' => 'admin', 'description' => 'Admin']);
        $this->roleDoctor = Role::create(['name' => 'doctor', 'description' => 'Doctor']);
        $this->roleReceptionist = Role::create(['name' => 'receptionist', 'description' => 'Receptionist']);
    }

    public function test_non_admin_cannot_access_user_management(): void
    {
        $nonAdmin = User::factory()->create([
            'role_id' => $this->roleDoctor->id,
        ]);

        Passport::actingAs($nonAdmin);

        $this->getJson('/api/users')->assertStatus(403);
        $this->getJson('/api/roles')->assertStatus(403);
        $this->postJson('/api/users', [])->assertStatus(403);
    }

    public function test_admin_can_list_roles(): void
    {
        $admin = User::factory()->create([
            'role_id' => $this->roleAdmin->id,
        ]);

        Passport::actingAs($admin);

        $response = $this->getJson('/api/roles');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
        $response->assertJsonFragment(['name' => 'doctor']);
        $response->assertJsonFragment(['name' => 'admin']);
    }

    public function test_admin_can_list_users(): void
    {
        $admin = User::factory()->create([
            'role_id' => $this->roleAdmin->id,
        ]);

        User::factory()->create([
            'role_id' => $this->roleDoctor->id,
        ]);

        Passport::actingAs($admin);

        $response = $this->getJson('/api/users');

        $response->assertStatus(200);
        $response->assertJsonCount(2);
    }

    public function test_admin_can_create_doctor_user(): void
    {
        $admin = User::factory()->create([
            'role_id' => $this->roleAdmin->id,
        ]);

        Passport::actingAs($admin);

        $data = [
            'name' => 'Dr. Gregory House',
            'email' => 'house@medicontrol.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role_id' => $this->roleDoctor->id,
            'specialty' => 'Diagnóstico',
            'license_number' => 'MED-12345',
            'phone' => '555-0199',
        ];

        $response = $this->postJson('/api/users', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'email' => 'house@medicontrol.com',
            'role_id' => $this->roleDoctor->id,
        ]);

        $this->assertDatabaseHas('doctors', [
            'specialty' => 'Diagnóstico',
            'license_number' => 'MED-12345',
            'phone' => '555-0199',
        ]);
    }

    public function test_admin_can_create_non_doctor_user(): void
    {
        $admin = User::factory()->create([
            'role_id' => $this->roleAdmin->id,
        ]);

        Passport::actingAs($admin);

        $data = [
            'name' => 'Jane Doe',
            'email' => 'jane@medicontrol.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role_id' => $this->roleReceptionist->id,
        ];

        $response = $this->postJson('/api/users', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'email' => 'jane@medicontrol.com',
            'role_id' => $this->roleReceptionist->id,
        ]);
    }

    public function test_admin_can_update_user_and_doctor_profile(): void
    {
        $admin = User::factory()->create([
            'role_id' => $this->roleAdmin->id,
        ]);

        $doctorUser = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@medicontrol.com',
            'role_id' => $this->roleDoctor->id,
        ]);

        $doctor = Doctor::create([
            'user_id' => $doctorUser->id,
            'specialty' => 'Original Specialty',
            'license_number' => 'ORIG-123',
            'phone' => '123-456',
        ]);

        Passport::actingAs($admin);

        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@medicontrol.com',
            'role_id' => $this->roleDoctor->id,
            'is_active' => true,
            'specialty' => 'Updated Specialty',
            'license_number' => 'UPD-456',
            'phone' => '654-321',
        ];

        $response = $this->putJson("/api/users/{$doctorUser->id}", $updateData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'id' => $doctorUser->id,
            'name' => 'Updated Name',
            'email' => 'updated@medicontrol.com',
        ]);

        $this->assertDatabaseHas('doctors', [
            'user_id' => $doctorUser->id,
            'specialty' => 'Updated Specialty',
            'license_number' => 'UPD-456',
        ]);
    }

    public function test_admin_can_delete_user(): void
    {
        $admin = User::factory()->create([
            'role_id' => $this->roleAdmin->id,
        ]);

        $userToDelete = User::factory()->create([
            'role_id' => $this->roleReceptionist->id,
        ]);

        Passport::actingAs($admin);

        $response = $this->deleteJson("/api/users/{$userToDelete->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', [
            'id' => $userToDelete->id,
        ]);
    }

    public function test_admin_cannot_delete_self(): void
    {
        $admin = User::factory()->create([
            'role_id' => $this->roleAdmin->id,
        ]);

        Passport::actingAs($admin);

        $response = $this->deleteJson("/api/users/{$admin->id}");

        $response->assertStatus(400);
        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
        ]);
    }
}
