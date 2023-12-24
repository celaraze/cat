<?php

namespace App\Filament\Actions;

use App\Filament\Forms\FlowForm;
use App\Models\Flow;
use App\Models\FlowHasNode;
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
        return Action::make(__('cat/flow.action.delete'))
            ->slideOver()
            ->requiresConfirmation()
            ->color('danger')
            ->icon('heroicon-s-trash')
            ->action(function (Flow $flow) {
                try {
                    $flow->service()->delete();
                    NotificationUtil::make(true, __('cat/flow.action.delete_success'));
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
}
