<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * GET /api/users
     * List all users with their roles and profile information.
     */
    public function index(Request $request): JsonResponse
    {
        if ($request->user()->role_name !== 'admin') {
            return response()->json(['message' => 'No autorizado. Solo los administradores pueden realizar esta acción.'], 403);
        }

        $users = User::with(['role', 'doctor', 'patient'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($users);
    }

    /**
     * GET /api/roles
     * List all available roles.
     */
    public function getRoles(Request $request): JsonResponse
    {
        if ($request->user()->role_name !== 'admin') {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        $roles = Role::all();
        return response()->json($roles);
    }

    /**
     * POST /api/users
     * Register/Create a new user (and Doctor profile if doctor role is selected).
     */
    public function store(Request $request): JsonResponse
    {
        if ($request->user()->role_name !== 'admin') {
            return response()->json(['message' => 'No autorizado. Solo los administradores pueden realizar esta acción.'], 403);
        }

        $doctorRoleId = Role::where('name', 'doctor')->value('id');

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ];

        // Conditional validation if doctor role is selected
        if ((int)$request->input('role_id') === $doctorRoleId) {
            $rules['specialty'] = 'required|string|max:255';
            $rules['license_number'] = 'required|string|max:255|unique:doctors,license_number';
            $rules['phone'] = 'required|string|max:30';
        }

        $data = $request->validate($rules);

        $user = DB::transaction(function () use ($data, $doctorRoleId) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role_id' => $data['role_id'],
                'is_active' => true,
            ]);

            if ((int)$data['role_id'] === $doctorRoleId) {
                Doctor::create([
                    'user_id' => $user->id,
                    'specialty' => $data['specialty'],
                    'license_number' => $data['license_number'],
                    'phone' => $data['phone'],
                ]);
            }

            return $user;
        });

        return response()->json($user->load(['role', 'doctor']), 201);
    }

    /**
     * GET /api/users/{user}
     * Get specific user details.
     */
    public function show(Request $request, User $user): JsonResponse
    {
        if ($request->user()->role_name !== 'admin') {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        return response()->json($user->load(['role', 'doctor', 'patient']));
    }

    /**
     * PUT/PATCH /api/users/{user}
     * Update an existing user (and Doctor profile).
     */
    public function update(Request $request, User $user): JsonResponse
    {
        if ($request->user()->role_name !== 'admin') {
            return response()->json(['message' => 'No autorizado. Solo los administradores pueden realizar esta acción.'], 403);
        }

        $doctorRoleId = Role::where('name', 'doctor')->value('id');
        $doctor = $user->doctor;

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'required|boolean',
        ];

        // Conditional validation if doctor role is selected
        if ((int)$request->input('role_id') === $doctorRoleId) {
            $rules['specialty'] = 'required|string|max:255';
            $doctorId = $doctor ? $doctor->id : 'NULL';
            $rules['license_number'] = 'required|string|max:255|unique:doctors,license_number,' . $doctorId;
            $rules['phone'] = 'required|string|max:30';
        }

        $data = $request->validate($rules);

        DB::transaction(function () use ($data, $user, $doctorRoleId, $doctor) {
            $updateData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'role_id' => $data['role_id'],
                'is_active' => $data['is_active'],
            ];

            if (!empty($data['password'])) {
                $updateData['password'] = Hash::make($data['password']);
            }

            $user->update($updateData);

            if ((int)$data['role_id'] === $doctorRoleId) {
                if ($doctor) {
                    $doctor->update([
                        'specialty' => $data['specialty'],
                        'license_number' => $data['license_number'],
                        'phone' => $data['phone'],
                    ]);
                } else {
                    Doctor::create([
                        'user_id' => $user->id,
                        'specialty' => $data['specialty'],
                        'license_number' => $data['license_number'],
                        'phone' => $data['phone'],
                    ]);
                }
            } else {
                // If they changed from doctor to something else, we can delete the doctor profile to clean up
                if ($doctor) {
                    $doctor->delete();
                }
            }
        });

        return response()->json($user->load(['role', 'doctor']));
    }

    /**
     * DELETE /api/users/{user}
     * Delete a user.
     */
    public function destroy(Request $request, User $user): JsonResponse
    {
        if ($request->user()->role_name !== 'admin') {
            return response()->json(['message' => 'No autorizado. Solo los administradores pueden realizar esta acción.'], 403);
        }

        // Prevent self-deletion
        if ($request->user()->id === $user->id) {
            return response()->json(['message' => 'No puedes eliminar tu propia cuenta de administrador.'], 400);
        }

        DB::transaction(function () use ($user) {
            if ($user->doctor) {
                $user->doctor->delete();
            }
            if ($user->patient) {
                $user->patient->delete();
            }
            $user->delete();
        });

        return response()->json(['message' => 'Usuario eliminado correctamente.']);
    }
}
