<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\ClientController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

//laravel's home page
Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function () {
    // Home route - only accessible by authenticated users
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Client routes with full CRUD functionality
    Route::resource('clients', ClientController::class);

    // Transaction routes with full CRUD functionality
    Route::resource('transactions', TransactionController::class);
});

Route::group(['namespace' => 'App\Http\Controllers'], function () {
    //add auth to routes
    Auth::routes();
});
