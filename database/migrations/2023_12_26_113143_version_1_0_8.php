<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asset_number_rules', function (Blueprint $table) {
            $table->integer('creator_id')
                ->default(0)
                ->comment('创建人');
        });
        Schema::table('asset_number_tracks', function (Blueprint $table) {
            $table->integer('creator_id')
                ->default(0)
                ->comment('创建人');
        });
        Schema::table('brands', function (Blueprint $table) {
            $table->integer('creator_id')
                ->default(0)
                ->comment('创建人');
        });
        Schema::table('device_categories', function (Blueprint $table) {
            $table->integer('creator_id')
                ->default(0)
                ->comment('创建人');
        });
        Schema::table('devices', function (Blueprint $table) {
            $table->integer('creator_id')
                ->default(0)
                ->comment('创建人');
        });
        Schema::table('flow_has_forms', function (Blueprint $table) {
            $table->integer('creator_id')
                ->default(0)
                ->comment('创建人');
        });
        Schema::table('flow_has_nodes', function (Blueprint $table) {
            $table->integer('creator_id')
                ->default(0)
                ->comment('创建人');
        });
        Schema::table('flows', function (Blueprint $table) {
            $table->integer('creator_id')
                ->default(0)
                ->comment('创建人');
        });
        Schema::table('organization_has_users', function (Blueprint $table) {
            $table->integer('creator_id')
                ->default(0)
                ->comment('创建人');
        });
        Schema::table('organizations', function (Blueprint $table) {
            $table->integer('creator_id')
                ->default(0)
                ->comment('创建人');
        });
        Schema::table('part_categories', function (Blueprint $table) {
            $table->integer('creator_id')
                ->default(0)
                ->comment('创建人');
        });
        Schema::table('parts', function (Blueprint $table) {
            $table->integer('creator_id')
                ->default(0)
                ->comment('创建人');
        });
        Schema::table('software', function (Blueprint $table) {
            $table->integer('creator_id')
                ->default(0)
                ->comment('创建人');
        });
        Schema::table('software_categories', function (Blueprint $table) {
            $table->integer('creator_id')
                ->default(0)
                ->comment('创建人');
        });
        Schema::table('ticket_categories', function (Blueprint $table) {
            $table->integer('creator_id')
                ->default(0)
                ->comment('创建人');
        });
        Schema::table('ticket_has_tracks', function (Blueprint $table) {
            $table->integer('creator_id')
                ->default(0)
                ->comment('创建人');
        });
        Schema::table('tickets', function (Blueprint $table) {
            $table->integer('creator_id')
                ->default(0)
                ->comment('创建人');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->integer('creator_id')
                ->default(0)
                ->comment('创建人');
        });
        Schema::table('vendor_has_contacts', function (Blueprint $table) {
            $table->integer('creator_id')
                ->default(0)
                ->comment('创建人');
        });
        Schema::table('vendors', function (Blueprint $table) {
            $table->integer('creator_id')
                ->default(0)
                ->comment('创建人');
        });
    }

    public function down(): void
    {
        Schema::dropColumns('asset_number_rules', ['creator_id']);
        Schema::dropColumns('asset_number_tracks', ['creator_id']);
        Schema::dropColumns('brands', ['creator_id']);
        Schema::dropColumns('device_categories', ['creator_id']);
        Schema::dropColumns('devices', ['creator_id']);
        Schema::dropColumns('flow_has_forms', ['creator_id']);
        Schema::dropColumns('flow_has_nodes', ['creator_id']);
        Schema::dropColumns('flows', ['creator_id']);
        Schema::dropColumns('organization_has_users', ['creator_id']);
        Schema::dropColumns('organizations', ['creator_id']);
        Schema::dropColumns('part_categories', ['creator_id']);
        Schema::dropColumns('parts', ['creator_id']);
        Schema::dropColumns('software', ['creator_id']);
        Schema::dropColumns('software_categories', ['creator_id']);
        Schema::dropColumns('ticket_categories', ['creator_id']);
        Schema::dropColumns('ticket_has_tracks', ['creator_id']);
        Schema::dropColumns('tickets', ['creator_id']);
        Schema::dropColumns('users', ['creator_id']);
        Schema::dropColumns('vendor_has_contacts', ['creator_id']);
        Schema::dropColumns('vendors', ['creator_id']);
    }
};
