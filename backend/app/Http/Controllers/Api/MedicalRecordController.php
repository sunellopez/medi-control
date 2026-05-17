<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MedicalRecordController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = MedicalRecord::query()->with(['patient', 'doctor.user']);

        if ($user->role_name !== 'admin') {
            $query->whereBelongsTo($user->doctor, 'doctor');
        }

        $records = $query->orderBy('created_at', 'desc')->get();
        return response()->json($records);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'symptoms' => 'required|string',
            'diagnosis' => 'required|string',
            'treatment' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $record = MedicalRecord::create($data);
        return response()->json($record->load(['patient', 'doctor.user']), 201);
    }

    public function show(MedicalRecord $medicalRecord): JsonResponse
    {
        return response()->json($medicalRecord->load(['patient', 'doctor.user']));
    }

    public function update(Request $request, MedicalRecord $medicalRecord): JsonResponse
    {
        $data = $request->validate([
            'symptoms' => 'sometimes|required|string',
            'diagnosis' => 'sometimes|required|string',
            'treatment' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $medicalRecord->update($data);
        return response()->json($medicalRecord);
    }

    public function destroy(MedicalRecord $medicalRecord): JsonResponse
    {
        $medicalRecord->delete();
        return response()->json(['message' => 'Expediente eliminado correctamente.']);
    }
}
