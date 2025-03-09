<?php

use App\Enums\CategoryType;
use App\Exports\Report;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChartOfAccountController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/export', 'exports.report');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // category routes
    Route::prefix('categories')->group(function () {

        Route::get('/', [CategoryController::class, 'index'])->name('category.index');
        Route::get('/create', [CategoryController::class, 'create'])->name('category.create');
        Route::get('/edit/{category}', [CategoryController::class, 'edit'])->name('category.edit');
        Route::post('/', [CategoryController::class, 'store'])->name('category.store');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('category.update');
        Route::delete('/{name}', [CategoryController::class, 'destroy'])->name('category.destroy');
    });

    // coa routes
    Route::prefix('coa')->group(function () {

        Route::get('/', [ChartOfAccountController::class, 'index'])->name('coa.index');
        Route::get('/create', [ChartOfAccountController::class, 'create'])->name('coa.create');
        Route::get('/edit/{chartOfAccount}', [ChartOfAccountController::class, 'edit'])->name('coa.edit');
        Route::post('/', [ChartOfAccountController::class, 'store'])->name('coa.store');
        Route::put('/{chartOfAccount}', [ChartOfAccountController::class, 'update'])->name('coa.update');
        Route::delete('/{code}', [ChartOfAccountController::class, 'destroy'])->name('coa.destroy');
    });


    // transaction routes
    Route::prefix('transactions')->group(function () {

        Route::get('/', [TransactionController::class, 'index'])->name('transaction.index');
        Route::get('/create', [TransactionController::class, 'create'])->name('transaction.create');
        Route::get('/edit/{transaction}', [TransactionController::class, 'edit'])->name('transaction.edit');
        Route::post('/', [TransactionController::class, 'store'])->name('transaction.store');
        Route::put('/{transaction}', [TransactionController::class, 'update'])->name('transaction.update');
        Route::delete('/{id}', [TransactionController::class, 'destroy'])->name('transaction.destroy');
    });
    
    Route::get('/export/download', [ReportController::class, 'export'])->name('report.export');
});

require __DIR__ . '/auth.php';
