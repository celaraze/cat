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
        Schema::create('tickets', function (Blueprint $table) {
            $table->comment('工单表。');
            $table->id();
            $table->string('asset_number')
                ->comment('资产编号');
            $table->string('subject')
                ->comment('主题');
            $table->longText('description')
                ->comment('描述');
            $table->integer('category_id')
                ->comment('工单分类');
            $table->integer('priority')
                ->comment('优先级')
                ->default(0);
            $table->integer('status')
                ->comment('状态')
                ->default(0);
            $table->integer('user_id')
                ->comment('提交人');
            $table->integer('assignee_id')
                ->comment('处理人');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
