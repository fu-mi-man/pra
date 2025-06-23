<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\BatchJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('バッチ処理開始');

        $jobs = [];
        for ($i = 1; $i <= 5; $i++) {
            $jobs[] = new BatchJob($i); // パラメータを渡す
        }

        // バッチでディスパッチ
        Bus::batch($jobs)
            ->then(function () {
                Log::info('全てのバッチジョブが完了！');
            })
            ->dispatch();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
