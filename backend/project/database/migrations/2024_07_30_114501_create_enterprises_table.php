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
            $table->id('enterprise_id')->comment('id');
            // $table->unsignedBigInteger('user_id');
            $table->string('name')->comment('企業名');
            $table->text('description')->nullable();
            $table->string('address');
            $table->string('email');
            $table->string('icon')->nullable();
            $table->string('header')->nullable();
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
