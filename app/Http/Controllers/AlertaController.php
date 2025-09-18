<?php

namespace App\Http\Controllers;

use App\Models\Alerta;
use Illuminate\Http\Request;

class AlertaController extends Controller
{
    public function index()
    {
        return Alerta::all();
    }

    public function store(Request $request)
    {
        $alerta = Alerta::create($request->all());
        return response()->json($alerta, 201);
    }

    public function show($id)
    {
        return Alerta::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $alerta = Alerta::findOrFail($id);
        $alerta->update($request->all());
        return response()->json($alerta);
    }

    public function destroy($id)
    {
        Alerta::destroy($id);
        return response()->json(null, 204);
    }

    // Crear alerta automática si un turno espera demasiado
    public function crearPorEspera(Request $request)
    {
        $turnoId = $request->input('turno_id');
        $mensaje = $request->input('mensaje', 'Cliente esperando demasiado tiempo');
        $alerta = Alerta::create([
            'mensaje' => $mensaje,
            'fecha' => now(),
            'turno_id' => $turnoId,
            'atendida' => false
        ]);
        return response()->json($alerta, 201);
    }

    // Marcar alerta como atendida
    public function atender($id)
    {
        $alerta = Alerta::findOrFail($id);
        $alerta->atendida = true;
        $alerta->save();
        return response()->json($alerta);
    }
}
