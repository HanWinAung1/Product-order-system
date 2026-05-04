<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return response()->json(['message' => 'Please log in to access the dashboard.'], 401);
})->name('login');
Route::view('/{any?}', 'welcome')->where('any', '.*');
