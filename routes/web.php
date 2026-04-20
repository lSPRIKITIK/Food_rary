<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Staff.home');
})->name('login');

Route::POST('/login', [UserController::class, 'login']);
Route::POST('/logout', [UserController::class, 'logout']);

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', \App\Http\Middleware\PreventBackHistory::class]);