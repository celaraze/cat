<?php

use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SanctumController;
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

Route::post('/auth/login', [SanctumController::class, 'login']);

Route::middleware('auth:sanctum')
    ->post('/inventory/check', [InventoryController::class, 'check']);

Route::middleware('auth:sanctum')
    ->get('/inventory', [InventoryController::class, 'inventory']);
