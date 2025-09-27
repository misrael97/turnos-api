<?php

namespace App\Http\Controllers;

use App\Models\Cola;
use Illuminate\Http\Request;

class ColaController extends Controller
{
    public function index()
    {
        return Cola::all();
    }

    public function store(Request $request)
    {
        $cola = Cola::create($request->all());
        return response()->json($cola, 201);
    }

    public function show($id)
    {
        return Cola::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $cola = Cola::findOrFail($id);
        $cola->update($request->all());
        return response()->json($cola);
    }

    public function destroy($id)
    {
        Cola::destroy($id);
        return response()->json(null, 204);
    }
}






