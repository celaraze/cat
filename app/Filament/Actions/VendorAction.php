<?php

namespace App\Filament\Actions;

use App\Filament\Forms\VendorForm;
use App\Services\VendorHasContactService;
use App\Services\VendorService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\Action;

class VendorAction
{
    /**
     * 创建联系人按钮.
     */
    public static function createVendorHasContact(string $vendor_id): Action
    {
        return Action::make('添加联系人')
            ->form([
                TextInput::make('name')
                    ->maxLength(255)
                    ->required()
                    ->label('名称'),
                TextInput::make('phone_number')
                    ->maxLength(255)
                    ->required()
                    ->label('电话'),
                TextInput::make('email')
                    ->maxLength(255)
                    ->label('邮箱'),
            ])
            ->action(function (array $data) use ($vendor_id) {
                try {
                    $vendor_has_contact_service = new VendorHasContactService();
                    $data = [
                        'vendor_id' => $vendor_id,
                        'name' => $data['name'],
                        'phone_number' => $data['phone_number'],
                        'email' => $data['email'],
                    ];
                    $vendor_has_contact_service->create($data);
                    NotificationUtil::make(true, '已添加联系人');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    /**
     * 创建厂商.
     */
    public static function createVendor(): Action
    {
        return Action::make('创建厂商')
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(VendorForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $vendor_service = new VendorService();
                    $vendor_service->create($data);
                    NotificationUtil::make(true, '已创建厂商');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }
}
