<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enterprise;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnterpriseController extends Controller
{
    /**
     * 出展者一覧を取得
     *
     * @param  $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page');
        $searchKeyword = $request->input('search');

        $query = Enterprise::query()
            ->select('id', 'name');

        // 検索キーワードがある場合、検索条件を追加
        if ($searchKeyword) {
            // 商品名で検索
            $query->where('name', 'like', "%{$searchKeyword}%");

            // 将来的に検索対象を拡張する場合の例：
            // $query->orWhere('source', 'like', "%{$searchKeyword}%");
        }

        // 将来的に他の絞り込み条件を追加する場合はここに追加
        // if ($request->has('status')) {
        //     $query->where('status', $request->input('status'));
        // }
        // ソートとページネーション
        $enterprises = $query
            ->orderBy('id', 'asc')
            ->paginate($perPage); // paginateメソッドは自動的にリクエストからpageクエリパラメータを取得して使用

        return response()->json($enterprises);
    }

    /**
     * 指定された出展者を削除
     *
     * @param DeleteEnterpriseRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $enterprise = Enterprise::findOrFail($id);
        $enterprise->delete();

        return response()->json(null, 204);
    }
}
