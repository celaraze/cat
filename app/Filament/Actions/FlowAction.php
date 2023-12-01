<?php

namespace App\Filament\Actions;

use App\Filament\Forms\FlowForm;
use App\Models\Flow;
use App\Models\FlowHasForm;
use App\Models\FlowHasNode;
use App\Services\FlowHasFormService;
use App\Services\FlowHasNodeService;
use App\Services\FlowService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class FlowAction
{
    /**
     * 创建流程节点按钮.
     */
    public static function createHasNode(Model $flow): Action
    {
        /* @var $flow Flow */
        return Action::make('追加节点')
            ->icon('heroicon-s-user-plus')
            ->form(FlowForm::createNode())
            ->action(function (array $data, FlowHasNode $node) use ($flow): void {
                if ($flow->activeForms()) {
                    NotificationUtil::make(false, '创建失败，仍有此流程的表单没有结束');
                } else {
                    $flow_has_node_service = new FlowHasNodeService($node);
                    $is_last_node = $flow_has_node_service->isLastNode();
                    if (! $is_last_node) {
                        NotificationUtil::make(false, '该节点不是最终节点，请在最终节点后追加');
                    } elseif (empty($data['user_id']) && empty($data['role_id'])) {
                        NotificationUtil::make(false, '流程审批类型不能为空，必须选择用户或者角色');
                    } else {
                        $data = [
                            'name' => $data['name'],
                            'flow_id' => $flow->getKey(),
                            'user_id' => $data['user_id'] ?? 0,
                            'role_id' => $data['role_id'] ?? 0,
                            'parent_node_id' => $node->getKey(),
                        ];
                        $flow_has_node_service = new FlowHasNodeService();
                        $flow_has_node_service->create($data);
                        NotificationUtil::make(true, '节点追加成功');
                    }
                }
            });
    }

    /**
     * 删除节点.
     *
     * @param  Flow  $flow
     */
    public static function deleteHasNode(Model $flow): Action
    {
        /* @var $flow Flow */
        return Action::make('删除')
            ->color('danger')
            ->icon('heroicon-s-trash')
            ->requiresConfirmation()
            ->action(function (FlowHasNode $node) use ($flow) {
                if ($flow->activeForms()) {
                    NotificationUtil::make(false, '删除失败，仍有此流程的表单没有结束');
                } else {
                    $node->delete();
                    NotificationUtil::make(true, '成功删除节点');
                }
            });
    }

    /**
     * 删除流程所有节点.
     */
    public static function deleteHasNodeWithAll(Model $flow): Action
    {
        /* @var $flow Flow */
        return Action::make('清空节点')
            ->requiresConfirmation()
            ->action(function () use ($flow) {
                if ($flow->activeForms()) {
                    NotificationUtil::make(false, '删除失败，仍有此流程的表单没有结束');
                } else {
                    $flow->nodes()->where('parent_node_id', '!=', 0)->delete();
                    NotificationUtil::make(true, '成功删除所有节点');
                }
            });
    }

    /**
     * 创建表单按钮.
     */
    public static function createHasForm(): Action
    {
        return Action::make('发起表单')
            ->slideOver()
            ->form([
                Select::make('flow_id')
                    ->options(FlowService::pluckOptions())
                    ->label('选择流程')
                    ->required(),
                TextInput::make('name')
                    ->label('表单名称')
                    ->helperText('申请表单的主题，例如某某某申请某资产一台。')
                    ->required(),
                TextInput::make('comment')
                    ->label('申请意见')
                    ->required(),
            ])
            ->action(function (array $data) {
                try {
                    $flow = new FlowService($data['flow_id']);
                    $flow->createHasForm($data['name'], $data['comment']);
                    NotificationUtil::make(true, '已创建表单');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    /**
     * 创建流程按钮.
     */
    public static function createFlow(): Action
    {
        return Action::make('创建流程')
            ->slideOver()
            ->form(FlowForm::createFlow())
            ->action(function (array $data) {
                try {
                    // 开始事务
                    DB::beginTransaction();
                    $flow = new Flow();
                    $flow->setAttribute('name', $data['name']);
                    $flow->setAttribute('tag', Uuid::uuid4());
                    $flow->save();
                    $node = new FlowHasNode();
                    $node->setAttribute('name', '申请人');
                    $node->setAttribute('flow_id', $flow->getKey());
                    $node->setAttribute('user_id', 0);
                    $node->setAttribute('role_id', 0);
                    $node->save();
                    // 提交事务
                    DB::commit();
                    NotificationUtil::make(true, '创建成功');
                } catch (Exception $exception) {
                    // 回滚事务
                    DB::rollBack();
                    LogUtil::error($exception);
                    NotificationUtil::make(false, '流程创建失败：'.$exception->getMessage());
                }
            });
    }

    /**
     * 流程表单审批按钮.
     */
    public static function approve(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('审批')
            ->form([
                Radio::make('status')
                    ->label('审批类型')
                    ->options([
                        1 => '同意，表单进入下一个审核。',
                        2 => '退回，表单回到上一个审核。',
                        3 => '驳回，表单直接结束流程。',
                    ])
                    ->required(),
                TextInput::make('approve_comment')
                    ->label('审批意见')
                    ->required(),
            ])
            ->action(function (array $data, FlowHasForm $flow_has_form) {
                try {
                    $flow_has_form_service = new FlowHasFormService($flow_has_form);
                    $flow_has_form_service->approve($data['status'], $data['approve_comment']);
                    NotificationUtil::make(true, '审批完成');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception->getMessage());
                }
            });
    }
}
