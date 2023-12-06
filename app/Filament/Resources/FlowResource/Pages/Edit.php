<?php

namespace App\Filament\Resources\FlowResource\Pages;

use App\Filament\Resources\FlowResource;
use App\Models\Flow;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = FlowResource::class;

    public static function getNavigationLabel(): string
    {
        return '编辑';
    }

    protected function getHeaderActions(): array
    {
        return [
            // 删除
            Actions\DeleteAction::make()
                ->before(function (Flow $flow) {
                    if ($flow->forms()->whereNotIn('status', [3, 4])->count()) {
                        NotificationUtil::make(false, '此流程仍有未结案表单，请先处理表单');
                        $this->halt();
                    } else {
                        try {
                            $flow->nodes()->delete();
                        } catch (Exception $exception) {
                            NotificationUtil::make(false, $exception->getMessage());
                            $this->halt();
                        }
                    }
                })
                ->visible(function () {
                    return auth()->user()->can('delete_flow');
                }),
        ];
    }
}
