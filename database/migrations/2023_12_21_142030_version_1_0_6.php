<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consumables', function (Blueprint $table) {
            $table->comment('耗材');
            $table->id();
            $table->string('name')
                ->comment('耗材名称');
            $table->string('category_id')
                ->comment('分类');
            $table->string('brand_id')
                ->comment('品牌');
            $table->string('unit_id')
                ->comment('单位');
            $table->string('specification')
                ->nullable()
                ->comment('规格');
            $table->string('description')->nullable()
                ->comment('说明');
            $table->string('image')->nullable()
                ->comment('照片');
            $table->smallInteger('status')->default(0)
                ->comment('状态');
            $table->json('additional')->nullable()
                ->comment('额外信息');
            $table->integer('creator_id')
                ->comment('创建人');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('consumable_categories', function (Blueprint $table) {
            $table->comment('耗材分类');
            $table->id();
            $table->string('name')
                ->comment('分类名称');
            $table->integer('creator_id')
                ->comment('创建人');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('consumable_units', function (Blueprint $table) {
            $table->comment('耗材单位');
            $table->id();
            $table->string('name')
                ->comment('单位名称');
            $table->integer('creator_id')
                ->comment('创建人');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('asset_number_rules', function (Blueprint $table) {
            $table->string('class_name')->nullable()->default(null)->change();
        });

        Schema::table('device_has_users', function (Blueprint $table) {
            $table->string('comment')->default(null)->change();
            $table->string('delete_comment')->nullable()->default(null)->change();
            $table->integer('creator_id')->default(null)->change();
        });

        Schema::table('devices', function (Blueprint $table) {
            $table->string('name')->nullable()->default(null)->change();
            $table->string('sn')->nullable()->default(null)->change();
            $table->string('specification')->nullable()->default(null)->change();
        });

        Schema::table('flow_has_forms', function (Blueprint $table) {
            $table->string('approve_user_name')->nullable()->default(null)->change();
            $table->string('comment')->nullable()->default(null)->change();
            $table->string('approve_comment')->nullable()->default(null)->change();
            $table->string('payload')->nullable()->default(null)->change();
        });

        Schema::table('inventory_has_tracks', function (Blueprint $table) {
            $table->string('comment')->nullable()->default(null)->change();
        });

        Schema::table('parts', function (Blueprint $table) {
            $table->string('sn')->default(null)->change();
            $table->string('specification')->default(null)->change();
        });

        Schema::table('software', function (Blueprint $table) {
            $table->string('sn')->default(null)->change();
            $table->string('specification')->default(null)->change();
        });

        Schema::table('vendor_has_contacts', function (Blueprint $table) {
            $table->string('email')->default(null)->change();
        });

        Schema::table('vendors', function (Blueprint $table) {
            $table->string('public_phone_number')->default(null)->change();
            $table->string('referrer')->default(null)->change();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consumables');
        Schema::dropIfExists('consumable_categories');
        Schema::dropIfExists('consumable_units');

        Schema::table('asset_number_rules', function (Blueprint $table) {
            $table->string('class_name')->default('无')->change();
        });

        Schema::table('device_has_users', function (Blueprint $table) {
            $table->string('comment')->default('无')->change();
            $table->string('delete_comment')->default('无')->change();
            $table->string('creator_id')->default(0)->change();
        });

        Schema::table('devices', function (Blueprint $table) {
            $table->string('name')->default('无')->change();
            $table->string('sn')->default('无')->change();
            $table->string('specification')->default('无')->change();
        });

        Schema::table('flow_has_forms', function (Blueprint $table) {
            $table->string('approve_user_name')->default('无')->change();
            $table->string('comment')->default('无')->change();
            $table->string('approve_comment')->default('无')->change();
            $table->string('payload')->default('无')->change();
        });

        Schema::table('inventory_has_tracks', function (Blueprint $table) {
            $table->string('comment')->default('无')->change();
        });

        Schema::table('parts', function (Blueprint $table) {
            $table->string('sn')->nullable()->default('无')->change();
            $table->string('specification')->nullable()->default('无')->change();
        });

        Schema::table('software', function (Blueprint $table) {
            $table->string('sn')->nullable()->default('无')->change();
            $table->string('specification')->nullable()->default('无')->change();
        });

        Schema::table('vendor_has_contacts', function (Blueprint $table) {
            $table->string('email')->default('无')->change();
        });

        Schema::table('vendors', function (Blueprint $table) {
            $table->string('public_phone_number')->default('无')->change();
            $table->string('referrer')->default('无')->change();
        });
    }
};
