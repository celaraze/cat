<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->comment('厂商主数据表。');
            $table->id();
            $table->string('name')
                ->comment('名称');
            $table->string('address')
                ->comment('地址');
            $table->string('public_phone_number')
                ->default('无')
                ->comment('对公电话');
            $table->string('referrer')
                ->default('无')
                ->comment('引荐人');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
