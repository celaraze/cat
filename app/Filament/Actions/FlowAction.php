<?php

namespace App\Filament\Actions;

use App\Filament\Forms\FlowForm;
use App\Filament\Forms\FlowHasFormForm;
use App\Filament\Forms\FlowHasNodeForm;
use App\Models\Flow;
use App\Models\FlowHasForm;
use App\Models\FlowHasNode;
use App\Services\FlowHasFormService;
use App\Services\FlowHasNodeService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class FlowAction
{
    public static function createHasNode(Model $flow): Action
    {
        /* @var Flow $flow */
        return Action::make(__('cat.action.create_node'))
            ->slideOver()
            ->icon('heroicon-s-user-plus')
            ->form(FlowHasNodeForm::create())
            ->action(function (array $data, FlowHasNode $node) use ($flow): void {
                if ($flow->activeForms()) {
                    NotificationUtil::make(false, __('cat.action.create_node_failure_is_not_finished'));
                } else {
                    $flow_has_node_service = new FlowHasNodeService($node);
                    $is_last_node = $flow_has_node_service->isLastNode();
                    if (! $is_last_node) {
                        NotificationUtil::make(false, __('cat.action.create_node_failure_is_not_last_node'));
                    } elseif (empty($data['user_id']) && empty($data['role_id'])) {
                        NotificationUtil::make(false, __('cat.action.create_node_failure_user_id_or_role_id_is_empty'));
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
                        NotificationUtil::make(true, __('cat.action.create_node_success'));
                    }
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function create(): Action
    {
        return Action::make(__('cat.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(FlowForm::create())
            ->action(function (array $data) {
                try {
                    // 开始事务
                    DB::beginTransaction();
                    $flow = new Flow();
                    $flow->setAttribute('name', $data['name']);
                    $flow->setAttribute('tag', Uuid::uuid4());
                    $flow->save();
                    $node = new FlowHasNode();
                    $node->setAttribute('name', __('cat.flow_has_node.applicant'));
                    $node->setAttribute('flow_id', $flow->getKey());
                    $node->setAttribute('user_id', 0);
                    $node->setAttribute('role_id', 0);
                    $node->save();
                    // 提交事务
                    DB::commit();
                    NotificationUtil::make(true, __('cat.action.create_success'));
                } catch (Exception $exception) {
                    // 回滚事务
                    DB::rollBack();
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception->getMessage());
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function deleteHasNode(Model $flow): Action
    {
        /* @var Flow $flow */
        return Action::make(__('cat.action.delete_node'))
            ->color('danger')
            ->icon('heroicon-s-trash')
            ->requiresConfirmation()
            ->action(function (FlowHasNode $node) use ($flow) {
                if ($flow->activeForms()) {
                    NotificationUtil::make(false, __('cat.action.delete_node_failure_is_not_finished'));
                } else {
                    $node->delete();
                    NotificationUtil::make(true, __('cat.action.delete_node_success'));
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function delete(): Action
    {
        return Action::make(__('cat.action.delete'))
            ->requiresConfirmation()
            ->color('danger')
            ->icon('heroicon-s-trash')
            ->action(function (Flow $flow) {
                try {
                    $flow->service()->delete();
                    NotificationUtil::make(true, __('cat.action.delete_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function deleteHasNodeWithAll(Model $flow): Action
    {
        /* @var Flow $flow */
        return Action::make(__('cat.action.delete_node_with_all'))
            ->color('danger')
            ->requiresConfirmation()
            ->action(function () use ($flow) {
                if ($flow->activeForms()) {
                    NotificationUtil::make(false, __('cat.action.delete_node_failure_is_not_finished'));
                } else {
                    $flow->nodes()->where('parent_node_id', '!=', 0)->delete();
                    NotificationUtil::make(true, __('cat.action.delete_node_success'));
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function createHasForm(): Action
    {
        return Action::make(__('cat.action.create_flow_has_form'))
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(FlowHasFormForm::create())
            ->action(function (array $data) {
                try {
                    $flow_has_form_service = new FlowHasFormService();
                    $flow_has_form_service->create($data);
                    NotificationUtil::make(true, __('cat.action.create_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function approve(): \Filament\Infolists\Components\Actions\Action
    {
        return \Filament\Infolists\Components\Actions\Action::make(__('cat.action.approve'))
            ->slideOver()
            ->icon('heroicon-o-shield-exclamation')
            ->form(FlowHasFormForm::approve())
            ->action(function (array $data, FlowHasForm $flow_has_form) {
                try {
                    $flow_has_form_service = new FlowHasFormService($flow_has_form);
                    $flow_has_form_service->approve($data['status'], $data['approve_comment']);
                    NotificationUtil::make(true, __('cat.action.approve_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception->getMessage());
                }
            })
            ->closeModalByClickingAway(false);
    }
}
