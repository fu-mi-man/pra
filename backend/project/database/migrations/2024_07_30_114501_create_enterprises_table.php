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
        Schema::create('enterprises', function (Blueprint $table) {
            $table->bigInteger('id')->primary()->comment('出展者ID');
            $table->string('name')->comment('出展者名');
            $table->text('description')->nullable()->comment('説明');
            $table->string('address')->comment('住所');
            $table->string('email')->comment('メールアドレス');
            $table->string('icon')->nullable()->comment('アイコン');
            $table->string('header')->nullable()->comment('ヘッダー');
            $table->boolean('is_published')->default(false);
            $table->string('website')->nullable();
            $table->string('site_id');
            $table->timestamps();

            $table->comment('出展者テーブル');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enterprises');
    }
};
