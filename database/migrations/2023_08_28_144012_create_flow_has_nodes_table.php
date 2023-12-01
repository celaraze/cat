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
        Schema::create('flow_has_nodes', function (Blueprint $table) {
            $table->comment('流程节点记录表。');
            $table->id();
            $table->string('name')
                ->comment('节点名称');
            $table->integer('flow_id')
                ->comment('工作流ID');
            $table->integer('user_id')
                ->comment('用户ID');
            $table->integer('role_id')
                ->comment('角色ID');
            $table->integer('parent_node_id')
                ->default(0)
                ->comment('父节点ID');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flow_has_nodes');
    }
};
