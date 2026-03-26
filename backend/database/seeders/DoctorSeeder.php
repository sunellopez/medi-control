<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        // Create user accounts for doctors (role_id 2 = doctor)
        $doctorUsers = [
            [
                'name'       => 'Dr. Alejandro Torres',
                'email'      => 'a.torres@medicontrol.com',
                'password'   => Hash::make('password'),
                'role_id'    => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'       => 'Dra. Valeria González',
                'email'      => 'v.gonzalez@medicontrol.com',
                'password'   => Hash::make('password'),
                'role_id'    => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'       => 'Dr. Héctor Morales',
                'email'      => 'h.morales@medicontrol.com',
                'password'   => Hash::make('password'),
                'role_id'    => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Get the ID after the last inserted user to link doctors
        $firstUserId = DB::table('users')->max('id') + 1;
        DB::table('users')->insert($doctorUsers);

        $doctors = [
            [
                'user_id'        => $firstUserId,
                'specialty'      => 'Medicina General',
                'license_number' => 'MEX-CED-001234',
                'phone'          => '555-900-1001',
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
            [
                'user_id'        => $firstUserId + 1,
                'specialty'      => 'Cardiología',
                'license_number' => 'MEX-CED-005678',
                'phone'          => '555-900-1002',
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
            [
                'user_id'        => $firstUserId + 2,
                'specialty'      => 'Pediatría',
                'license_number' => 'MEX-CED-009012',
                'phone'          => '555-900-1003',
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
        ];

        DB::table('doctors')->insert($doctors);
    }
}
