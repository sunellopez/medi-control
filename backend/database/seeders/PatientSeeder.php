<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $patients = [
            [
                'first_name'        => 'Juan',
                'last_name'         => 'Pérez Ramírez',
                'date_of_birth'     => '1985-04-12',
                'gender'            => 'male',
                'phone'             => '555-100-2001',
                'address'           => 'Calle Roble 45, Col. Centro, CDMX',
                'emergency_contact' => 'María Pérez',
                'emergency_phone'   => '555-100-2002',
                'user_id'           => null,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'first_name'        => 'Ana Laura',
                'last_name'         => 'Martínez Torres',
                'date_of_birth'     => '1990-08-22',
                'gender'            => 'female',
                'phone'             => '555-200-3001',
                'address'           => 'Av. Insurgentes 890, Piso 3, CDMX',
                'emergency_contact' => 'Carlos Martínez',
                'emergency_phone'   => '555-200-3002',
                'user_id'           => null,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'first_name'        => 'Carlos',
                'last_name'         => 'López Hernández',
                'date_of_birth'     => '1975-11-30',
                'gender'            => 'male',
                'phone'             => '555-300-4001',
                'address'           => 'Blvd. Av. Reforma 120, Guadalajara',
                'emergency_contact' => 'Rosa López',
                'emergency_phone'   => '555-300-4002',
                'user_id'           => null,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'first_name'        => 'María',
                'last_name'         => 'Sánchez Vega',
                'date_of_birth'     => '1998-02-14',
                'gender'            => 'female',
                'phone'             => '555-400-5001',
                'address'           => 'Calle Cedro 78, Monterrey',
                'emergency_contact' => 'Pedro Sánchez',
                'emergency_phone'   => '555-400-5002',
                'user_id'           => null,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'first_name'        => 'Jorge',
                'last_name'         => 'Gómez Fuentes',
                'date_of_birth'     => '1967-06-08',
                'gender'            => 'male',
                'phone'             => '555-500-6001',
                'address'           => 'Privada Las Palmas 5, Puebla',
                'emergency_contact' => 'Lucía Gómez',
                'emergency_phone'   => '555-500-6002',
                'user_id'           => null,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'first_name'        => 'Laura',
                'last_name'         => 'Díaz Morales',
                'date_of_birth'     => '2002-09-03',
                'gender'            => 'female',
                'phone'             => '555-600-7001',
                'address'           => 'Fraccionamiento Los Pinos 12, Cancún',
                'emergency_contact' => 'Roberto Díaz',
                'emergency_phone'   => '555-600-7002',
                'user_id'           => null,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'first_name'        => 'Roberto',
                'last_name'         => 'Jiménez Castillo',
                'date_of_birth'     => '1955-12-20',
                'gender'            => 'male',
                'phone'             => '555-700-8001',
                'address'           => 'Calle Mirto 33, Toluca',
                'emergency_contact' => 'Elena Jiménez',
                'emergency_phone'   => '555-700-8002',
                'user_id'           => null,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'first_name'        => 'Sofia',
                'last_name'         => 'Reyes Gutiérrez',
                'date_of_birth'     => '1993-03-27',
                'gender'            => 'female',
                'phone'             => '555-800-9001',
                'address'           => 'Calle Palma 7, Querétaro',
                'emergency_contact' => 'Hugo Reyes',
                'emergency_phone'   => '555-800-9002',
                'user_id'           => null,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
        ];

        DB::table('patients')->insert($patients);
    }
}
