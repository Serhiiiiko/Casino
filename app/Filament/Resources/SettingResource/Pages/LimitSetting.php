<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use App\Models\Setting;
use App\Models\User;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class LimitSetting extends Page implements HasForms
{
    use HasPageSidebar, InteractsWithForms;

    protected static string $resource = SettingResource::class;

    protected static string $view = 'filament.resources.setting-resource.pages.limit-setting';

    /**
     * @dev @victormsalatiel
     * @param Model $record
     * @return bool
     */
    public static function canView(Model $record): bool
    {
        return auth()->user()->hasRole('admin');
    }

    /**
     * @return string|Htmlable
     */
    public function getTitle(): string | Htmlable
    {
        return __('Лимиты');
    }

    public Setting $record;
    public ?array $data = [];

    /**
     * @dev @victormsalatiel - Мой Instagram
     * @return void
     */
    public function mount(): void
    {
        $setting = Setting::first();
        $this->record = $setting;
        $this->form->fill($setting->toArray());
    }

    /**
     * @dev @victormsalatiel - Мой Instagram
     * @return void
     */
    public function save()
    {
        try {
            if (env('APP_DEMO')) {
                Notification::make()
                    ->title('Внимание')
                    ->body('Вы не можете выполнить это изменение в демо-версии')
                    ->danger()
                    ->send();
                return;
            }

            $setting = Setting::find($this->record->id);

            if ($setting->update($this->data)) {
                Cache::put('setting', $setting);

                Notification::make()
                    ->title('Данные изменены')
                    ->body('Данные успешно изменены!')
                    ->success()
                    ->send();

                redirect(route('filament.admin.resources.settings.limit', ['record' => $this->record->id]));
            }
        } catch (Halt $exception) {
            return;
        }
    }

    /**
     * @dev @victormsalatiel - Мой Instagram
     * @param Form $form
     * @return Form
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Настройка лимитов')
                    ->description('Форма для настройки лимитов на платформе')
                    ->schema([
                        TextInput::make('withdrawal_limit')
                            ->label('Лимит на вывод')
                            ->numeric(),
                        Select::make('withdrawal_period')
                            ->label('Период')
                            ->options([
                                'daily'   => 'Ежедневно',
                                'weekly'  => 'Еженедельно',
                                'monthly' => 'Ежемесячно',
                                'yearly'  => 'Ежегодно',
                            ]),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }
}
