<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexCategoryRequest;
use App\Http\Requests\Api\UpdateCategoryRequest;
use App\Http\Requests\Api\DeleteCategoryRequest;
use App\Models\Category;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

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
        // catalogとproductのカテゴリーを取得
        $categories = Category::groupByType($request->validated('enterprise_id'));

        return response()->json([
            'catalog' => $categories['catalog'] ?? [],
            'product' => $categories['product'] ?? [],
        ]);
    }

    /**
     * 指定されたカテゴリーを更新
     *
     * @param UpdateCategoryRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateCategoryRequest $request, int $id): JsonResponse
    {
        $category = Category::findOrFail($id);
        // Log::info($category);

        $category->update($request->validated());

        return response()->json($category);
    }

    /**
     * 指定されたカテゴリーを削除
     *
     * @param DeleteCategoryRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(DeleteCategoryRequest $request, int $id): JsonResponse
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(null, 204);
    }
}
