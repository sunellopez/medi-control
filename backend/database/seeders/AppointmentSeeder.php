<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $appointments = [
            // Completed (past)
            ['patient_id' => 1, 'doctor_id' => 1, 'appointment_date' => $now->copy()->subDays(14)->setTime(9, 0),  'status' => 'completed',  'notes' => 'Consulta rutinaria, paciente en buen estado general.'],
            ['patient_id' => 2, 'doctor_id' => 2, 'appointment_date' => $now->copy()->subDays(10)->setTime(10, 30), 'status' => 'completed',  'notes' => 'Revisión de electrocardiograma. Sin anomalías detectadas.'],
            ['patient_id' => 3, 'doctor_id' => 1, 'appointment_date' => $now->copy()->subDays(7)->setTime(11, 0),  'status' => 'completed',  'notes' => 'Control post-tratamiento antibiótico. Mejoría notable.'],
            ['patient_id' => 5, 'doctor_id' => 3, 'appointment_date' => $now->copy()->subDays(5)->setTime(9, 30),  'status' => 'cancelled',  'notes' => 'Paciente canceló por motivos personales.'],
            ['patient_id' => 6, 'doctor_id' => 3, 'appointment_date' => $now->copy()->subDays(3)->setTime(14, 0),  'status' => 'no_show',    'notes' => null],
            ['patient_id' => 4, 'doctor_id' => 2, 'appointment_date' => $now->copy()->subDays(1)->setTime(16, 0),  'status' => 'completed',  'notes' => 'Chequeo anual. Presión arterial ligeramente elevada. Se recomienda seguimiento.'],

            // Today
            ['patient_id' => 1, 'doctor_id' => 1, 'appointment_date' => $now->copy()->setTime(9, 0),  'status' => 'scheduled', 'notes' => null],
            ['patient_id' => 2, 'doctor_id' => 1, 'appointment_date' => $now->copy()->setTime(9, 30), 'status' => 'scheduled', 'notes' => 'Revisión de resultados de laboratorio.'],
            ['patient_id' => 3, 'doctor_id' => 2, 'appointment_date' => $now->copy()->setTime(10, 0), 'status' => 'scheduled', 'notes' => 'Control post-tratamiento.'],
            ['patient_id' => 4, 'doctor_id' => 1, 'appointment_date' => $now->copy()->setTime(11, 15),'status' => 'scheduled', 'notes' => null],
            ['patient_id' => 5, 'doctor_id' => 2, 'appointment_date' => $now->copy()->setTime(12, 0), 'status' => 'cancelled', 'notes' => 'Cancelada por el médico.'],
            ['patient_id' => 6, 'doctor_id' => 3, 'appointment_date' => $now->copy()->setTime(15, 0), 'status' => 'scheduled', 'notes' => 'Consulta pediátrica de seguimiento.'],
            ['patient_id' => 7, 'doctor_id' => 1, 'appointment_date' => $now->copy()->setTime(16, 30),'status' => 'scheduled', 'notes' => 'Revisión anual.'],

            // Future
            ['patient_id' => 8, 'doctor_id' => 2, 'appointment_date' => $now->copy()->addDays(2)->setTime(10, 0), 'status' => 'scheduled', 'notes' => 'Primera visita cardiológica.'],
            ['patient_id' => 1, 'doctor_id' => 3, 'appointment_date' => $now->copy()->addDays(5)->setTime(9, 0),  'status' => 'scheduled', 'notes' => 'Seguimiento de tratamiento.'],
            ['patient_id' => 4, 'doctor_id' => 2, 'appointment_date' => $now->copy()->addDays(7)->setTime(11, 0), 'status' => 'scheduled', 'notes' => null],
        ];

        foreach ($appointments as &$appt) {
            $appt['created_at'] = Carbon::now();
            $appt['updated_at'] = Carbon::now();
        }

        DB::table('appointments')->insert($appointments);
    }
}
