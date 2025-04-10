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

        // 大量データでのクエリ最適化
        $enterprises = Enterprise::query()
            ->select('id', 'name')
            ->orderBy('id', 'asc')
            ->paginate($perPage);

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
