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
        Schema::create('enterprise_customer_users', function (Blueprint $table) {
            $table->unsignedBigInteger('enterprise_id')->comment('出展者ID');
            $table->unsignedBigInteger('user_id')->comment('ユーザID');
            $table->unsignedBigInteger('business_card_id')->nullable()->comment('名刺ID');
            $table->timestamps();

            $table->comment('出展者カスタマーユーザテーブル（顧客データ）');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enterprise_customer_users');
    }
};
