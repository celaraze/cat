<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * v1.0.5 更新涉及
     * 新增密码表和密码附属表
     */
    public function up(): void
    {
        Schema::create('secrets', function (Blueprint $table) {
            $table->comment('密码');
            $table->id();
            $table->string('name')
                ->comment('名称');
            $table->string('site')
                ->nullable()
                ->comment('站点');
            $table->string('vault')
                ->comment('保险柜：public公共，private私人');
            $table->string('username')
                ->comment('账户');
            $table->string('token')
                ->comment('密钥');
            $table->timestamp('expired_at')
                ->nullable()
                ->comment('过期时间');
            $table->integer('creator_id')
                ->comment('操作人');
            $table->integer('status')
                ->comment('状态：参考 AssetEnum::statusText()');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('device_has_secrets', function (Blueprint $table) {
            $table->comment('设备附加密码');
            $table->id();
            $table->integer('device_id')
                ->comment('设备 ID');
            $table->integer('secret_id')
                ->comment('密码 ID');
            $table->integer('creator_id')
                ->comment('操作人');
            $table->integer('status')
                ->comment('操作：参考 AssetEnum::relationOperationText()');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('secrets');
        Schema::dropIfExists('device_has_secrets');
    }
};
