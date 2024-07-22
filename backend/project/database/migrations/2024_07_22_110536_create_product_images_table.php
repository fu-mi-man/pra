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
        Schema::create('product_images', function (Blueprint $table) {
            $table->id('product_id');
            $table->integer('sequence'); // index
            $table->string('image_id');
            $table->timestamps();
        });

        //  $this->insertSampleData();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }

    /**
     * Insert sample data into the product_images table.
     */
    private function insertSampleData(): void
    {
        $sampleData = [];
        for ($i = 1; $i <= 100; $i++) {
            $sequence = rand(1, 10);
            $userId = rand(1, 100);
            $date = date('Y-md-His', strtotime("-{$i} days"));
            $sampleData[] = [
                'sequence' => $sequence,
                'image_id' => "image/41653966/{$userId}/4270b026/{$date}/XXXXXXXXXXXX.pdf.page_1.jpg",
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('product_images')->insert($sampleData);
    }
};
