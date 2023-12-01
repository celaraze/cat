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
        Schema::create('device_has_users', function (Blueprint $table) {
            $table->comment('IT，设备分配管理者表。');
            $table->id();
            $table->integer('device_id')
                ->comment('设备ID');
            $table->integer('user_id')
                ->comment('用户ID');
            $table->string('comment')
                ->default('无')
                ->comment('分配原因');
            $table->string('delete_comment')
                ->default('无')
                ->comment('解除原因');
            $table->timestamp('expire_datetime')
                ->nullable()
                ->comment('到期时间');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_has_users');
    }
};
