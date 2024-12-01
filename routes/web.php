<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/community', [PostController::class, 'index'])->name('community.index');
Route::post('/community/store', [PostController::class, 'store'])->name('community.store');
Route::get('/community/create/text', [PostController::class, 'createText'])->name('posts.createText');
Route::get('/community/create/images', [PostController::class, 'createImages'])->name('posts.createImages');
Route::get('/community/create/video', [PostController::class, 'createVideo'])->name('posts.createVideo');
