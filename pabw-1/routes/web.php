<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CobaController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\LaporBanjirController;
use App\Http\Controllers\FormRegistrasiSiswaController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return 'Halo, ini adalah halaman Home';
})->name('home.page');

Route::get('/welcome', function () {
    return view ('welcome'); })->name('welcome.page');

Route::get('/coba', [CobaController::class, 'index']); {
};

Route::get('/final', [CobaController::class, 'final']); {
};

Route::get('/hello', function(){
    return 'Hello, Dryhus Dzacky Damingtyas.S';
});


Route::get('/user/{name}', function($name){
    return "Hello $name";
});

Route::get('/user/{name?}', function($name = 'Guest'){
    return "Hello $name";
});

Route::get('/about', function(){
    return view ('about', ['name' => 'Dryhus Dzacky Damingtyas.S']);
});

Route::get('/profile/{name?}', function($name = 'Guest'){
    return view ('profile', ['name' => $name]);
});

Route::get('/form', [DataController::class, 'form']); {
};

Route::post('/proses', [DataController::class, 'proses']); {
};

Route::get('/formMahasiswa', [MahasiswaController::class, 'form']); {
};

Route::post('/prosesMahasiswa', [MahasiswaController::class, 'proses']); {
};

Route::get('/laporbanjir', [LaporBanjirController::class, 'form'])->name('laporbanjir.form');

Route::post('/laporbanjir', [LaporBanjirController::class, 'proses'])->name('laporbanjir.proses');

Route::get('/laporbanjir/konfirmasi', [LaporBanjirController::class, 'konfirmasi'])->name('laporbanjir.konfirmasi');

Route::get('/formregistrasisiswa', [FormRegistrasiSiswaController::class, 'form'])->name('formregistrasisiswa.form');

Route::post('/formregistrasisiswa', [FormRegistrasiSiswaController::class, 'proses'])->name('formregistrasisiswa.proses');

Route::get('/formregistrasisiswa/konfirmasi', [FormRegistrasiSiswaController::class, 'konfirmasi'])->name('formregistrasisiswa.konfirmasi');