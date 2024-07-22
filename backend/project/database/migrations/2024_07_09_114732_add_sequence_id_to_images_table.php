<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ALTER TABLE images DROP COLUMN sequence_id;
        // ALTER TABLE images DROP COLUMN user_id;
        // ALTER TABLE images DROP COLUMN type;
        // ALTER TABLE images ADD PRIMARY KEY (id);
        // ALTER TABLE images DROP INDEX images_id_unique;
        // ALTER TABLE images DROP INDEX images_id_index;
        // ALTER TABLE images DROP CONSTRAINT check_images_type;
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
            $table->string('type', 50)->after('user_id')->default('')->comment('画像種別：product, catalog, crop, user, other');

            $table->index(['sequence_id', 'user_id'], 'idx_sequence_id_user_id'); // 複合インデックス
        });
        // MySQL5.7ではCHECK制約が無視されるので、機能するのはMySQL8.0以降
        // DB::statement("ALTER TABLE images ADD CONSTRAINT check_images_type CHECK (type IN ('', 'product', 'catalog', 'crop', 'enterprise', 'profile'))");

        // 既存のデータに対してuser_idを設定
        // $this->updateUserIds();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('images', function (Blueprint $table) {
            $table->dropIndex('idx_sequence_id_user_id'); // 複合インデックス削除

            // カラムを削除
            $table->dropColumn('type');
            $table->dropColumn('user_id');
            $table->dropColumn('sequence_id');

            $table->dropUnique('images_id_unique'); // ユニークインデックスを削除
            $table->primary('id'); // idを再度主キーに設定（ユニークインデックス）
        });
    }

    /**
     * S3 Pathからユーザidを抽出し、user_idカラムを更新する
     *
     * ※S3 Pathの形式が 'xxx/xxx/{user_id}/xxx/...' であることを前提
     */
    private function updateUserIds(): void
    {
        DB::table('images')->orderBy('id')->chunk(1000, function ($images) {
            foreach ($images as $image) {
                $parts = explode('/', $image->id);
                DB::table('images')
                    ->where('id', $image->id)
                    ->update([
                        'user_id' => $parts[2],
                    ]);
            }
        });
    }
};
