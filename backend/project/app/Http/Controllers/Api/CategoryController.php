<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexCategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * 指定された出展者IDのカテゴリー一覧を取得
     *
     * @param IndexCategoryRequest $request
     * @return JsonResponse
     */
    public function index(IndexCategoryRequest $request): JsonResponse
    {
        // documentとproductのカテゴリーを取得
        $categories = Category::groupByType($request->validated('enterprise_id'));

        return response()->json([
            'document' => $categories['document'] ?? [],
            'product' => $categories['product'] ?? [],
        ]);
    }
}
