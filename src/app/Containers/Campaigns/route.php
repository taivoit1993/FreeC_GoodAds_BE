<?php

use App\Containers\Campaigns\Controller;
use Illuminate\Support\Facades\Route;

Route::resource('campaign',Controller::class)
    ->middleware(['google.ads.auth']);
