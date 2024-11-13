<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservaciones extends Model
{
    use HasFactory;

    protected $table = 'reservaciones';

    protected $fillable = [
      'propiedad_id',
      'huesped_id',
      'fecha_ini',
      'fecha_fin',

    ];
}
