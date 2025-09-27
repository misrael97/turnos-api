<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use Illuminate\Http\Request;

class TurnoController extends Controller
{
    public function index()
    {
        return Turno::all();
    }

    public function store(Request $request)
    {
        if ($request->user()->role !== 'usuario_cliente') {
            abort(403, 'Solo los usuarios pueden solicitar turnos.');
        }
        $turno = Turno::create($request->all());
        return response()->json($turno, 201);
    }

    public function show($id)
    {
        return Turno::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $turno = Turno::findOrFail($id);
        $turno->update($request->all());
        return response()->json($turno);
    }

    public function destroy($id)
    {
        Turno::destroy($id);
        return response()->json(null, 204);
    }

    // Obtener turnos en espera de una cola
    public function enEspera(Request $request)
    {
        $colaId = $request->input('cola_id');
        $query = Turno::where('estado', 'espera');
        if ($colaId) {
            $query->where('cola_id', $colaId);
        }
        return $query->orderBy('fecha')->orderBy('id')->get();
    }

    // Llamar al siguiente turno de una cola
    public function llamarSiguiente(Request $request)
    {
        if ($request->user()->role !== 'gestor') {
            abort(403, 'Solo los gestores pueden llamar turnos.');
        }
        $colaId = $request->input('cola_id');
        $turno = Turno::where('estado', 'espera')->where('cola_id', $colaId)->orderBy('fecha')->orderBy('id')->first();
        if ($turno) {
            $turno->estado = 'llamado';
            $turno->save();
            return response()->json($turno);
        }
        return response()->json(['message' => 'No hay turnos en espera'], 404);
    }

    // Tablero: obtener el turno actual de una cola
    public function tableroActual(Request $request)
    {
        $colaId = $request->input('cola_id');
        $turno = Turno::where('estado', 'llamado')->where('cola_id', $colaId)->orderByDesc('updated_at')->first();
        return $turno;
    }

    // Historial de turnos atendidos
    public function historial(Request $request)
    {
        if ($request->user()->role !== 'gestor') {
            abort(403, 'Solo los gestores pueden ver el historial de turnos.');
        }
        $colaId = $request->input('cola_id');
        $query = Turno::where('estado', 'atendido');
        if ($colaId) {
            $query->where('cola_id', $colaId);
        }
        return $query->orderByDesc('updated_at')->get();
    }

    // Cancelar un turno
    public function cancelar($id)
    {
        $user = request()->user();
        if (!in_array($user->role, ['gestor', 'usuario_cliente'])) {
            abort(403, 'No autorizado para cancelar turnos.');
        }
        $turno = Turno::findOrFail($id);
        $turno->estado = 'cancelado';
        $turno->save();
        return response()->json($turno);
    }

    // Reasignar un turno a otra cola
    public function reasignar(Request $request, $id)
    {
        if ($request->user()->role !== 'gestor') {
            abort(403, 'Solo los gestores pueden reasignar turnos.');
        }
        $turno = Turno::findOrFail($id);
        $turno->cola_id = $request->input('cola_id');
        $turno->save();
        return response()->json($turno);
    }

    // Validar ticket por QR (ejemplo: busca por número de turno)
    public function validarQR(Request $request)
    {
        $numero = $request->input('numero');
        $turno = Turno::where('numero', $numero)->first();
        if ($turno) {
            return response()->json(['valido' => true, 'turno' => $turno]);
        }
        return response()->json(['valido' => false], 404);
    }
}
