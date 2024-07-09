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
        Schema::table('images', function (Blueprint $table) {
            // インデックスを追加
            // $table->index(['sequence_id', 'user_id']);
            // idのプライマリーキーを外すが，インデックスは残す
            // DB::statement('ALTER TABLE images MODIFY id VARCHAR(255) NOT NULL');
            // $table->dropPrimary('id');
            // $table->index('id')->comment('S3 Path')->change();
            // DB::statement('ALTER TABLE images MODIFY id VARCHAR(255) NOT NULL COMMENT "S3 Path"');

            // sequence_id, user_id, typeを追加
            $table->bigIncrements('sequence_id')->first()->comment('シーケンスID');
            $table->unsignedBigInteger('user_id')->after('sequence_id')->comment('ユーザID');
            $table->string('type')->after('user_id')->default('')->comment('画像の種類：product, catalog, kiritori, user, other');

            // $table->index(['sequence_id', 'user_id']); // 複合インデックス
        });

        // 既存のデータに対してuser_idを設定
        $this->updateUserIds();
        //
        //         DB::statement("ALTER TABLE images ADD CONSTRAINT check_image_type CHECK (type IN ('', 'product', 'catalog', 'kiritori', 'user', 'other'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('images', function (Blueprint $table) {
            $table->dropColumn('sequence_id');
            $table->dropColumn('user_id');
            $table->dropColumn('type');
            // $table->dropIndex(['sequence_id', 'user_id']);
            // $table->dropIndex('id');
            // $table->dropUnique('images_sequence_id_unique');

            // $table->primary('id'); // idを再度プライマリーキーに設定
        });

        // DB::statement("ALTER TABLE images DROP CONSTRAINT IF EXISTS check_image_type");
    }

    private function updateUserIds(): void
    {
        DB::table('images')->orderBy('id')->chunk(1000, function ($images) {
            foreach ($images as $image) {
                $parts = explode('/', $image->id);
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
