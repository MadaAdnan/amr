<?php

use App\Http\Controllers\Chauffeur\AuthController;
use Illuminate\Support\Facades\Route;

#authorization apis 
Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
  
});