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
        Schema::create('device_has_parts', function (Blueprint $table) {
            $table->comment('IT，设备配件关联表。');
            $table->id();
            $table->integer('device_id')
                ->comment('设备ID');
            $table->integer('part_id')
                ->comment('配件ID');
            $table->integer('user_id')
                ->comment('操作人');
            $table->string('status', 20)
                ->comment('状态：附加/脱离');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('information_device_has_parts');
    }
};
