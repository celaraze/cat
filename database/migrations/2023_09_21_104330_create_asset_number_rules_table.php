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
        Schema::create('asset_number_rules', function (Blueprint $table) {
            $table->comment('资产编号生成规则表。');
            $table->id();
            $table->string('name')
                ->unique()
                ->comment('名称');
            $table->string('formula')
                ->comment('公式');
            $table->integer('auto_increment_length')
                ->comment('自增长度');
            $table->integer('auto_increment_count')
                ->default(0)
                ->comment('自增计数');
            $table->string('class_name')
                ->default('无')
                ->comment('绑定资产类型的模型名称');
            $table->integer('is_auto')
                ->default(0)
                ->comment('是否启用自动生成');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_number_rules');
    }
};
