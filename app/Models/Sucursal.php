<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;

    protected $fillable = [
        'negocio_id',
        'nombre',
        'direccion',
    ];

    public function negocio()
    {
        return $this->belongsTo(Negocio::class);
    }
}






