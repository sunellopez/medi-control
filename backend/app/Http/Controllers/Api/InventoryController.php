<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Medication;
use App\Models\InventoryMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class InventoryController extends Controller
{
    public function index()
    {
        return response()->json(Medication::orderBy('name')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'batch' => 'nullable|string',
            'expiration_date' => 'nullable|date',
            'current_stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'unit_price' => 'nullable|numeric|min:0',
        ]);

        $medication = Medication::create($data);
        return response()->json($medication, 201);
    }

    public function storeMovement(Request $request)
    {
        $data = $request->validate([
            'medication_id' => 'required|exists:medications,id',
            'type' => 'required|in:in,out,adjustment',
            'quantity' => 'required|integer|min:0',
            'reason' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $movement = InventoryMovement::create([
                'medication_id' => $data['medication_id'],
                'type' => $data['type'],
                'quantity' => $data['quantity'],
                'user_id' => $request->user() ? $request->user()->id : null,
                'reason' => $data['reason'] ?? '',
            ]);

            $medication = Medication::findOrFail($data['medication_id']);
            if ($data['type'] === 'in') {
                $medication->current_stock += $data['quantity'];
            } elseif ($data['type'] === 'out') {
                $medication->current_stock -= $data['quantity'];
            } elseif ($data['type'] === 'adjustment') {
                $medication->current_stock = $data['quantity'];
            }
            $medication->save();
            DB::commit();

            return response()->json($movement, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al registrar movimiento.', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(Medication $medication): JsonResponse
    {
        return response()->json($medication);
    }

    public function update(Request $request, Medication $medication): JsonResponse
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string',
            'description' => 'nullable|string',
            'batch' => 'nullable|string',
            'expiration_date' => 'nullable|date',
            'current_stock' => 'sometimes|required|integer|min:0',
            'minimum_stock' => 'sometimes|required|integer|min:0',
            'unit_price' => 'nullable|numeric|min:0',
        ]);

        $medication->update($data);
        return response()->json($medication);
    }

    public function destroy(Medication $medication): JsonResponse
    {
        $medication->delete();
        return response()->json(['message' => 'Medicamento eliminado correctamente.']);
    }
}
