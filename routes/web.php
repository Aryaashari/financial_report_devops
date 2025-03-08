<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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
});

require __DIR__ . '/auth.php';
