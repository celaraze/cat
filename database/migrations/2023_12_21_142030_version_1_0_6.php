<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consumable', function (Blueprint $table) {
            $table->comment('耗材');
            $table->id();
            $table->string('name')
                ->comment('耗材名称');
            $table->string('category_id')
                ->comment('分类');
            $table->string('brand_id')
                ->comment('品牌');
            $table->string('unit_id')
                ->comment('单位');
            $table->string('specification')
                ->default('无')
                ->comment('规格');
            $table->string('description')->nullable()
                ->comment('说明');
            $table->string('image')->nullable()
                ->comment('照片');
            $table->smallInteger('status')
                ->comment('状态');
            $table->integer('creator_id')
                ->comment('创建人');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('consumable_categories', function (Blueprint $table) {
            $table->comment('耗材分类');
            $table->id();
            $table->string('name')
                ->comment('分类名称');
            $table->integer('creator_id')
                ->comment('创建人');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('consumable_units', function (Blueprint $table) {
            $table->comment('耗材单位');
            $table->id();
            $table->string('name')
                ->comment('单位名称');
            $table->integer('creator_id')
                ->comment('创建人');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consumable');
        Schema::dropIfExists('consumable_categories');
        Schema::dropIfExists('consumable_units');
    }
};
