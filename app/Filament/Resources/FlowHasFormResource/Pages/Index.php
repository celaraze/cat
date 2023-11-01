<?php

namespace App\Filament\Resources\FlowHasFormResource\Pages;

use App\Filament\Components\ListRecords\Tab;
use App\Filament\Resources\FlowHasFormResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class Index extends ListRecords
{
    protected static string $resource = FlowHasFormResource::class;

    protected static ?string $title = '';

    public function getTabs(): array
    {
        $tabs = [
            'my_applicant' => Tab::make('我的申请')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where('applicant_user_id', auth()->id());
                })
                ->icon('heroicon-o-document'),
            'my_approval' => Tab::make('我的审批')
                // 这里要判断两个类型的数据，一个是单独给我审批的，另一个是给我拥有的角色审批的
                ->modifyQueryUsing(function (Builder $query) {
                    $role_ids = auth()->user()->roles()->pluck('id');
                    return $query->where(function ($query) use ($role_ids) {
                        $query->whereIn('current_approve_role_id', $role_ids)
                            ->orWhere('current_approve_user_id', auth()->id());
                    });
                })
                ->icon('heroicon-o-document-check'),

        ];
        // TODO 这里差权限控制，只有拥有权限的人才能看到全部表单
        return $tabs;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
