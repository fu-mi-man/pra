<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // `SHOW CREATE TABLE images\G`と`show index from images\G` で現在のテーブル状況を確認しておく
        // `sequence_id`カラム追加が暗黙的に先に実行され，主キーの重複エラーとなるためマイグレーションを分割する
        Schema::table('images', function (Blueprint $table) {
            $table->dropPrimary('id');
            $table->unique('id', 'images_id_unique'); // 主キーを外すと非ユニークインデックスになるためユニークを設定
        });
        Schema::table('images', function (Blueprint $table) {
            $table->string('id')->comment('S3 Path')->change();

            // sequence_id, user_id, typeを追加
            $table->bigIncrements('sequence_id')->first()->comment('シーケンスID');
            $table->integer('user_id')->after('sequence_id')->comment('ユーザID');
            $table->string('type')->after('user_id')->default('')->comment('画像の種類：product, catalog, kiritori, user, other');

            $table->index(['sequence_id', 'user_id'], 'idx_sequence_id_user_id'); // 複合インデックス
        });

        // 既存のデータに対してuser_idを設定
        $this->updateUserIds();
        // DB::statement("ALTER TABLE images ADD CONSTRAINT check_image_type CHECK (type IN ('', 'product', 'catalog', 'kiritori', 'user', 'other'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('images', function (Blueprint $table) {
            // 複合インデックス削除
            $table->dropIndex('idx_sequence_id_user_id');
            // カラムを削除
            $table->dropColumn('type');
            $table->dropColumn('user_id');
            $table->dropColumn('sequence_id');

            $table->dropUnique('images_id_unique');
            $table->primary('id'); // idを再度主キーに設定（ユニークインデックス）
        });

        // DB::statement("ALTER TABLE images DROP CONSTRAINT IF EXISTS check_image_type");
    }

    private function updateUserIds(): void
    {
        DB::table('images')->orderBy('id')->chunk(1000, function ($images) {
            foreach ($images as $image) {
                $parts = explode('/', $image->id);
                Log::debug($parts);
                if (count($parts) >= 3) {
                    DB::table('images')
                        ->where('id', $image->id)
                        ->update([
                            'user_id' => $parts[2],
                            'name' => 'testtest',
                            'type' => $image->type ?? 'other',
                        ]);
                }
            }
        });
        // DB::table('images')->orderBy('id')->chunk(1000, function ($images) {
        //     $updates = [];
        //     foreach ($images as $image) {
        //         $parts = explode('/', $image->id);
        //         if (count($parts) >= 3) {
        //             $updates[] = [
        //                 'id' => $image->id,
        //                 'user_id' => $parts[2],
        //                 'name' => 'testtest',
        //             ];
        //         }
        //     }
        //     DB::table('images')->upsert($updates, ['id'], ['user_id']);
        // });
    }
};
