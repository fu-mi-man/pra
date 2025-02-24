<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexCategoryRequest;
use App\Http\Requests\Api\UpdateCategoryRequest;
use App\Http\Requests\Api\DeleteCategoryRequest;
use App\Http\Requests\Api\StoreCategoryRequest;
use App\Http\Requests\Api\UpdateCategoryOrderRequest;
use App\Models\Category;
use Exception;
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
        // catalogとproductのカテゴリーを取得
        $categories = Category::groupByType($request->validated('enterprise_id'));

        return response()->json([
            'catalog' => $categories['catalog'] ?? [],
            'product' => $categories['product'] ?? [],
        ]);
    }

    /**
     * 新しいカテゴリーを作成
     *
     * @param StoreCategoryRequest $request
     * @return JsonResponse
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        // display_orderの計算（同じtype,enterprise_idの最大値+1）
        $maxOrder = Category::where('type', $validatedData['type'])
            ->where('enterprise_id', $validatedData['enterprise_id'])
            ->max('display_order') ?? 0;

        $validatedData['display_order'] = $maxOrder + 1;

        $category = Category::create($validatedData);

        return response()->json($category, 201); // 201 Created
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

    public function updateOrder(UpdateCategoryOrderRequest $request): JsonResponse
    {
        $enterpriseId = $request->validated('enterprise_id');
        $type = $request->validated('type');
        $categories = $request->validated('categories');

        $categoryIds = array_column($categories, 'id');
        $existingCategories = Category::whereIn('id', $categoryIds)
            ->where('enterprise_id', $enterpriseId)
            ->where('type', $type)
            ->get()
            ->keyBy('id');

        $updatedCategories = [];
        foreach ($categories as $category) {
            $existingCategory = $existingCategories[$category['id']];

            // Upsertに必要なフィールドをすべて含める
            $updatedCategory = [
                'id'            => $category['id'],
                'name'          => $existingCategory->name,
                'type'          => $existingCategory->type, // DBから取得した値を使用
                'enterprise_id' => $existingCategory->enterprise_id, // DBから取得した値を使用
                'display_order' => $category['display_order'], // リクエストから取得した新しい表示順
            ];

            $updatedCategories[] = $updatedCategory;
        }

        // 新規で作成する場合も考慮しないといけないので，このケースでは適していないがいったんupsertを使用する
        Category::upsert(
            $updatedCategories,
            ['id'],
            ['display_order']
        );
        // 下記だとクエリが複数回発行されるので，upsertにして発行回数を1回にする
        // DB::transaction(function () use ($request) {
        //     foreach ($request->categories as $category) {
        //         Category::where('id', $category['id'])
        //             ->update(['display_order' => $category['display_order']]);
        //     }
        // });

        return response()->json(['message' => '並び順を更新しました']);
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
