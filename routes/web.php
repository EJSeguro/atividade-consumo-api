<?php

use App\Http\Controllers\FipeController;
use Illuminate\Support\Facades\Route;

Route::controller(FipeController::class)->name('fipe.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/tipos/{type}', 'brands')->whereNumber('type')->name('brands');
    Route::get('/marcas/{brand}', 'models')->whereNumber('brand')->name('models');
    Route::get('/modelos/{fipeCode}', 'years')->name('years');
    Route::get('/modelos/{fipeCode}/anos/{yearId}', 'history')->name('history');
});
