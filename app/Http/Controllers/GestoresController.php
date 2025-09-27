<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AsignacionGestor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GestoresController extends Controller
{
    public function index()
    {
        if (!in_array(request()->user()->role, ['admin_general', 'admin_sucursal'])) {
            abort(403, 'Solo administradores pueden ver gestores.');
        }
        return User::where('role', 'gestor')->get();
    }

    public function store(Request $request)
    {
        if (!in_array($request->user()->role, ['admin_general', 'admin_sucursal'])) {
            abort(403, 'Solo administradores pueden crear gestores.');
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'sucursal_id' => 'required|exists:sucursales,id',
        ]);
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'gestor',
        ]);
        AsignacionGestor::create([
            'usuario_id' => $user->id,
            'sucursal_id' => $validated['sucursal_id'],
        ]);
        return response()->json($user, 201);
    }

    public function show($id)
    {
        if (!in_array(request()->user()->role, ['admin_general', 'admin_sucursal'])) {
            abort(403, 'Solo administradores pueden ver gestores.');
        }
        return User::where('role', 'gestor')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        if (!in_array($request->user()->role, ['admin_general', 'admin_sucursal'])) {
            abort(403, 'Solo administradores pueden editar gestores.');
        }
        $user = User::where('role', 'gestor')->findOrFail($id);
        $user->update($request->only('name', 'email'));
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }
        if ($request->filled('sucursal_id')) {
            AsignacionGestor::updateOrCreate(
                ['usuario_id' => $user->id],
                ['sucursal_id' => $request->sucursal_id]
            );
        }
        return response()->json($user);
    }

    public function destroy($id)
    {
        if (!in_array(request()->user()->role, ['admin_general', 'admin_sucursal'])) {
            abort(403, 'Solo administradores pueden eliminar gestores.');
        }
        $user = User::where('role', 'gestor')->findOrFail($id);
        $user->delete();
        AsignacionGestor::where('usuario_id', $id)->delete();
        return response()->json(null, 204);
    }
}






