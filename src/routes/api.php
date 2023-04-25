<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdsGroupController;
use App\Http\Controllers\AdsController;
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
require_once base_path()."/app/Containers/GoogleAuthentication/route.php";


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::prefix('auth/google')->group(function(){
//    Route::get('/url',[GoogleController::class,'loginurl']);
//    Route::get('/oauth2callback',[GoogleController::class,'loginCallback']);
//});

Route::resource('customer',CustomerController::class);

Route::get('account/{customerId}',[AccountController::class,'listingAccount']);

Route::resource('campaign',CampaignController::class)->middleware(['google.ads.auth']);

Route::resource('ads-group',AdsGroupController::class);

Route::resource('ads',AdsController::class);
