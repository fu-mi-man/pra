<?php

namespace App\Jobs;

use App\Models\Enterprise;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BulkDeleteSharedRequestsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;
    // Dispatchable: ジョブをキューに投入するためのトレイト
    // InteractsWithQueue: キューでのジョブ再試行や例外処理のためのトレイト
    // Queueable: キュー設定を管理するトレイト

    /**
     * 削除対象のリクエストIDの配列
     *
     * @var array
     */
    protected $enterpriseIds;

    /**
     * ジョブの実行可能な時間（秒）
     */
    public $timeout = 1800; // 30分

    /**
     * ジョブ試行回数の最大値
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @param array $enterpriseIds 削除対象のリクエストIDの配列
     * @return void
     */
    public function __construct(array $enterpriseIds)
    {
        $this->enterpriseIds = $enterpriseIds;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('一括削除処理を開始します。対象件数: ' . count($this->enterpriseIds));
        $chunks = array_chunk($this->enterpriseIds, 1000);
        foreach ($chunks as $chunk) {
            try {
                $this->deleteSharedRequestsWithRelations($chunk);
                Log::info("商品バッチ削除完了: " . count($chunk) . "件");
                sleep(1); // スリープを入れてDBの負荷を分散
            } catch (\Exception $e) {
                Log::error("商品バッチ削除失敗: " . $e->getMessage());
                // このチャンクは失敗したが次のチャンクに進む
            }
        }
    }

    /**
     * 指定された共有リクエストIDに関連する全てのレコードを削除する
     *
     * @param array $enterpriseIds
     * @return void
     */
    protected function deleteSharedRequestsWithRelations(array $enterpriseIds)
    {
        DB::beginTransaction();
        try {
            // 他の関連テーブルを削除する処理（外部キー制約）
            // 最後にリクエストを削除
            Enterprise::whereIn('id', $enterpriseIds)->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
