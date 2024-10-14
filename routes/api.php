<?php

use Illuminate\Http\Request;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// For fetching authenticated user details via API
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Group all routes under auth:api for secured API access
Route::middleware('auth:api')->group(function () {

    // Clients Routes with full CRUD functionality (for Vue.js)
    Route::get('/clients', [ClientController::class, 'getClients']);
    Route::get('/clients/all', [ClientController::class, 'getAllClients']);
    Route::post('/clients', [ClientController::class, 'store']);
    Route::get('/clients/{id}', [ClientController::class, 'show']);
    Route::put('/clients/{id}', [ClientController::class, 'update']);
    Route::delete('/clients/{id}', [ClientController::class, 'destroy']);

    // Transactions Routes with full CRUD functionality (for Vue.js)
    Route::get('/transactions', [TransactionController::class, 'getTransactions']);
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::get('/transactions/{id}', [TransactionController::class, 'show']);
    Route::put('/transactions/{id}', [TransactionController::class, 'update']);
    Route::delete('/transactions/{id}', [TransactionController::class, 'destroy']);
});

