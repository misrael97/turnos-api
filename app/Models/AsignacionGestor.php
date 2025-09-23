<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignacionGestor extends Model
{
    use HasFactory;

    protected $table = 'asignaciones_gestor';

    protected $fillable = [
        'usuario_id',
        'sucursal_id',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }
}

    {
        $user = User::where('role', 'gestor')->findOrFail($id);
        AsignacionGestor::where('usuario_id', $user->id)->delete();
        $user->delete();
        return response()->json(null, 204);
    }


