<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WalletController;

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

Route::group(['prefix' => 'v1'], function(){
//ADMIN ROUTES
    Route::group(['middleware' => ['auth:sanctum', 'admin']], function() {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/transfer', [WalletController::class, 'transfer']);
    });

    //AUTHENTICATED ROUTES
    Route::group(['middleware' => ['auth:sanctum']], function() {
        Route::get('/profile', [AuthController::class, 'me']);
        Route::get('/getwallet', [WalletController::class, 'getWallet']);
        Route::get('/gethistory', [WalletController::class, 'transfer_history']);
        Route::put('/editprofile', [AuthController::class, 'edit']);
    });

    Route::post('/login', [AuthController::class, 'login']);
});


