<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * v1.0.0 更新涉及
 * 初次发布，创建基础表
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->comment('失败的队列任务');
            $table->id();
            $table->string('uuid')->unique()
                ->comment('任务唯一标识');
            $table->text('connection')
                ->comment('队列连接');
            $table->text('queue')
                ->comment('队列名称');
            $table->longText('payload')
                ->comment('任务载荷');
            $table->longText('exception')
                ->comment('异常信息');
            $table->timestamp('failed_at')->useCurrent()
                ->comment('失败时间');
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->comment('通知');
            $table->uuid('id')->primary()
                ->comment('通知');
            $table->string('type')
                ->comment('类型');
            $table->morphs('notifiable');
            $table->text('data')
                ->comment('数据');
            $table->timestamp('read_at')->nullable()
                ->comment('已读时间');
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->comment('用户');
            $table->id();
            $table->string('name')
                ->comment('用户名');
            $table->string('avatar_url')->nullable()
                ->comment('头像');
            $table->string('email')->unique()
                ->comment('邮箱');
            $table->string('password')->nullable()
                ->comment('密码');
            $table->string('theme')->nullable()->default('default')
                ->comment('主题');
            $table->string('theme_color')->nullable()
                ->comment('主题颜色');
            $table->rememberToken()
                ->comment('记住我令牌');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('brands', function (Blueprint $table) {
            $table->comment('品牌');
            $table->id();
            $table->string('name')
                ->comment('名称');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('vendors', function (Blueprint $table) {
            $table->comment('厂商');
            $table->id();
            $table->string('name')
                ->comment('名称');
            $table->string('address')
                ->comment('地址');
            $table->string('public_phone_number')->default('无')->nullable()
                ->comment('对公电话');
            $table->string('referrer')->default('无')->nullable()
                ->comment('引荐人');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('vendor_has_contacts', function (Blueprint $table) {
            $table->comment('厂商联系人');
            $table->id();
            $table->integer('vendor_id')
                ->comment('厂商');
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

        Schema::create('device_categories', function (Blueprint $table) {
            $table->comment('设备分类');
            $table->id();
            $table->string('name')->unique()
                ->comment('名称');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('devices', function (Blueprint $table) {
            $table->comment('设备');
            $table->id();
            $table->string('asset_number')->unique()
                ->comment('资产编号');
            $table->integer('category_id')
                ->comment('分类');
            $table->string('name')->default('无')
                ->comment('名称');
            $table->string('sn')->default('无')
                ->comment('序列号');
            $table->string('specification')->default('无')
                ->comment('规格');
            $table->string('image')->nullable()
                ->comment('照片');
            $table->integer('brand_id')
                ->comment('品牌');
            $table->string('description')->nullable()
                ->comment('说明');
            $table->smallInteger('status')->default(0)
                ->comment('状态：0闲置，1使用，2借用，3报废，4正常');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('part_categories', function (Blueprint $table) {
            $table->comment('配件分类');
            $table->id();
            $table->string('name')->unique()
                ->comment('名称');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('parts', function (Blueprint $table) {
            $table->comment('配件');
            $table->id();
            $table->string('asset_number')->unique()
                ->comment('资产编号');
            $table->integer('category_id')
                ->comment('分类');
            $table->string('sn')->default('无')->nullable()
                ->comment('序列号');
            $table->string('specification')->default('无')->nullable()
                ->comment('规格');
            $table->string('image')->nullable()
                ->comment('照片');
            $table->integer('brand_id')
                ->comment('品牌');
            $table->string('description')->nullable()
                ->comment('说明');
            $table->smallInteger('status')->default(0)
                ->comment('状态：0闲置，1使用，2借用，3报废，4正常');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('software_categories', function (Blueprint $table) {
            $table->comment('软件分类');
            $table->id();
            $table->string('name')
                ->comment('名称');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('software', function (Blueprint $table) {
            $table->comment('软件');
            $table->id();
            $table->string('asset_number')
                ->comment('资产编号');
            $table->integer('category_id')
                ->comment('分类');
            $table->string('name')
                ->comment('名称');
            $table->string('sn')->nullable()->default('无')
                ->comment('序列号');
            $table->string('specification')->nullable()->default('无')
                ->comment('规格');
            $table->integer('max_license_count')->default(0)
                ->comment('授权数量');
            $table->string('image')->nullable()
                ->comment('照片');
            $table->integer('brand_id')->comment('品牌');
            $table->string('description')->nullable()
                ->comment('说明');
            $table->smallInteger('status')->default(0)
                ->comment('状态：0闲置，1使用，2借用，3报废，4正常');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('device_has_users', function (Blueprint $table) {
            $table->comment('设备用户');
            $table->id();
            $table->integer('device_id')
                ->comment('设备');
            $table->integer('user_id')
                ->comment('用户');
            $table->tinyInteger('status')->default(0)
                ->comment('分配状态：0管理, 1借用');
            $table->string('comment')->default('无')
                ->comment('分配原因');
            $table->string('delete_comment')->default('无')
                ->comment('解除原因');
            $table->timestamp('expired_at')->nullable()
                ->comment('到期时间');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('device_has_parts', function (Blueprint $table) {
            $table->comment('设备配件');
            $table->id();
            $table->integer('device_id')
                ->comment('设备');
            $table->integer('part_id')
                ->comment('配件');
            $table->integer('user_id')
                ->comment('操作人');
            $table->tinyInteger('status')->default(0)
                ->comment('状态：0附加，1脱离');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('device_has_software', function (Blueprint $table) {
            $table->comment('设备软件');
            $table->id();
            $table->integer('device_id')
                ->comment('设备');
            $table->integer('software_id')
                ->comment('软件');
            $table->integer('user_id')
                ->comment('操作人');
            $table->tinyInteger('status')->default(0)
                ->comment('状态：0附加，1脱离');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('flows', function (Blueprint $table) {
            $table->comment('流程');
            $table->id();
            $table->string('tag')->unique()
                ->comment('唯一标识');
            $table->string('name')
                ->comment('名称');
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
            $table->string('approve_user_name')->default('无')
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
            $table->string('comment')->default('无')
                ->comment('申请意见');
            $table->string('approve_comment')->default('无')
                ->comment('审批意见');
            $table->string('payload')->default('无')
                ->comment('载荷，例如资产编号');
            $table->integer('stage')->default(0)
                ->comment('表单顺序计数');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->comment('设置');
            $table->id();
            $table->string('custom_key')
                ->comment('键');
            $table->string('custom_value')
                ->comment('值');
            $table->timestamps();
        });

        Schema::create('asset_number_rules', function (Blueprint $table) {
            $table->comment('资产编号生成规则');
            $table->id();
            $table->string('name')->unique()
                ->comment('名称');
            $table->string('formula')
                ->comment('公式');
            $table->integer('auto_increment_length')
                ->comment('自增长度');
            $table->integer('auto_increment_count')->default(0)
                ->comment('自增计数');
            $table->string('class_name')->default('无')
                ->comment('绑定资产类型的模型名称');
            $table->integer('is_auto')->default(0)
                ->comment('是否启用自动生成');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('asset_number_tracks', function (Blueprint $table) {
            $table->comment('资产编号汇总');
            $table->id();
            $table->string('asset_number')->unique()
                ->comment('资产编号');
            $table->timestamps();
        });

        Schema::create('inventories', function (Blueprint $table) {
            $table->comment('盘点');
            $table->id();
            $table->string('name')
                ->comment('盘点任务名称');
            $table->string('class_name')
                ->comment('资产模型类名');
            $table->integer('user_id')
                ->comment('创建人');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('inventory_has_tracks', function (Blueprint $table) {
            $table->comment('盘点记录');
            $table->id();
            $table->integer('inventory_id')
                ->comment('盘点任务');
            $table->string('asset_number')
                ->comment('资产编号');
            $table->integer('check')->default(0)
                ->comment('盘点操作：1在库，2缺失');
            $table->integer('user_id')
                ->comment('操作人');
            $table->string('comment')->default('无')
                ->comment('说明');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('organizations', function (Blueprint $table) {
            $table->comment('组织');
            $table->id();
            $table->string('name')
                ->comment('名称');
            $table->integer('parent_id')->default(-1)
                ->comment('父组织');
            $table->integer('order')->default(0)->index()
                ->comment('排序');
            $table->integer('manager_role_id')->default(0)
                ->comment('组织管理角色');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('organization_has_users', function (Blueprint $table) {
            $table->comment('组织成员');
            $table->id();
            $table->integer('organization_id')
                ->comment('组织');
            $table->integer('user_id')
                ->comment('用户');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        Schema::create('imports', function (Blueprint $table) {
            $table->comment('导入');
            $table->id();
            $table->timestamp('completed_at')->nullable()
                ->comment('完成时间');
            $table->string('file_name')
                ->comment('文件名');
            $table->string('file_path')
                ->comment('文件路径');
            $table->string('importer')
                ->comment('导入器');
            $table->unsignedInteger('processed_rows')->default(0)
                ->comment('已处理行数');
            $table->unsignedInteger('total_rows')
                ->comment('总行数');
            $table->unsignedInteger('successful_rows')->default(0)
                ->comment('成功行数');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('failed_import_rows', function (Blueprint $table) {
            $table->comment('导入失败记录');
            $table->id();
            $table->json('data')
                ->comment('数据');
            $table->foreignId('import_id')->constrained()->cascadeOnDelete();
            $table->text('validation_error')->nullable()
                ->comment('验证错误');
            $table->timestamps();
        });

        Schema::create('ticket_categories', function (Blueprint $table) {
            $table->comment('工单分类');
            $table->id();
            $table->string('name')
                ->comment('名称');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('tickets', function (Blueprint $table) {
            $table->comment('工单');
            $table->id();
            $table->string('asset_number')
                ->comment('资产编号');
            $table->string('subject')
                ->comment('主题');
            $table->longText('description')
                ->comment('描述');
            $table->integer('category_id')
                ->comment('分类');
            $table->tinyInteger('priority')->default(0)
                ->comment('优先级：0低，,1中，2高，3紧急');
            $table->tinyInteger('status')->default(0)
                ->comment('状态：0空闲，1进行，2完成');
            $table->integer('user_id')
                ->comment('提交人');
            $table->integer('assignee_id')
                ->comment('处理人');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('ticket_has_tracks', function (Blueprint $table) {
            $table->comment('工单记录');
            $table->id();
            $table->integer('ticket_id')
                ->comment('工单');
            $table->longText('comment')
                ->comment('评论');
            $table->integer('user_id')
                ->comment('用户');
            $table->softDeletes();
            $table->timestamps();
        });

        $teams = config('permission.teams');
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $pivotRole = $columnNames['role_pivot_key'] ?? 'role_id';
        $pivotPermission = $columnNames['permission_pivot_key'] ?? 'permission_id';

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        }
        if ($teams && empty($columnNames['team_foreign_key'] ?? null)) {
            throw new \Exception('Error: team_foreign_key on config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        }

        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->bigIncrements('id'); // permission id
            $table->string('name');       // For MySQL 8.0 use string('name', 125);
            $table->string('guard_name'); // For MySQL 8.0 use string('guard_name', 125);
            $table->timestamps();

            $table->unique(['name', 'guard_name']);
        });

        Schema::create($tableNames['roles'], function (Blueprint $table) use ($teams, $columnNames) {
            $table->bigIncrements('id'); // role id
            if ($teams || config('permission.testing')) { // permission.testing is a fix for sqlite testing
                $table->unsignedBigInteger($columnNames['team_foreign_key'])->nullable();
                $table->index($columnNames['team_foreign_key'], 'roles_team_foreign_key_index');
            }
            $table->string('name');       // For MySQL 8.0 use string('name', 125);
            $table->string('guard_name'); // For MySQL 8.0 use string('guard_name', 125);
            $table->timestamps();
            if ($teams || config('permission.testing')) {
                $table->unique([$columnNames['team_foreign_key'], 'name', 'guard_name']);
            } else {
                $table->unique(['name', 'guard_name']);
            }
        });

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames, $pivotPermission, $teams) {
            $table->unsignedBigInteger($pivotPermission);

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_model_id_model_type_index');

            $table->foreign($pivotPermission)
                ->references('id') // permission id
                ->on($tableNames['permissions'])
                ->onDelete('cascade');
            if ($teams) {
                $table->unsignedBigInteger($columnNames['team_foreign_key']);
                $table->index($columnNames['team_foreign_key'], 'model_has_permissions_team_foreign_key_index');

                $table->primary([$columnNames['team_foreign_key'], $pivotPermission, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_permissions_permission_model_type_primary');
            } else {
                $table->primary([$pivotPermission, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_permissions_permission_model_type_primary');
            }

        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames, $pivotRole, $teams) {
            $table->unsignedBigInteger($pivotRole);

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');

            $table->foreign($pivotRole)
                ->references('id') // role id
                ->on($tableNames['roles'])
                ->onDelete('cascade');
            if ($teams) {
                $table->unsignedBigInteger($columnNames['team_foreign_key']);
                $table->index($columnNames['team_foreign_key'], 'model_has_roles_team_foreign_key_index');

                $table->primary([$columnNames['team_foreign_key'], $pivotRole, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_roles_role_model_type_primary');
            } else {
                $table->primary([$pivotRole, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_roles_role_model_type_primary');
            }
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames, $pivotRole, $pivotPermission) {
            $table->unsignedBigInteger($pivotPermission);
            $table->unsignedBigInteger($pivotRole);

            $table->foreign($pivotPermission)
                ->references('id') // permission id
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign($pivotRole)
                ->references('id') // role id
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary([$pivotPermission, $pivotRole], 'role_has_permissions_permission_id_role_id_primary');
        });

        app('cache')
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }

    public function down(): void
    {
        Schema::dropAllTables();
    }
};
