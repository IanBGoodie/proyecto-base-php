<?php

use App\Http\Controllers\Web\UsersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\RolesController;

Route::get('ping',function(){
    return response()->json(['result' => 'ok']);
});


Route::get('usuarios/roles', [UsersController::class, 'roles'])->name('usuarios.roles');
Route::resource('usuarios', UsersController::class, ['except' => ['edit', 'create']]);
Route::resource('roles', RolesController::class, ['except' => ['edit', 'create']]);
