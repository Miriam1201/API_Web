<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí se registran las rutas web para tu aplicación.
| Estas rutas están cargadas por el RouteServiceProvider y pertenecen
| al grupo de middleware "web".
|
*/

// Ruta de inicio, redirigir al login si no está autenticado, de lo contrario a comunidad
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('comunidad');
    }
    return view('auth.login');
})->name('inicio');

// Grupo de rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    // Rutas para el perfil del usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Ruta para la comunidad
    Route::get('/comunidad', [PostController::class, 'index'])->name('comunidad');
    Route::post('/comunidad', [PostController::class, 'store'])->name('community.store');
});

require __DIR__ . '/auth.php';
