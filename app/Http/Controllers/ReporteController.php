<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        // Ejemplo: cantidad de turnos atendidos por día
        $fecha = $request->input('fecha');
        $query = DB::table('turnos')
            ->selectRaw('DATE(fecha) as fecha, COUNT(*) as turnos_atendidos, AVG(TIMESTAMPDIFF(MINUTE, created_at, updated_at)) as tiempo_promedio_espera')
            ->where('estado', 'atendido');
        if ($fecha) {
            $query->whereDate('fecha', $fecha);
        }
        $result = $query->groupBy('fecha')->get();
        // Productividad de agentes: ejemplo ficticio
        foreach ($result as $row) {
            $row->productividad_agentes = rand(5, 15);
        }
        return $result;
    }

    public function avanzados(Request $request)
    {
        $fecha = $request->input('fecha');
        $sucursalId = $request->input('sucursal_id');
        $query = DB::table('turnos')
            ->join('colas', 'turnos.cola_id', '=', 'colas.id')
            ->join('sucursales', 'colas.sucursal_id', '=', 'sucursales.id')
            ->selectRaw('DATE(turnos.fecha) as fecha, sucursales.nombre as sucursal, COUNT(turnos.id) as turnos_atendidos, AVG(TIMESTAMPDIFF(MINUTE, turnos.created_at, turnos.updated_at)) as tiempo_promedio_espera')
            ->where('turnos.estado', 'atendido');
        if ($fecha) {
            $query->whereDate('turnos.fecha', $fecha);
        }
        if ($sucursalId) {
            $query->where('sucursales.id', $sucursalId);
        }
        $result = $query->groupBy('fecha', 'sucursales.nombre')->get();
        // Productividad de agentes: ejemplo ficticio
        foreach ($result as $row) {
            $row->productividad_agentes = rand(5, 15);
        }
        return $result;
    }
}
