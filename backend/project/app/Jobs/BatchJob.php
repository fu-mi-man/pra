<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BatchJob implements ShouldQueue
{
    use Queueable, Batchable;

    public int $number;

    /**
     * Create a new job instance.
     */
    public function __construct(int $number)
    {
        $this->number = $number;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("バッチジョブ実行！！！ 番号: {$this->number}");
    }
}
