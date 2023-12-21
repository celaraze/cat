<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;

class Site extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-m-globe-asia-australia';

    protected static string $view = 'filament.pages.site';

    // todo 暂时隐藏
    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return __('cat.site');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('cat.system_setting');
    }

    public function getBreadcrumbs(): array
    {
        return [
            '/' => 'CAT',
            '' => __('cat.system_setting'),
            'site' => __('cat.site'),
        ];
    }

    public function mount(): void
    {
        $this->form->fill(Setting::query()->pluck('custom_value', 'custom_key')->toArray());
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('app_url')
                ->hint(__('cat.app_url_helper'))
                ->rules(['url'])
                ->label(__('cat.app_url')),
        ])
            ->statePath('data');
    }

    /**
     * @throws Halt
     */
    public function save(): void
    {
        try {
            $data = $this->form->getState();
            foreach ($data as $key => $datum) {
                Setting::query()->updateOrCreate(
                    ['custom_key' => $key],
                    ['custom_key' => $key, 'custom_value' => $datum]
                );
            }
            NotificationUtil::make(true, __('cat.save_success'));
        } catch (Halt $exception) {
            LogUtil::error($exception);
            NotificationUtil::make(false, $exception);
        }
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('cat.save'))
                ->submit('save'),
        ];
    }
}
