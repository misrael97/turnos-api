<?php

namespace App\Http\Controllers;

use App\Models\Negocio;
use Illuminate\Http\Request;

class NegociosController extends Controller
{
    public function index()
    {
        return Negocio::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
        ]);
        $negocio = Negocio::create($validated);
        return response()->json($negocio, 201);
    }

    public function show($id)
    {
        return Negocio::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $negocio = Negocio::findOrFail($id);
        $negocio->update($request->only('nombre'));
        return response()->json($negocio);
    }

    public function destroy($id)
    {
        $negocio = Negocio::findOrFail($id);
        $negocio->delete();
        return response()->json(null, 204);
    }
}



