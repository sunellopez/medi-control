<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Services\AppointmentService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $query = Appointment::with(['patient', 'doctor.user'])
            ->orderBy('appointment_date', 'desc');

        if ($user->role_name === 'doctor') {
            $doctor = $user->doctor;
            if ($doctor) {
                $query->where('doctor_id', $doctor->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        } elseif ($user->role_name === 'patient') {
            $patient = $user->patient;
            if ($patient) {
                $query->where('patient_id', $patient->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        $appointments = $query->get();

        return response()->json($appointments);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'notes' => 'nullable|string',
        ]);

        try {
            $this->appointmentService->validateAvailability($data['doctor_id'], $data['date'], $data['time']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $appointmentDateTime = Carbon::parse($data['date'] . ' ' . $data['time']);

        $appointment = Appointment::create([
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctor_id'],
            'appointment_date' => $appointmentDateTime,
            'status' => 'scheduled',
            'notes' => $data['notes'] ?? '',
        ]);

        return response()->json($appointment->load(['patient', 'doctor.user']), 201);
    }

    public function show(Appointment $appointment): JsonResponse
    {
        return response()->json($appointment->load(['patient', 'doctor.user']));
    }

    public function update(Request $request, Appointment $appointment): JsonResponse
    {
        $data = $request->validate([
            'patient_id' => 'sometimes|exists:patients,id',
            'doctor_id' => 'sometimes|exists:doctors,id',
            'date' => 'sometimes|date',
            'time' => 'sometimes|date_format:H:i',
            'status' => 'sometimes|in:scheduled,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        if (isset($data['date']) && isset($data['time'])) {
            $doctorId = $data['doctor_id'] ?? $appointment->doctor_id;
            try {
                $this->appointmentService->validateAvailability($doctorId, $data['date'], $data['time'], $appointment->id);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 422);
            }
            $data['appointment_date'] = Carbon::parse($data['date'] . ' ' . $data['time']);
            unset($data['date'], $data['time']);
        }

        $appointment->update($data);

        return response()->json($appointment->load(['patient', 'doctor.user']));
    }

    public function destroy(Appointment $appointment): JsonResponse
    {
        $appointment->update(['status' => 'cancelled']);
        return response()->json(['message' => 'Cita cancelada correctamente.']);
    }
}
