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
        Schema::create('inventory_has_tracks', function (Blueprint $table) {
            $table->id();
            $table->integer('inventory_id');
            $table->string('asset_number');
            $table->integer('check')->default(0);
            $table->integer('user_id');
            $table->string('comment')->default('无');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_has_tracks');
    }
};
