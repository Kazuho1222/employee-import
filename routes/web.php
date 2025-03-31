<?php

use Illuminate\Support\Facades\Route;

// app.blade.phpを返す
Route::get('/', function () {
    return view('app');
})->name('home');