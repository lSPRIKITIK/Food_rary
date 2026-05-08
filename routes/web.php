<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\IngredientController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\CheckAdmin;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RecordController;

Route::get('/', function () {
    return view('Staff.home');
})->name('login');

Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);

// ---------------------------------------------------------
// STANDARD ROUTES: Accessible by both Staff and Admin
// ---------------------------------------------------------
Route::middleware(['auth', PreventBackHistory::class])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/menu', [MenuController::class, 'index']);
    Route::post('/checkout', [MenuController::class, 'checkout']);
    Route::get('/records', [RecordController::class, 'index']);
    Route::get('/ingredients/{id}/history', [App\Http\Controllers\IngredientController::class, 'history']);
    Route::get('/ingredients/{id}/add-stock', [App\Http\Controllers\IngredientController::class, 'addStockForm']);
    Route::post('/ingredients/{id}/add-stock', [App\Http\Controllers\IngredientController::class, 'storeStock']);

});


Route::middleware(['auth', PreventBackHistory::class, CheckAdmin::class])->group(function () {
    //Products
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/create', [ProductController::class, 'create']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{product}/edit', [ProductController::class, 'edit']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
    //Ingredients
    Route::get('/ingredients', [IngredientController::class, 'index']);
    Route::get('/ingredients/create', [IngredientController::class, 'create']);
    Route::post('/ingredients', [IngredientController::class, 'store']);
    Route::get('/ingredients/{id}/edit', [IngredientController::class, 'edit']);
    Route::put('/ingredients/{id}', [IngredientController::class, 'update']);
    Route::delete('/ingredients/{id}', [IngredientController::class, 'destroy']);
    
});