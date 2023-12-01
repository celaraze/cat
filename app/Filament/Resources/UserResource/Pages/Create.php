<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;

class Create extends CreateRecord
{
    protected static string $resource = UserResource::class;

    /**
     * 表单保存前事件.
     *
     * @throws Halt
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 验证两次密码是否一致
        if ($data['password'] != $data['password_verify']) {
            Notification::make()
                ->danger()
                ->title('验证失败')
                ->body('两次密码不一致')
                ->persistent()
                ->send();
            $this->halt();
        }
        unset($data['password_verify']);

        return $data;
    }

    /**
     * 保存后跳转至列表.
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
