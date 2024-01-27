<?php

use App\Http\Controllers\web\v1\AuthController;
use App\Http\Controllers\web\v1\HomeController;
use App\Http\Controllers\web\v1\IncomeController;
use App\Http\Controllers\web\v1\SourceController;
use App\Http\Controllers\web\v1\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get("/", [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::post("/", [AuthController::class, 'auth'])->name('authenticate');

Route::middleware('auth')->group(function() {
    Route::get("logout", [AuthController::class, 'logout'])->name('logout');
    Route::get("home", [HomeController::class, 'index'])->name('home');

    Route::prefix("income")->group(function() {

        // sumber dana
        Route::prefix("source")->group(function() {
            Route::get('/', [SourceController::class, 'index'])->name('source');
            Route::get('save/{id?}', [SourceController::class, 'getData'])->name('source-get');
            Route::post('save/{id?}', [SourceController::class, 'save'])->name('source-save');
            Route::post('list', [SourceController::class, 'list'])->name('source-list');
            Route::get('delete/{id?}', [SourceController::class, 'delete'])->name('source-delete');
        });

        Route::get('/', [IncomeController::class, 'index'])->name('income');
    });

    Route::prefix("transaction")->group(function() {
        Route::get('/', [TransactionController::class, 'index'])->name('transaction');
    });
});
