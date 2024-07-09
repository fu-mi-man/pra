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
        Schema::create('images', function (Blueprint $table) {
            $table->string('id')->primary;
            $table->string('name');
            $table->integer('size');
            $table->timestamps();
        });

        $this->insertSampleData();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }

    /**
     * Insert sample data into the images table.
     */
    private function insertSampleData(): void
    {
        $sampleData = [];
        for ($i = 1; $i <= 100; $i++) {
            $userId = rand(10000000, 99999999);
            $date = date('Y-md-His', strtotime("-{$i} days"));
            $sampleData[] = [
                'id' => "image/41653966/{$userId}/4270b026/{$date}/XXXXXXXXXXXX.pdf.page_1.jpg",
                'name' => "Sample Image {$i}",
                'size' => rand(1000000, 10000000), // 1MB to 10MB
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('images')->insert($sampleData);  // ここを 'images' に修正
    }
};
