<?php

use App\Http\Controllers\CSVValidationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/hello', function() {
    return 'Hello!';
});

Route::post('/validate-csv', [CSVValidationController::class, 'validate']);
