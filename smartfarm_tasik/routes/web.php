<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MonitoringController;

Route::get('/', [MonitoringController::class, 'home'])->name('home');
Route::get('/monitoring', [MonitoringController::class, 'create'])->name('monitoring');
Route::post('/monitoring', [MonitoringController::class, 'store'])->name('monitoring.store');
Route::get('/riwayat', [MonitoringController::class, 'riwayat'])->name('riwayat');
Route::get('/tentang', [MonitoringController::class, 'tentang'])->name('tentang');
