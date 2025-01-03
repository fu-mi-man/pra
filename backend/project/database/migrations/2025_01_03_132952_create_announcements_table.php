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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title', 30);
            $table->text('content');
            $table->enum('category', ['notice', 'maintenance', 'update']);
            $table->boolean('is_published')->default(true);
            $table->datetime('start_at');
            $table->datetime('end_at')->nullable();
            $table->boolean('show_banner')->default(false);
            $table->datetime('banner_start_at')->nullable();
            $table->datetime('banner_end_at')->nullable();
            $table->boolean('send_notification')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
