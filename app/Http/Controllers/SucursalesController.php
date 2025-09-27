<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use Illuminate\Http\Request;

class SucursalesController extends Controller
{
    public function index($negocioId)
    {
        if (!in_array(request()->user()->role, ['admin_general', 'admin_sucursal'])) {
            abort(403, 'Solo administradores pueden ver sucursales.');
        }
        return Sucursal::where('negocio_id', $negocioId)->get();
    }

    public function store(Request $request)
    {
        if (!in_array($request->user()->role, ['admin_general', 'admin_sucursal'])) {
            abort(403, 'Solo administradores pueden crear sucursales.');
        }
        $validated = $request->validate([
            'negocio_id' => 'required|exists:negocios,id',
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
        ]);
        $sucursal = Sucursal::create($validated);
        return response()->json($sucursal, 201);
    }

    public function show($id)
    {
        if (!in_array(request()->user()->role, ['admin_general', 'admin_sucursal'])) {
            abort(403, 'Solo administradores pueden ver sucursales.');
        }
        return Sucursal::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        if (!in_array($request->user()->role, ['admin_general', 'admin_sucursal'])) {
            abort(403, 'Solo administradores pueden editar sucursales.');
        }
        $sucursal = Sucursal::findOrFail($id);
        $sucursal->update($request->only('nombre', 'direccion'));
        return response()->json($sucursal);
    }

    public function destroy($id)
    {
        if (!in_array(request()->user()->role, ['admin_general', 'admin_sucursal'])) {
            abort(403, 'Solo administradores pueden eliminar sucursales.');
        }
        $sucursal = Sucursal::findOrFail($id);
        $sucursal->delete();
        return response()->json(null, 204);
    }
}






