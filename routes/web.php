<?php

use App\Http\Controllers\Cashier\CashierProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

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
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    
});

Route::middleware(['auth', 'role:cashier'])->name('cashier.')->prefix('cashier')->group(function () {
    Route::get('products', [CashierProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [CashierProductController::class, 'create'])->name('products.create');
    Route::post('products', [CashierProductController::class, 'store'])->name('products.store');
    Route::get('products/{product}/edit', [CashierProductController::class, 'edit'])->name('products.edit');
    Route::put('products/{product}', [CashierProductController::class, 'update'])->name('products.update');
    Route::delete('products/{product}', [CashierProductController::class, 'destroy'])->name('products.destroy');
});

require __DIR__.'/auth.php';
