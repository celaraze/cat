<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Edit extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return '编辑';
    }

    //    /**
    //     * 保存事件.
    //     *
    //     * @param bool $shouldRedirect
    //     * @return void
    //     * @throws Halt
    //     */
    //    public function save(bool $shouldRedirect = true): void
    //    {
    //        /* @var User $user */
    //        $user = $this->getRecord();
    //        try {
    //            $user->service()->update($this->data);
    //            NotificationUtil::make(true, '保存成功');
    //        } catch (Exception $exception) {
    //            LogUtil::error($exception);
    //            NotificationUtil::make(false, $exception);
    //            $this->halt();
    //        }
    //    }

    /**
     * 表单保存前事件.
     *
     * @throws Halt
     * @throws Exception
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $validator = Validator::make($data, [
            'email' => [
                'required',
                Rule::unique('users')->ignore($this->getRecord()->getAttribute('id')),
            ],
        ]);
        if ($validator->fails()) {
            Notification::make()
                ->danger()
                ->title('验证失败')
                ->body($validator->errors()->first())
                ->persistent()
                ->send();
            $this->halt();
        }

        return $data;
    }
}
