<?php

use App\Http\Controllers\web\v1\AuthController;
use App\Http\Controllers\web\v1\CategoryController;
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
    Route::get("count-balance", [HomeController::class, 'balance'])->name('count-balance');

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
        Route::get('save/{id?}', [IncomeController::class, 'getData'])->name('income-get');
        Route::post('save/{id?}', [IncomeController::class, 'save'])->name('income-save');
        Route::post('list', [IncomeController::class, 'list'])->name('income-list');
        Route::get('delete/{id?}', [IncomeController::class, 'delete'])->name('income-delete');
    });

    Route::prefix("transaction")->group(function() {

        // kategori
        Route::prefix("category")->group(function() {
            Route::get('/', [CategoryController::class, 'index'])->name('category');
            Route::get('save/{id?}', [CategoryController::class, 'getData'])->name('category-get');
            Route::post('save/{id?}', [CategoryController::class, 'save'])->name('category-save');
            Route::post('list', [CategoryController::class, 'list'])->name('category-list');
            Route::get('delete/{id?}', [CategoryController::class, 'delete'])->name('category-delete');
        });

        Route::get('/', [TransactionController::class, 'index'])->name('transaction');
        Route::get('save/{id?}', [TransactionController::class, 'getData'])->name('transaction-get');
        Route::post('save/{id?}', [TransactionController::class, 'save'])->name('transaction-save');
        Route::post('list', [TransactionController::class, 'list'])->name('transaction-list');
        Route::get('delete/{id?}', [TransactionController::class, 'delete'])->name('transaction-delete');
    });
});
