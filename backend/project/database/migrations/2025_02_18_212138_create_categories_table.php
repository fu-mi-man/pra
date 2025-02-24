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
        Schema::create('categories', function (Blueprint $table) {
            // テーブル全体の照合順序を設定
            $table->collation = 'utf8mb4_bin'; // nameで大文字小文字が区別つかなかった場合

            $table->id();
            $table->unsignedBigInteger('enterprise_id');
            $table->enum('type', ['catalog', 'product']);
            $table->string('name', 30);
            $table->integer('display_order')->default(0);
            $table->timestamps();

            // 単一カラムインデックス（最も頻繁な検索パターン）
            $table->index('enterprise_id', 'categories_enterprise_id_idx');
            // 複合ユニーク制約
            $table->unique(
                ['enterprise_id', 'type', 'name'],
                'categories_enterprise_id_type_name_unq'
            );
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
