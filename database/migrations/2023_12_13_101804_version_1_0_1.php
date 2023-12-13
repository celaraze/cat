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
        Schema::table('ticket_has_tracks', function (Blueprint $table) {
            $table->integer('minutes')->default(0)
                ->comment('工时');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_has_tracks', function (Blueprint $table) {
            $table->dropColumn('minutes');
        });
    }
};
