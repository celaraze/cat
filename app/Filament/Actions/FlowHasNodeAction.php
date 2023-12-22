<?php

namespace App\Filament\Actions;

use App\Filament\Forms\FlowHasNodeForm;
use App\Models\Flow;
use App\Models\FlowHasNode;
use App\Services\FlowHasNodeService;
use App\Utils\NotificationUtil;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class FlowHasNodeAction
{
    public static function create(Model $flow): Action
    {
        /* @var Flow $flow */
        return Action::make(__('cat/flow_has_node.action.create'))
            ->slideOver()
            ->icon('heroicon-s-user-plus')
            ->form(FlowHasNodeForm::create())
            ->action(function (array $data, FlowHasNode $node) use ($flow): void {
                if ($flow->activeForms()) {
                    NotificationUtil::make(false, __('cat/flow_has_node.action.create_failure_active_forms'));
                } else {
                    $flow_has_node_service = new FlowHasNodeService($node);
                    $is_last_node = $flow_has_node_service->isLastNode();
                    if (! $is_last_node) {
                        NotificationUtil::make(false, __('cat/flow_has_node.action.create_failure_node_exists'));
                    } elseif (empty($data['user_id']) && empty($data['role_id'])) {
                        NotificationUtil::make(false, __('cat/flow_has_node.action.create_failure_user_id_or_role_id_is_empty'));
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
                        NotificationUtil::make(true, __('cat/flow_has_node.action.create_success'));
                    }
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function deleteAll(Model $flow): Action
    {
        /* @var Flow $flow */
        return Action::make(__('cat/flow_has_node.action.delete_all'))
            ->color('danger')
            ->requiresConfirmation()
            ->action(function () use ($flow) {
                if ($flow->activeForms()) {
                    NotificationUtil::make(false, __('cat/flow_has_node.action.delete_all_failure_avtive_forms'));
                } else {
                    $flow->nodes()->where('parent_node_id', '!=', 0)->delete();
                    NotificationUtil::make(true, __('cat/flow_has_node.action.delete_all_success'));
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function delete(Model $flow): Action
    {
        /* @var Flow $flow */
        return Action::make(__('cat/flow_has_node.action.delete'))
            ->color('danger')
            ->icon('heroicon-s-trash')
            ->requiresConfirmation()
            ->action(function (FlowHasNode $node) use ($flow) {
                if ($flow->activeForms()) {
                    NotificationUtil::make(false, __('cat/flow_has_node.action.delete_failure_active_forms'));
                } else {
                    $node->delete();
                    NotificationUtil::make(true, __('cat/flow_has_node.action.delete_success'));
                }
            })
            ->closeModalByClickingAway(false);
    }
}
