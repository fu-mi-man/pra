<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SchedulerTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scheduler:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'スケジューラーのテスト用コマンド';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $this->infoはログローテートができないので不作用
        // $this->info('スケジューラーテスト開始');
        Log::info('普通のログ！', [
            'timestamp' => now(),
            'command' => $this->signature
        ]);

        try {
            // ローカルAPIを叩く例
            // $response = Http::get('http://web/api/batch');

            // Log::info('API呼び出し完了', [
            //     'status' => $response->status(),
            //     'response_size' => strlen($response->body())
            // ]);

            $this->info('スケジューラーテスト完了');
        } catch (Exception $e) {
            Log::error('スケジューラーテストでエラー発生', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->error('スケジューラーテストでエラー: ' . $e->getMessage());
            return 1; // エラー終了
        }

        return 0; // 正常終了（Artisanコマンドの終了コード）
    }
}
