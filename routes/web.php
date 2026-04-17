<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Staff.home');
});

Route::POST('/login', [UserController::class, 'login']);

Route::get('/dashboard', function () {
    return view('Staff.dashboard'); 
});
