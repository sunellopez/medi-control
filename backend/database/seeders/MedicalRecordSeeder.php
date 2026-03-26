<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MedicalRecordSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            [
                'patient_id'     => 1,
                'doctor_id'      => 1,
                'appointment_id' => 1,
                'diagnosis'      => 'Infección respiratoria superior leve. Rinofaringitis aguda.',
                'treatment'      => 'Reposo, hidratación abundante y analgésicos según necesidad.',
                'prescription'   => 'Paracetamol 500mg cada 8h por 5 días. Loratadina 10mg noche.',
                'notes'          => 'Paciente refiere fiebre de 37.8°C los últimos 2 días. Sin dificultad respiratoria.',
                'created_at'     => Carbon::now()->subDays(14),
                'updated_at'     => Carbon::now()->subDays(14),
            ],
            [
                'patient_id'     => 2,
                'doctor_id'      => 2,
                'appointment_id' => 2,
                'diagnosis'      => 'Hipertensión arterial grado I. Sin daño a órgano blanco.',
                'treatment'      => 'Modificación de estilo de vida: dieta hiposódica, ejercicio aeróbico 30 min/día.',
                'prescription'   => 'Losartán 50mg cada 24h. Control en 1 mes.',
                'notes'          => 'ECG normal. PA: 148/92 mmHg. IMC: 27.4. Sin antecedentes familiares cardíacos.',
                'created_at'     => Carbon::now()->subDays(10),
                'updated_at'     => Carbon::now()->subDays(10),
            ],
            [
                'patient_id'     => 3,
                'doctor_id'      => 1,
                'appointment_id' => 3,
                'diagnosis'      => 'Faringitis bacteriana estreptocócica. Streptococcus pyogenes confirmado.',
                'treatment'      => 'Antibioticoterapia completada. Evolución favorable.',
                'prescription'   => 'Sin nuevos medicamentos. Alta médica.',
                'notes'          => 'Control post-tratamiento satisfactorio. Cultivo de control negativo.',
                'created_at'     => Carbon::now()->subDays(7),
                'updated_at'     => Carbon::now()->subDays(7),
            ],
            [
                'patient_id'     => 4,
                'doctor_id'      => 2,
                'appointment_id' => 6,
                'diagnosis'      => 'Chequeo anual sin hallazgos patológicos mayores. HTA borderline.',
                'treatment'      => 'Control de peso, reducción de ingesta de sal y cafeína.',
                'prescription'   => 'Ácido acetilsalicílico 100mg preventivo. Vitamina D3 1000 UI.',
                'notes'          => 'Laboratorios: glucemia 98 mg/dL, colesterol total 198 mg/dL, creatinina 0.9 mg/dL. Todo en rango normal.',
                'created_at'     => Carbon::now()->subDays(1),
                'updated_at'     => Carbon::now()->subDays(1),
            ],
            [
                'patient_id'     => 7,
                'doctor_id'      => 1,
                'appointment_id' => null,
                'diagnosis'      => 'Diabetes mellitus tipo 2 en control adecuado. HbA1c 6.8%.',
                'treatment'      => 'Continuar tratamiento hipoglucemiante actual. Dieta diabética.',
                'prescription'   => 'Metformina 850mg c/12h. Glibenclamida 5mg con el desayuno.',
                'notes'          => 'Paciente con DM2 de 8 años de evolución. Perfil lipídico en metas. Sin complicaciones.',
                'created_at'     => Carbon::now()->subDays(21),
                'updated_at'     => Carbon::now()->subDays(21),
            ],
        ];

        DB::table('medical_records')->insert($records);
    }
}
