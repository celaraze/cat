<?php

namespace App\Filament\Actions;

use App\Filament\Forms\FlowForm;
use App\Filament\Forms\FlowHasFormForm;
use App\Models\Flow;
use App\Models\FlowHasForm;
use App\Models\FlowHasNode;
use App\Services\FlowHasFormService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class FlowAction
{
    public static function delete(): Action
    {
        return Action::make(__('cat/action.delete'))
            ->requiresConfirmation()
            ->color('danger')
            ->icon('heroicon-s-trash')
            ->action(function (Flow $flow) {
                try {
                    $flow->service()->delete();
                    NotificationUtil::make(true, __('cat/action.delete_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function create(): Action
    {
        return Action::make(__('cat/flow.action.create'))
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
                    $node->setAttribute('name', __('cat/flow_has_node.form.applicant'));
                    $node->setAttribute('flow_id', $flow->getKey());
                    $node->setAttribute('user_id', 0);
                    $node->setAttribute('role_id', 0);
                    $node->save();
                    // 提交事务
                    DB::commit();
                    NotificationUtil::make(true, __('cat/flow.action.create_success'));
                } catch (Exception $exception) {
                    // 回滚事务
                    DB::rollBack();
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception->getMessage());
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function approve(): \Filament\Infolists\Components\Actions\Action
    {
        return \Filament\Infolists\Components\Actions\Action::make(__('cat/flow.action.approve'))
            ->slideOver()
            ->icon('heroicon-o-shield-exclamation')
            ->form(FlowHasFormForm::approve())
            ->action(function (array $data, FlowHasForm $flow_has_form) {
                try {
                    $flow_has_form_service = new FlowHasFormService($flow_has_form);
                    $flow_has_form_service->approve($data['status'], $data['approve_comment']);
                    NotificationUtil::make(true, __('cat/flow.action.approve_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception->getMessage());
                }
            })
            ->closeModalByClickingAway(false);
    }
}
