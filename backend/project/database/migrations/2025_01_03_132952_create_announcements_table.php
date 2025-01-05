<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id()->comment('主キー');
            $table->string('title', 30)->comment('お知らせタイトル');
            $table->text('content')->comment('お知らせ本文');
            $table->enum('category', ['notice', 'maintenance', 'update'])->comment('お知らせカテゴリ');
            $table->boolean('is_published')->default(true)->comment('公開状態');
            $table->datetime('start_at')->comment('公開開始日時');
            $table->datetime('end_at')->nullable()->comment('公開終了日時');
            $table->boolean('show_banner')->default(false)->comment('バナー表示フラグ');
            $table->datetime('banner_start_at')->nullable()->comment('バナー表示開始日時');
            $table->datetime('banner_end_at')->nullable()->comment('バナー表示終了日時');
            $table->boolean('send_notification')->default(false)->comment('メール通知送信フラグ');
            $table->boolean('published_mail_sent')->default(false)->comment('メール送信完了フラグ');
            $table->timestamps();  // created_at, updated_at

            $table->comment('お知らせ管理テーブル');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
