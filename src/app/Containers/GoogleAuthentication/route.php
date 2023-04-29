<?php

use App\Containers\GoogleAuthentication\Controller;
use Illuminate\Support\Facades\Route;


Route::prefix('auth/google')->group(function(){
    Route::get('/url',[Controller::class,'loginurl']);
    Route::get('/oauth2callback',[Controller::class,'loginCallback']);
});
