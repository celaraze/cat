<?php

namespace App\Filament\Resources\FlowHasFormResource\Pages;

use App\Filament\Actions\FlowAction;
use App\Filament\Resources\FlowHasFormResource;
use App\Models\FlowHasForm;
use App\Models\User;
use Filament\Resources\Pages\ViewRecord;

class View extends ViewRecord
{
    protected static string $resource = FlowHasFormResource::class;

    public static function getNavigationLabel(): string
    {
        return '详情';
    }

    protected function getActions(): array
    {
        return [
            FlowAction::approve()
                ->visible(function (FlowHasForm $flowHasForm) {
                    // 根据表单状态判断是否显示审批按钮
                    $status = $flowHasForm->getAttribute('status');
                    $current_approve_user_id = $flowHasForm->getAttribute('current_approve_user_id');
                    $current_approve_role_id = $flowHasForm->getAttribute('current_approve_role_id');
                    if ($status == 0 || $status == 1 || $status == 2) {
                        // 根据表单当前审批人判断是否显示审批按钮
                        if ($current_approve_user_id == auth()->id()) {
                            return true;
                        }
                        // 根据表单当前审批角色判断是否显示审批按钮
                        $user = auth()->user();
                        /* @var User $user */
                        if ($user->hasRole($current_approve_role_id)) {
                            return true;
                        }
                    }

                    return false;
                }),
        ];
    }
}
