<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consumable_has_tracks', function (Blueprint $table) {
            $table->comment('耗材记录');
            $table->id();
            $table->integer('consumable_id')
                ->comment('耗材');
            $table->integer('quantity')
                ->comment('数量');
            $table->string('comment')
                ->comment('备注');
            $table->integer('creator_id')
                ->comment('创建人');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consumable_has_tracks');
    }
};
