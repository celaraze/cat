<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('software', function (Blueprint $table) {
            $table->comment('IT，软件主数据表。');
            $table->id();
            $table->string('asset_number')
                ->comment('资产编号');
            $table->integer('category_id')
                ->comment('分类ID');
            $table->string('name')
                ->comment('名称');
            $table->string('sn')
                ->comment('序列号')
                ->default('无');
            $table->string('specification')
                ->comment('规格')
                ->default('无');
            $table->integer('max_license_count')
                ->comment('授权数量')
                ->default(0);
            $table->string('image')
                ->default('无')
                ->nullable()
                ->comment('照片');
            $table->integer('brand_id')
                ->comment('品牌ID');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('information_software');
    }
};
