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
        Schema::table('devices', function (Blueprint $table) {
            $table->string('image')->nullable()->default(null)->change();
        });

        Schema::table('parts', function (Blueprint $table) {
            $table->string('image')->nullable()->default(null)->change();
        });

        Schema::table('software', function (Blueprint $table) {
            $table->string('image')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->string('image')->nullable()->default('无')->change();
        });

        Schema::table('parts', function (Blueprint $table) {
            $table->string('image')->nullable()->default('无')->change();
        });

        Schema::table('software', function (Blueprint $table) {
            $table->string('image')->nullable()->default('无')->change();
        });
    }
};
