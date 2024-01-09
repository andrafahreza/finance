<?php

use App\Http\Controllers\web\v1\AuthController;
use App\Http\Controllers\web\v1\HomeController;
use App\Http\Controllers\web\v1\IncomeController;
use App\Http\Controllers\web\v1\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get("/", [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::post("/", [AuthController::class, 'auth'])->name('authenticate');

Route::middleware('auth')->group(function() {
    Route::get("logout", [AuthController::class, 'logout'])->name('logout');
    Route::get("home", [HomeController::class, 'index'])->name('home');

    Route::prefix("income")->group(function() {
        Route::get('/', [IncomeController::class, 'index'])->name('income');
    });

    Route::prefix("transaction")->group(function() {
        Route::get('/', [TransactionController::class, 'index'])->name('transaction');
    });
});
