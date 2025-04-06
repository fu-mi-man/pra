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
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        return response()->json($enterprises);
    }
}
