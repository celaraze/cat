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

    protected static ?string $navigationLabel = '站点';

    protected static ?string $navigationGroup = '系统设置';

    // todo 暂时隐藏
    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];

    protected ?string $heading = ' ';

    public function getBreadcrumbs(): array
    {
        return [
            '/' => 'CAT',
            '' => '系统',
            'site' => '站点',
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
                ->hint('填写你访问 CAT 的地址，这会影响上传的图片、文件、Logo 这一类资源的正确显示。')
                ->rules(['url'])
                ->label('站点地址'),
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
            NotificationUtil::make(true, '已保存');
        } catch (Halt $exception) {
            LogUtil::error($exception);
            NotificationUtil::make(false, $exception);
        }
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('保存')
                ->submit('save'),
        ];
    }
}
