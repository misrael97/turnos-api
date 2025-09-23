<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use Illuminate\Http\Request;

class SucursalesController extends Controller
{
    public function index($negocioId)
    {
        return Sucursal::where('negocio_id', $negocioId)->get();
    }

    public function store(Request $request)
    {
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
        return Sucursal::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $sucursal = Sucursal::findOrFail($id);
        $sucursal->update($request->only('nombre', 'direccion'));
        return response()->json($sucursal);
    }

    public function destroy($id)
    {
        $sucursal = Sucursal::findOrFail($id);
        $sucursal->delete();
        return response()->json(null, 204);
    }
}


