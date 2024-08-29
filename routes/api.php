<?php

use App\Http\Controllers\api\userController;
use App\Http\Controllers\api\ProductosController;
use App\Http\Controllers\api\PokemonesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Ruta de prueba
Route::post('hola', function(){
    return 'Hello World!';
});

// Ruta para el login
Route::post('user/login', [UserController::class, 'login']);

Route::post('usuario', [userController::class, 'create']);

Route::group(['middleware' => ['auth:sanctum']], function() {

    // Rutas para el usercontroller
    Route::prefix('usuario')->group(function() {
            // Ruta para obtener todos los usuarios
        Route::get('', [userController::class, 'index']);
            // Ruta para crear un nuevo usuario
        Route::post('', [userController::class, 'create']);
            // Ruta para mostrar un usuario por ID
        Route::get('/{id}', [userController::class, 'show'])->where('id', '[0-9]+');
            // Ruta para actualizar un usuario por ID
        Route::patch('/{id}', [userController::class, 'update'])->where('id', '[0-9]+');
            // Ruta para eliminar un usuario por ID
        Route::delete('/{id}', [userController::class, 'destroy'])->where('id', '[0-9]+');
    });

    // Rutas para el pokemoncontroller
    Route::prefix('pokemon')->group(function() {
            // Ruta para obtener todos los Pokemones
        Route::get('', [pokemonescontroller::class, 'index']);
            // Ruta para crear un nuevo Pokemon
        Route::post('', [pokemonescontroller::class, 'store']);
            // Ruta para mostrar un Pokemon por ID
        Route::get('/{id}', [pokemonescontroller::class, 'show'])->where('id', '[0-9]+');
            // Ruta para actualizar un Pokemon por ID
        Route::patch('/{id}', [pokemonescontroller::class, 'update'])->where('id', '[0-9]+');
            // Ruta para eliminar un Pokemon por ID
        Route::delete('/{id}', [pokemonescontroller::class, 'destroy'])->where('id', '[0-9]+');
    });
});

// Obtener la autenticacion del usuario
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
