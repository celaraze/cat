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
        Schema::create('parts', function (Blueprint $table) {
            $table->comment('IT，配件主数据表。');
            $table->id();
            $table->string('asset_number')
                ->unique()
                ->comment('资产编号');
            $table->integer('category_id')
                ->comment('分类ID');
            $table->string('sn')
                ->default('无')
                ->comment('序列号');
            $table->string('specification')
                ->default('无')
                ->comment('规格');
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
        Schema::dropIfExists('information_parts');
    }
};
