<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('huespedes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido paterno');
            $table->string('apellido materno')->nullable();
            $table->integer('codigo del pais');
            $table->integer('telefono');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('huespedes');
    }
};
