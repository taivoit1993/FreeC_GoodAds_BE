<?php
use App\Containers\Ad\Controller;
use Illuminate\Support\Facades\Route;

Route::resource('ads',Controller::class) ->middleware(['google.ads.auth']);;
