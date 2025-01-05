<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\PlaysController;

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    // Admin Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Plays Management
    Route::get('/plays', [PlaysController::class, 'index'])->name('admin.plays.index');
    
    // Transaction Management
    Route::get('/transactions', [TransactionController::class, 'index'])->name('admin.transactions.index');
    Route::post('/transactions/{transaction}/approve', [TransactionController::class, 'approve'])->name('admin.transactions.approve');
    Route::post('/transactions/{transaction}/reject', [TransactionController::class, 'reject'])->name('admin.transactions.reject');
    
    // User Management
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/{user}/plays', [AdminController::class, 'userPlays'])->name('admin.users.plays');
    Route::get('/users/{user}/transactions', [AdminController::class, 'userTransactions'])->name('admin.users.transactions');
    Route::post('/users/{user}/status', [AdminController::class, 'updateUserStatus'])->name('admin.users.status');
    Route::post('/users/{user}/ban', [AdminController::class, 'banUser'])->name('admin.users.ban');
});
