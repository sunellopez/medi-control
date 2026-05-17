<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{
    /**
     * GET /api/patients
     * List patients with optional search and pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = Patient::query()->with('user');

        // Only restrict if the user has the 'patient' role
        if ($user->role_name === 'patient') {
            $query->where('user_id', $user->id);
        }

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name',  'like', "%{$search}%")
                    ->orWhere('phone',      'like', "%{$search}%");
            });
        }

        $patients = $query->orderBy('id', 'desc')->paginate($request->query('per_page', 20));

        return response()->json($patients);
    }

    /**
     * POST /api/patients
     * Create a new patient.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'date_of_birth'     => 'required|date',
            'gender'            => ['required', Rule::in(['male', 'female', 'other'])],
            'phone'             => 'nullable|string|max:30',
            'address'           => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone'   => 'nullable|string|max:30',
            'user_id'           => 'nullable|exists:users,id',
        ]);

        if ($request->user() && $request->user()->role_name === 'patient') {
            $data['user_id'] = $request->user()->id;
        } else {
            $data['user_id'] = $request->user()->id;
        }

        $patient = Patient::create($data);

        return response()->json($patient, 201);
    }

    /**
     * GET /api/patients/{patient}
     * Show a single patient with their appointments and medical records.
     */
    public function show(Patient $patient): JsonResponse
    {
        $patient->load(['appointments.doctor.user', 'medicalRecords.doctor.user']);

        return response()->json($patient);
    }

    /**
     * PUT /api/patients/{patient}
     * Update a patient's data.
     */
    public function update(Request $request, Patient $patient): JsonResponse
    {
        $data = $request->validate([
            'first_name'        => 'sometimes|string|max:255',
            'last_name'         => 'sometimes|string|max:255',
            'date_of_birth'     => 'sometimes|date',
            'gender'            => ['sometimes', Rule::in(['male', 'female', 'other'])],
            'phone'             => 'nullable|string|max:30',
            'address'           => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone'   => 'nullable|string|max:30',
            'user_id'           => 'nullable|exists:users,id',
        ]);

        // Auto-assign/overwrite user_id if the logged-in user has the 'patient' role
        if ($request->user() && $request->user()->role_name === 'patient') {
            $data['user_id'] = $request->user()->id;
        } else {
            // For admin/receptionist, only update if passed in payload
            if ($request->has('user_id')) {
                $data['user_id'] = $request->input('user_id');
            }
        }

        $patient->update($data);

        return response()->json($patient);
    }

    /**
     * DELETE /api/patients/{patient}
     * Soft-delete (hard delete) a patient.
     */
    public function destroy(Patient $patient): JsonResponse
    {
        $patient->delete();

        return response()->json(['message' => 'Paciente eliminado correctamente.']);
    }
}
