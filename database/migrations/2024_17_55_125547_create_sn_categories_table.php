<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sn_categories', function (Blueprint $table) {
            $table->comment('分类');
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('scope_type', 20)->nullable()->comment('范围类型');
            $table->unsignedBigInteger('scope_id')->default(0)->comment('范围');
            $table->integer('type_id')->default(0)->comment('类别');
            $table->string('name')->default('')->comment('名称');
            $table->integer('parent_id')->default(0)->comment('上级');
            $table->string('icon')->nullable()->comment('图标');
            $table->string('image')->nullable()->comment('封面');
            $table->string('description')->nullable()->comment('描述');
            $table->enum('status', ['normal', 'hidden'])->default('normal')->comment('状态:normal=正常,hidden=隐藏');
            $table->unsignedInteger('order_column')->nullable()->index()->comment('排序');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sn_categories');
    }
};
