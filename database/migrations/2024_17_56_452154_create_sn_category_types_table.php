<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sn_category_types', function (Blueprint $table) {
            $table->comment('分类类别');
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('scope_type', 20)->nullable()->comment('范围类型');
            $table->unsignedBigInteger('scope_id')->default(0)->comment('范围');
            $table->string('name')->nullable()->comment('名称');
            $table->tinyInteger('level')->default(0)->comment('层级');
            $table->string('description')->nullable()->comment('描述');
            $table->enum('status', ['normal', 'disabled'])->default('normal')->comment('状态:normal=正常,disabled=禁用');
            $table->unsignedInteger('order_column')->nullable()->index()->comment('排序');
            $table->timestamps();
            $table->softDeletes();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sn_category_types');
    }
};
