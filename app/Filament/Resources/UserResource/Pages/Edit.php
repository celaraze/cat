<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
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
        return __('cat/action.edit');
    }

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
                ->title(__('cat/form.validate_failure'))
                ->body($validator->errors()->first())
                ->persistent()
                ->send();
            $this->halt();
        }

        return $data;
    }
}
