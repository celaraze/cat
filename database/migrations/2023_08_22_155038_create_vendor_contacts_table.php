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
        Schema::create('vendor_contacts', function (Blueprint $table) {
            $table->comment('厂商联系人记录表。');
            $table->id();
            $table->integer('vendor_id')
                ->comment('厂商ID');
            $table->string('name')
                ->comment('名称');
            $table->string('phone_number')
                ->comment('电话');
            $table->string('email')
                ->default('无')
                ->comment('邮箱');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_contacts');
    }
};
