<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AppointmentRoleFilterTest extends TestCase
{
    use RefreshDatabase;

    private $roleAdmin;
    private $roleDoctor;
    private $rolePatient;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        $this->roleAdmin = Role::create(['name' => 'admin', 'description' => 'Admin']);
        $this->roleDoctor = Role::create(['name' => 'doctor', 'description' => 'Doctor']);
        $this->rolePatient = Role::create(['name' => 'patient', 'description' => 'Patient']);
    }

    public function test_doctor_can_only_see_their_own_appointments(): void
    {
        // Create Doctor 1 and User
        $userDoc1 = User::factory()->create([
            'role_id' => $this->roleDoctor->id,
        ]);
        $doctor1 = Doctor::create([
            'user_id' => $userDoc1->id,
            'specialty' => 'Cardiología',
            'license_number' => '12345',
            'phone' => '1234567890',
        ]);

        // Create Doctor 2 and User
        $userDoc2 = User::factory()->create([
            'role_id' => $this->roleDoctor->id,
        ]);
        $doctor2 = Doctor::create([
            'user_id' => $userDoc2->id,
            'specialty' => 'Pediatría',
            'license_number' => '67890',
            'phone' => '0987654321',
        ]);

        // Create Patients
        $patient1 = Patient::create([
            'first_name' => 'Juan',
            'last_name' => 'Perez',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
        ]);
        $patient2 = Patient::create([
            'first_name' => 'Maria',
            'last_name' => 'Gomez',
            'date_of_birth' => '1992-02-02',
            'gender' => 'female',
        ]);

        // Create Appointments
        $app1 = Appointment::create([
            'patient_id' => $patient1->id,
            'doctor_id' => $doctor1->id,
            'appointment_date' => Carbon::now()->addDay(),
            'status' => 'scheduled',
            'notes' => 'Cita 1',
        ]);

        $app2 = Appointment::create([
            'patient_id' => $patient2->id,
            'doctor_id' => $doctor2->id,
            'appointment_date' => Carbon::now()->addDays(2),
            'status' => 'scheduled',
            'notes' => 'Cita 2',
        ]);

        // Act as Doctor 1
        Passport::actingAs($userDoc1);

        $response = $this->getJson('/api/appointments');

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['notes' => 'Cita 1']);
        $response->assertJsonMissing(['notes' => 'Cita 2']);
    }

    public function test_patient_can_only_see_their_own_appointments(): void
    {
        // Create Doctor
        $userDoc = User::factory()->create([
            'role_id' => $this->roleDoctor->id,
        ]);
        $doctor = Doctor::create([
            'user_id' => $userDoc->id,
            'specialty' => 'General',
            'license_number' => '11111',
            'phone' => '1234567890',
        ]);

        // Create Patient 1 and User
        $userPat1 = User::factory()->create([
            'role_id' => $this->rolePatient->id,
        ]);
        $patient1 = Patient::create([
            'first_name' => 'Juan',
            'last_name' => 'Perez',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'user_id' => $userPat1->id,
        ]);

        // Create Patient 2 and User
        $userPat2 = User::factory()->create([
            'role_id' => $this->rolePatient->id,
        ]);
        $patient2 = Patient::create([
            'first_name' => 'Maria',
            'last_name' => 'Gomez',
            'date_of_birth' => '1992-02-02',
            'gender' => 'female',
            'user_id' => $userPat2->id,
        ]);

        // Create Appointments
        $app1 = Appointment::create([
            'patient_id' => $patient1->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => Carbon::now()->addDay(),
            'status' => 'scheduled',
            'notes' => 'Cita Juan',
        ]);

        $app2 = Appointment::create([
            'patient_id' => $patient2->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => Carbon::now()->addDays(2),
            'status' => 'scheduled',
            'notes' => 'Cita Maria',
        ]);

        // Act as Patient 1
        Passport::actingAs($userPat1);

        $response = $this->getJson('/api/appointments');

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['notes' => 'Cita Juan']);
        $response->assertJsonMissing(['notes' => 'Cita Maria']);
    }

    public function test_admin_can_see_all_appointments(): void
    {
        // Create Doctor
        $userDoc = User::factory()->create([
            'role_id' => $this->roleDoctor->id,
        ]);
        $doctor = Doctor::create([
            'user_id' => $userDoc->id,
            'specialty' => 'General',
            'license_number' => '11111',
            'phone' => '1234567890',
        ]);

        // Create Patients
        $patient1 = Patient::create([
            'first_name' => 'Juan',
            'last_name' => 'Perez',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
        ]);
        $patient2 = Patient::create([
            'first_name' => 'Maria',
            'last_name' => 'Gomez',
            'date_of_birth' => '1992-02-02',
            'gender' => 'female',
        ]);

        // Create Appointments
        Appointment::create([
            'patient_id' => $patient1->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => Carbon::now()->addDay(),
            'status' => 'scheduled',
            'notes' => 'Cita 1',
        ]);

        Appointment::create([
            'patient_id' => $patient2->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => Carbon::now()->addDays(2),
            'status' => 'scheduled',
            'notes' => 'Cita 2',
        ]);

        // Act as Admin
        $userAdmin = User::factory()->create([
            'role_id' => $this->roleAdmin->id,
        ]);
        Passport::actingAs($userAdmin);

        $response = $this->getJson('/api/appointments');

        $response->assertStatus(200);
        $response->assertJsonCount(2);
    }

    public function test_patient_can_only_see_themselves_in_patients_list(): void
    {
        // Create Patient 1 and User
        $userPat1 = User::factory()->create([
            'role_id' => $this->rolePatient->id,
        ]);
        $patient1 = Patient::create([
            'first_name' => 'Juan',
            'last_name' => 'Perez',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'user_id' => $userPat1->id,
        ]);

        // Create Patient 2 and User
        $userPat2 = User::factory()->create([
            'role_id' => $this->rolePatient->id,
        ]);
        $patient2 = Patient::create([
            'first_name' => 'Maria',
            'last_name' => 'Gomez',
            'date_of_birth' => '1992-02-02',
            'gender' => 'female',
            'user_id' => $userPat2->id,
        ]);

        // Act as Patient 1
        Passport::actingAs($userPat1);

        $response = $this->getJson('/api/patients');

        $response->assertStatus(200);
        
        // Response is paginated
        $response->assertJsonPath('data.0.first_name', 'Juan');
        $response->assertJsonCount(1, 'data');
    }

    public function test_doctor_can_see_all_patients_in_patients_list(): void
    {
        // Create Patients
        Patient::create([
            'first_name' => 'Juan',
            'last_name' => 'Perez',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
        ]);
        Patient::create([
            'first_name' => 'Maria',
            'last_name' => 'Gomez',
            'date_of_birth' => '1992-02-02',
            'gender' => 'female',
        ]);

        // Act as Doctor
        $userDoc = User::factory()->create([
            'role_id' => $this->roleDoctor->id,
        ]);
        Passport::actingAs($userDoc);

        $response = $this->getJson('/api/patients');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
    }
}
