<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;

Route::post('/reset', [AccountController::class, 'reset']);
Route::post('/event', [AccountController::class, 'event']);
Route::get('/balance', [AccountController::class, 'balance']);

