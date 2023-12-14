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
            $table->json('additional')->nullable()->after('status')
                ->comment('额外信息');
        });

        Schema::table('parts', function (Blueprint $table) {
            $table->json('additional')->nullable()->after('status')
                ->comment('额外信息');
        });

        Schema::table('software', function (Blueprint $table) {
            $table->json('additional')->nullable()->after('status')
                ->comment('额外信息');
        });

        Schema::table('vendor_has_contacts', function (Blueprint $table) {
            $table->json('additional')->nullable()->after('email')
                ->comment('额外信息');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropColumn('additional');
        });

        Schema::table('parts', function (Blueprint $table) {
            $table->dropColumn('additional');
        });

        Schema::table('software', function (Blueprint $table) {
            $table->dropColumn('additional');
        });

        Schema::table('vendor_has_contacts', function (Blueprint $table) {
            $table->dropColumn('additional');
        });
    }
};
