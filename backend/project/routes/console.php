<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// スケジューラー定義を追加
Schedule::command('scheduler:test')
    ->everyMinute()         // 1分ごとに1回実行
    // ->hourly()           // 1時間に1回実行
    // ->daily()            // 毎日0時に1回実行
    // ->everyFiveMinutes() // 5分ごとに1回実行
    ->withoutOverlapping()  // 前回の実行が完了していない場合はスキップ
    ->runInBackground()     // バックグラウンドで実行
    ->appendOutputTo(storage_path('logs/scheduler-test.log')); // エラーだけログ出力
