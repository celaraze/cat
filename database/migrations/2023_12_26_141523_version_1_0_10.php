<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flows', function (Blueprint $table) {
            $table->comment('流程表');
            $table->id();
            $table->string('name')
                ->comment('名称');
            $table->string('slug')
                ->comment('标识');
            $table->string('model_name')
                ->comment('模型名称');
            $table->integer('creator_id')
                ->comment('创建人');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('flow_has_nodes', function (Blueprint $table) {
            $table->comment('流程节点表');
            $table->id();
            $table->integer('flow_id')
                ->comment('流程');
            $table->integer('order')
                ->comment('排序');
            $table->integer('role_id')
                ->comment('审批角色');
            $table->integer('creator_id')
                ->comment('创建人');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('flow_has_forms', function (Blueprint $table) {
            $table->comment('流程表单表');
            $table->id();
            $table->string('uuid')
                ->comment('唯一编码');
            $table->integer('flow_has_node_id')
                ->comment('节点 ID');
            $table->string('model_class')
                ->comment('模型名称');
            $table->integer('model_id')
                ->comment('模型 ID');
            $table->integer('applicant_id')
                ->comment('申请人');
            $table->integer('approver_id')
                ->default(0)
                ->comment('审批人');
            $table->integer('creator_id')
                ->comment('创建人');
            $table->string('comment')
                ->nullable()
                ->comment('审批描述');
            $table->integer('status')
                ->default(0)
                ->comment('状态');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flows');
        Schema::dropIfExists('flow_has_nodes');
        Schema::dropIfExists('flow_has_forms');
    }
};
