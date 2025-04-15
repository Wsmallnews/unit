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
        Schema::create('sn_test_images', function (Blueprint $table) {
            $table->comment('图片');
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('title')->nullable()->comment('标题');
            $table->string('image')->nullable()->comment('封面');
            $table->json('images')->nullable()->comment('轮播图');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sn_test_images');
    }
};
