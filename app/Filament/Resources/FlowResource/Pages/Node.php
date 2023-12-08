<?php

namespace App\Filament\Resources\FlowResource\Pages;

use App\Filament\Actions\FlowAction;
use App\Filament\Resources\FlowResource;
use App\Models\FlowHasNode;
use App\Services\FlowHasNodeService;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;

class Node extends ManageRelatedRecords
{
    protected static string $resource = FlowResource::class;

    protected static string $relationship = 'nodes';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $title = '节点';

    public static function getNavigationLabel(): string
    {
        return '节点';
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->toggleable()
                    ->label('节点名称'),
                Tables\Columns\TextColumn::make('type')
                    ->searchable()
                    ->toggleable()
                    ->badge()
                    ->color(function (FlowHasNode $node) {
                        if (explode('：', $node->getAttribute('type'))[0] == '用户') {
                            return 'info';
                        } else {
                            return 'success';
                        }
                    })
                    ->label('审批类型'),
            ])
            ->filters([

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
                FlowAction::deleteHasNode($this->getOwnerRecord())
                    ->visible(function (FlowHasNode $node) {
                        $flow_has_node_service = new FlowHasNodeService($node);

                        // 第一个节点不允许被删除
                        // 中间节点不允许删除，只可以删除最后的节点
                        return ! $flow_has_node_service->isFirstNode() && $flow_has_node_service->isLastNode();
                    }),
            ])
            ->bulkActions([

            ]);
    }
}
