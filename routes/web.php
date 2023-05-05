<?php

use App\Http\Controllers\NutricionalController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [NutricionalController::class, 'dadosApi']);
Route::prefix('/products')->group(function () {
    Route::get('', [NutricionalController::class, 'listar']);
    Route::get('/{code}', [NutricionalController::class, 'buscar']);
    Route::put('/{code}', [NutricionalController::class, 'atualizar']);
    Route::delete( '/{code}', [NutricionalController::class, 'deletar']);
});
