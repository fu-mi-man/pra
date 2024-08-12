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
        Schema::create('business_cards', function (Blueprint $table) {
            $table->id('business_card_id')->comment('名刺id');
            $table->string('name')->nullable()->comment('名前');
            $table->string('company')->nullable()->comment('法人名');
            $table->string('position')->nullable()->comment('肩書き');
            $table->string('department')->nullable()->comment('事業部');
            $table->string('address')->nullable()->comment('住所');
            $table->string('phone_number')->nullable()->comment('電話番号');
            $table->string('email')->nullable()->comment('メールアドレス');
            $table->string('memo')->nullable()->comment('メモ');
            $table->timestamps();

            $table->comment('名刺テーブル');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_cards');
    }
};
