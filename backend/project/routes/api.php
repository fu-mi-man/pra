<?php

use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Api\BatchController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\EnterpriseController;
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

// バッチ
Route::post('/batch', [BatchController::class, 'store']);

// 出展者一覧
Route::get('/enterprises', [EnterpriseController::class, 'index']);
Route::delete('/enterprises/{id}', [EnterpriseController::class, 'destroy']);

// お知らせのAPIルート
Route::apiResource('announcements', AnnouncementController::class);
// カテゴリのAPI
Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::put('categories/order', [CategoryController::class, 'updateOrder']);
Route::put('/categories/{id}', [CategoryController::class, 'update']);
Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
// REST APIの標準的なCRUD操作との整合性
// GET    /categories     - 一覧取得
// POST   /categories     - 作成
// GET    /categories/1   - 個別取得
// PUT    /categories/1   - 更新
// DELETE /categories/1   - 削除
