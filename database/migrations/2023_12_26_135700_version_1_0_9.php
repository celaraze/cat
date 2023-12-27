<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('flows');
        Schema::dropIfExists('flow_has_forms');
        Schema::dropIfExists('flow_has_nodes');
    }

    public function down(): void
    {
        Schema::create('flows', function (Blueprint $table) {
            $table->comment('流程');
            $table->id();
            $table->string('tag')->unique()
                ->comment('唯一标识');
            $table->string('name')
                ->comment('名称');
            $table->integer('creator_id')->default(0)
                ->comment('创建人');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('flow_has_nodes', function (Blueprint $table) {
            $table->comment('流程节点');
            $table->id();
            $table->string('name')
                ->comment('节点名称');
            $table->integer('flow_id')
                ->comment('流程');
            $table->integer('user_id')
                ->comment('审批用户');
            $table->integer('role_id')
                ->comment('审批角色');
            $table->integer('parent_node_id')->default(0)
                ->comment('父节点');
            $table->integer('creator_id')->default(0)
                ->comment('创建人');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('flow_has_forms', function (Blueprint $table) {
            $table->comment('流程表单');
            $table->id();
            $table->string('name')
                ->comment('表单名称');
            $table->string('flow_name')
                ->comment('流程名称');
            $table->json('flow_progress')->nullable()
                ->comment('流程路径快照');
            $table->string('uuid')
                ->comment('表单唯一标识');
            $table->integer('flow_id')
                ->comment('流程');
            $table->integer('applicant_user_id')->default(0)
                ->comment('申请人');
            $table->integer('approve_user_id')->default(0)
                ->comment('审批人');
            $table->string('approve_user_name')->nullable()
                ->comment('审批人名称');
            $table->integer('current_approve_user_id')->default(0)
                ->comment('当前需要审批的用户');
            $table->integer('current_approve_role_id')->default(0)
                ->comment('当前需要审批的角色');
            $table->integer('node_id')->default(0)
                ->comment('当前节点');
            $table->string('node_name')
                ->comment('当前节点名称');
            $table->integer('status')->default(0)
                ->comment('审批类型：1同意，2退回，3驳回');
            $table->string('comment')->nullable()
                ->comment('申请意见');
            $table->string('approve_comment')->nullable()
                ->comment('审批意见');
            $table->string('payload')->nullable()
                ->comment('载荷，例如资产编号');
            $table->integer('stage')->default(0)
                ->comment('表单顺序计数');
            $table->integer('creator_id')->default(0)
                ->comment('创建人');
            $table->softDeletes();
            $table->timestamps();
        });
    }
};
