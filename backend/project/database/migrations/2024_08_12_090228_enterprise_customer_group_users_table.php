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
        Schema::create('enterprise_customer_group_users', function (Blueprint $table) {
            $table->unsignedBigInteger('enterprise_customer_group_id')->comment('出展者顧客グループID');
            $table->unsignedBigInteger('user_id')->comment('ユーザID');
            $table->timestamps();

            $table->comment('顧客グループユーザテーブル');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enterprise_customer_group_users');
    }
};
