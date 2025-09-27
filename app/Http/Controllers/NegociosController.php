<?php

namespace App\Http\Controllers;

use App\Models\Negocio;
use Illuminate\Http\Request;

class NegociosController extends Controller
{
    public function index()
    {
        if (request()->user()->role !== 'admin_general') {
            abort(403, 'Solo el administrador general puede ver negocios.');
        }
        return Negocio::all();
    }

    public function store(Request $request)
    {
        if ($request->user()->role !== 'admin_general') {
            abort(403, 'Solo el administrador general puede crear negocios.');
        }
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
        ]);
        $negocio = Negocio::create($validated);
        return response()->json($negocio, 201);
    }

    public function show($id)
    {
        if (request()->user()->role !== 'admin_general') {
            abort(403, 'Solo el administrador general puede ver negocios.');
        }
        return Negocio::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        if ($request->user()->role !== 'admin_general') {
            abort(403, 'Solo el administrador general puede editar negocios.');
        }
        $negocio = Negocio::findOrFail($id);
        $negocio->update($request->only('nombre'));
        return response()->json($negocio);
    }

    public function destroy($id)
    {
        if (request()->user()->role !== 'admin_general') {
            abort(403, 'Solo el administrador general puede eliminar negocios.');
        }
        $negocio = Negocio::findOrFail($id);
        $negocio->delete();
        return response()->json(null, 204);
    }
}






