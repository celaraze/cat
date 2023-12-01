<?php

namespace App\Filament\Resources\FlowResource\RelationManagers;

use App\Filament\Actions\FlowAction;
use App\Models\FlowHasNode;
use App\Services\FlowHasNodeService;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class HasNodeRelationManager extends RelationManager
{
    protected static string $relationship = 'nodes';

    protected static ?string $title = '节点';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('name')
                    ->label('节点名称'),
                Tables\Columns\TextColumn::make('type')
                    ->label('审批类型')
                    ->badge()
                    ->color(function (FlowHasNode $node) {
                        if (explode('：', $node->getAttribute('type'))[0] == '用户') {
                            return 'info';
                        } else {
                            return 'success';
                        }
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                FlowAction::deleteHasNodeWithAll($this->getOwnerRecord())
                    ->visible($this->getOwnerRecord()->nodes()->count()),
            ])
            ->actions([
                FlowAction::createHasNode($this->getOwnerRecord())
                    ->visible(function (FlowHasNode $node) {
                        $flow_has_node_service = new FlowHasNodeService($node);

                        return ! $flow_has_node_service->isExistChildNode();
                    }),
                // todo 删除按钮需要判断逻辑，不能直接用预设
                FlowAction::deleteHasNode($this->getOwnerRecord())
                    ->visible(function (FlowHasNode $node) {
                        $flow_has_node_service = new FlowHasNodeService($node);

                        // 第一个节点不允许被删除
                        // 中间节点不允许删除，只可以删除最后的节点
                        return ! $flow_has_node_service->isFirstNode() && $flow_has_node_service->isLastNode();
                    }),
            ])
            ->bulkActions([

            ])
            ->emptyStateActions([

            ]);
    }

    protected function nodeCounts()
    {

    }
}
