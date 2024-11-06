<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Ruta para listar todas las publicaciones
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

// Ruta para obtener una publicación específica
Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');

// Ruta para crear una nueva publicación
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

// Ruta para actualizar una publicación específica
Route::put('/posts/{id}', [PostController::class, 'update'])->name('posts.update');

// Ruta para eliminar una publicación específica
Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');

// Si necesitas rutas adicionales como buscar publicaciones por ciertos criterios
Route::get('/posts/search', [PostController::class, 'search'])->name('posts.search');
