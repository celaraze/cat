<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * v1.0.4 更新涉及
 * 设备用户记录增加操作人字段
 * 设备附属记录中的 user_id 改为 creator_id，用于区分用户和操作者
 * 设备、配件、软件、厂商联系人增加额外信息字段
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('device_has_users', function (Blueprint $table) {
            $table->integer('creator_id')->default(0)->after('user_id')
                ->comment('操作人');
        });

        Schema::table('device_has_parts', function (Blueprint $table) {
            $table->renameColumn('user_id', 'creator_id');
        });

        Schema::table('device_has_software', function (Blueprint $table) {
            $table->renameColumn('user_id', 'creator_id');
        });

        Schema::table('inventories', function (Blueprint $table) {
            $table->renameColumn('user_id', 'creator_id');
        });

        Schema::table('inventory_has_tracks', function (Blueprint $table) {
            $table->renameColumn('user_id', 'creator_id');
        });

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

        Schema::create('footprints', function (Blueprint $table) {
            $table->comment('脚印');
            $table->id();
            $table->string('model_class')
                ->comment('模型名称');
            $table->integer('model_id')
                ->comment('模型 ID');
            $table->string('action', 10)
                ->comment('操作');
            $table->integer('creator_id')
                ->comment('操作人 ID');
            $table->json('before')
                ->nullable()
                ->comment('原始值');
            $table->json('after')
                ->nullable()
                ->comment('修改后的值');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('device_has_users', function (Blueprint $table) {
            $table->dropColumn('creator_id');
        });

        Schema::table('device_has_parts', function (Blueprint $table) {
            $table->renameColumn('creator_id', 'user_id');
        });

        Schema::table('device_has_software', function (Blueprint $table) {
            $table->renameColumn('creator_id', 'user_id');
        });

        Schema::table('inventories', function (Blueprint $table) {
            $table->renameColumn('creator_id', 'user_id');
        });

        Schema::table('inventory_has_tracks', function (Blueprint $table) {
            $table->renameColumn('creator_id', 'user_id');
        });

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

        Schema::dropIfExists('footprints');
    }
};
