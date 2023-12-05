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
        Schema::create('ticket_has_tracks', function (Blueprint $table) {
            $table->comment('工单记录表');
            $table->id();
            $table->integer('ticket_id');
            $table->longText('comment');
            $table->integer('user_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_has_tracks');
    }
};
