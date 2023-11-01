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
        Schema::create('flow_has_forms', function (Blueprint $table) {
            $table->comment('流程表单记录表。');
            $table->id();
            $table->string('name')
                ->comment('表单名称');
            $table->string('flow_name')
                ->comment('流程名称');
            $table->json('flow_progress')
                ->nullable()
                ->comment('流程路径快照');
            $table->string('uuid')
                ->comment('表单唯一标识');
            $table->integer('flow_id')
                ->comment('工作流ID');
            $table->integer('applicant_user_id')
                ->default(0)
                ->comment('申请人ID');
            $table->integer('approve_user_id')
                ->default(0)
                ->comment('审批人ID');
            $table->string('approve_user_name')
                ->default('无')
                ->comment('审批人名称');
            $table->integer('current_approve_user_id')
                ->default(0)
                ->comment('当前需要审批的用户ID');
            $table->integer('current_approve_role_id')
                ->default(0)
                ->comment('当前需要审批的角色ID');
            $table->integer('node_id')
                ->default(0)
                ->comment('当前节点，来自flow和flow_has_nodes表');
            $table->string('node_name')
                ->comment('当前节点名称，来自flow和flow_has_nodes表');
            $table->integer('status')
                ->default(0)
                ->comment('审批类型，1同意，2退回，3驳回');
            $table->string('comment')
                ->default('无')
                ->comment('申请意见');
            $table->string('approve_comment')
                ->default('无')
                ->comment('审批意见');
            $table->string('payload')
                ->default('无')
                ->comment('载荷，例如资产编号');
            $table->integer('stage')
                ->default(0)
                ->comment('表单顺序计数');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flow_has_forms');
    }
};
