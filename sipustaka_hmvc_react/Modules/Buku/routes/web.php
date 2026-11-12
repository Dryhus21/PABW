<?php

use Illuminate\Support\Facades\Route;
use Modules\Buku\Http\Controllers\BukuController;

Route::middleware(['auth'])->group(function () {
    Route::resource('buku', BukuController::class)
        ->only('index')
        ->names('buku');

    Route::resource('buku', BukuController::class)
        ->except(['index', 'show'])
        ->names('buku')
        ->middleware('role:admin');
});
