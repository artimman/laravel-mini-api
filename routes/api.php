<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/**
 * Group routes: /api/pet
 */
Route::prefix('pet')->group(function () {
    Route::get('/', [PetController::class, 'index'])->name('index');
    Route::post('/create', [PetController::class, 'createPet']);
    Route::get('{id}', [PetController::class, 'getPet']);
    Route::put('{id}', [PetController::class, 'updatePet']);
    Route::delete('{id}', [PetController::class, 'deletePet']);
});
