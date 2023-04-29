<?php

use Illuminate\Support\Facades\Route;
use App\Containers\AdGroup\Controller;

Route::resource('ads-group', Controller::class)
    ->middleware(['google.ads.auth']);
