<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Medication;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function dashboardStats(Request $request): JsonResponse
    {
        $user = $request->user();

        $doctorId = $user->doctor?->id;

        $patientsQuery = Patient::query();

        $appointmentsQuery = Appointment::query()->whereDate('appointment_date', '>=', now()->toDateString());

        if ($user->role_name !== 'admin') {

            $patientsQuery->whereHas('appointments', function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId);
            });

            $appointmentsQuery->where('doctor_id', $doctorId);
        }

        $stats = [
            'totalPatients' => $patientsQuery->distinct()->count(),
            'totalAppointments' => $appointmentsQuery->count(),
            'activeDoctors' => Doctor::active()->count(),
            'lowStockMedications' => Medication::whereRaw('current_stock <= minimum_stock')->count(),
        ];

        return response()->json($stats);
    }
}
