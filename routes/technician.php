<?php

use App\Http\Controllers\Technician\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('technician')->name('technician.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/work-orders/{workOrder}', [DashboardController::class, 'show'])->name('work-orders.show');
    Route::post('/work-orders/{workOrder}/update-progress', [DashboardController::class, 'updateProgress'])->name('work-orders.update-progress');
});
