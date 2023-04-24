<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\AccountController;
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

Route::prefix('auth/google')->group(function(){
    Route::get('/url',[GoogleController::class,'loginurl']);
    Route::get('/oauth2callback',[GoogleController::class,'loginCallback']);
});

Route::resource('customer',CustomerController::class);

Route::get('account/{customerId}',[AccountController::class,'listingAccount']);

Route::post('campaign',[CampaignController::class,"store"]);

Route::get('campaign',[CampaignController::class,"index"]);
