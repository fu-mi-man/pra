<?php

use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\CSVValidationController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\UploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// http://localhost/api/hello
Route::get('/hello', function() {
    return response()->json(['message' => 'Hello!']);
});

Route::get('/groups', [GroupController::class, 'index']);
Route::post('/validate-csv', [CSVValidationController::class, 'validate']);
Route::post('/upload', [UploadController::class, 'upload']);

// お知らせのAPIルート
Route::apiResource('announcements', AnnouncementController::class);
