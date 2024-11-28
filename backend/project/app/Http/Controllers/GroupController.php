<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
// use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index(): JsonResponse
    {
        for ($i = 1; $i <= 100; $i++) {
            $groups[] = [
                'id' => $i,
                'groupName' => 'グループテスト30文字グループテスト30文字グループテス30' . $i,
                'memberCount' => $i,  // 1-10人でランダム
                'remarks' => '50文字入る備考50文字入る備考50文字入る備考50文字入る備考50文字入る備考50文字入る備考５０'
            ];
        }
        // sleep(1);

        return response()->json($groups);
    }
}
