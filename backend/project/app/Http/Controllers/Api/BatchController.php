<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\BatchJob;
use App\Mail\BatchCompletedMail;
use Illuminate\Bus\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

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
            ->name('BATCHNAME!!!')
            ->allowFailures(false) // デフォルトfalse（バッチがキャンセル扱い＆完了時間挿入）
            ->then(function (Batch $batch) {
                Log::info('ジョブの中でエラーが発生すればここには入りません');
                Log::info('ジョブが全て成功すればここに入ります');
                // バッチレコードを削除
                // $batch->delete();
                // Log::info('バッチレコードを削除しました', ['batch_id' => $batch->id]);
            })
            ->catch(function (Batch $batch, Throwable $e) {
                // 最初の失敗検知時に1回だけ実行
                // $eは最初に失敗したジョブの例外
                Log::error('バッチで失敗発生', [
                    'error' => $e->getMessage(),
                    'failed_jobs' => $batch->failedJobs,
                ]);
            })
            ->finally(function (Batch $batch) {
                Log::info('メール送信します');
                Mail::to('admin@example.com')->send(new BatchCompletedMail());
                Log::info('メール送信しました');
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
