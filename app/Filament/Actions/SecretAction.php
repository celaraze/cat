<?php

namespace App\Filament\Actions;

use App\Filament\Forms\SecretForm;
use App\Models\Secret;
use App\Services\SecretService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;

class SecretAction
{
    public static function viewToken(): Action
    {
        return Action::make(__('cat/secret.action.view_token'))
            ->icon('heroicon-m-key')
            ->color('warning')
            ->requiresConfirmation()
            ->modalDescription(__('cat/secret.action.view_token_helper'))
            ->form(SecretForm::viewToken())
            ->action(function (array $data, Secret $secret) {
                try {
                    if (auth()->attempt(['email' => auth()->user()->email, 'password' => $data['password']])) {
                        NotificationUtil::make(true, __('cat/secret.action.view_token_success').decrypt($secret->getAttribute('token')), true);
                    } else {
                        NotificationUtil::make(false, __('cat/secret.action.view_token_failure'));
                    }
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    public static function create(): Action
    {
        return Action::make(__('cat/secret.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(SecretForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $data['creator_id'] = auth()->id();
                    $secret_service = new SecretService();
                    $secret_service->create($data);
                    NotificationUtil::make(true, __('cat/secret.action.create_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function retire(): Action
    {
        return Action::make(__('cat/secret.action.retire'))
            ->icon('heroicon-m-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->form(SecretForm::delete())
            ->action(function (Secret $secret) {
                try {
                    $secret->service()->retire();
                    NotificationUtil::make(true, __('cat/secret.action.retire_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }
}
