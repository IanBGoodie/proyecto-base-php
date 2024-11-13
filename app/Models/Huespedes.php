<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Huespedes extends Model
{
    use HasFactory;

    protected $table = 'huespedes';
    protected $fillable = [
        'nombre',
        'apellido paterno',
        'apellido materno',
        'codigo del pais',
        'telefono'
    ];
}
