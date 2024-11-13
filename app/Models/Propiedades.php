<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Propiedades extends Model
{
    use HasFactory;

    protected $table = 'propiedades';

    protected $fillable = [
       'nombre',
       'calle',
       'colonia',
       'numero',
       'cp',
       'precio'
    ];
}
