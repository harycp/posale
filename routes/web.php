<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Cashier\CashierPOSController;
use App\Http\Controllers\Cashier\CashierCartController;
use App\Http\Controllers\Cashier\CashierProductController;
use App\Http\Controllers\Cashier\CashierTransactionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    
    if ($role === 'cashier') {
        return redirect()->route('cashier.pos.index');
    }

    if ($role === 'admin') {
        return view('dashboard');
    }
    
    // Fallback jika ada role lain
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
    
    Route::get('pos', [CashierPOSController::class, 'index'])->name('pos.index');
    
    Route::get('cart', [CashierCartController::class, 'index'])->name('cart.index');
    Route::post('cart/add', [CashierCartController::class, 'add'])->name('cart.add');
    Route::patch('cart/update/{id}', [CashierCartController::class, 'update'])->name('cart.update');
    Route::delete('cart/destroy/{id}', [CashierCartController::class, 'destroy'])->name('cart.destroy');

    Route::post('transactions/store', [CashierTransactionController::class, 'store'])->name('transactions.store');
});

require __DIR__.'/auth.php';
