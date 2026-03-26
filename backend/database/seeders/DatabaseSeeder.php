<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database in dependency order.
     */
    public function run(): void
    {
        // 1. Roles
        $roles = [
            ['name' => 'admin',        'description' => 'Administrador del sistema'],
            ['name' => 'doctor',       'description' => 'Médico con acceso a expedientes'],
            ['name' => 'receptionist', 'description' => 'Recepcionista y agenda de citas'],
            ['name' => 'patient',      'description' => 'Paciente del consultorio'],
        ];
        foreach ($roles as $role) {
            \App\Models\Role::firstOrCreate(['name' => $role['name']], $role);
        }

        // 2. Admin user
        User::firstOrCreate(
            ['email' => 'admin@medicontrol.com'],
            [
                'name'     => 'Administrador MediControl',
                'password' => Hash::make('password'),
                'role_id'  => 1,
            ]
        );

        // 3. Test user (kept for quick login)
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name'     => 'Test User',
                'password' => Hash::make('password'),
                'role_id'  => 1,
            ]
        );

        // 4. Doctors (creates their own user accounts internally)
        $this->call(DoctorSeeder::class);

        // 5. Patients
        $this->call(PatientSeeder::class);

        // 6. Appointments (depends on patients + doctors)
        $this->call(AppointmentSeeder::class);

        // 7. Medical Records (depends on appointments)
        $this->call(MedicalRecordSeeder::class);
    }
}
