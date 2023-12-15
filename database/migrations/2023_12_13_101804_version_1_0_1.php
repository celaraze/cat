<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * v1.0.1 更新涉及
 * 工单增加工时字段，单位分钟
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ticket_has_tracks', function (Blueprint $table) {
            $table->integer('minutes')->default(0)
                ->comment('工时');
        });
    }

    public function down(): void
    {
        Schema::table('ticket_has_tracks', function (Blueprint $table) {
            $table->dropColumn('minutes');
        });
    }
};
