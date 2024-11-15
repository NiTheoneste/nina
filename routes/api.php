<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//route pour l'auth et gestion du parametre utilisateur
Route::prefix('user')->group(function () {
    Route::post('login', [UserController::class, 'login']);
    Route::post('register', [UserController::class, 'store']); // Correction ici
    Route::middleware('auth:sanctum')->post('logout', [UserController::class, 'logout']);
});


Route::middleware('auth:sanctum')->group(function(){

    Route::apiResource('users', App\Http\Controllers\UserController::class);

    Route::apiResource('roles', App\Http\Controllers\RoleController::class);

    Route::apiResource('clients', App\Http\Controllers\ClientController::class);

    Route::apiResource('products', App\Http\Controllers\ProductController::class);

    Route::apiResource('categories', App\Http\Controllers\CategoryController::class);

    Route::apiResource('orders', App\Http\Controllers\OrderController::class);

    Route::apiResource('debts', App\Http\Controllers\DebtController::class);
});
