<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use App\Models\Setting;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Forms\Components\Group;
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

class PaymentSetting extends Page implements HasForms
{
    use HasPageSidebar, InteractsWithForms;

    protected static string $resource = SettingResource::class;

    protected static string $view = 'filament.resources.setting-resource.pages.payment-setting';

    /**
     * @return string|Htmlable
     */
    public function getTitle(): string | Htmlable
    {
        return __('Платежи');
    }

    public Setting $record;
    public ?array $data = [];

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
     * @dev victormsalatiel - Мой Instagram
     * @return void
     */
    public function mount(): void
    {
        $setting = Setting::first();
        $this->record = $setting;
        $this->form->fill($setting->toArray());
    }

    /**
     * @dev victormsalatiel - Мой Instagram
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

                redirect(route('filament.admin.resources.settings.payment', ['record' => $this->record->id]));
            }
        } catch (Halt $exception) {
            return;
        }
    }

    /**
     * @dev victormsalatiel - Мой Instagram
     * @param Form $form
     * @return Form
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Настройка комиссий')
                    ->description('Форма для настройки комиссий на платформе')
                    ->schema([
                        TextInput::make('min_deposit')
                            ->label('Мин. депозит')
                            ->numeric()
                            ->maxLength(191),
                        TextInput::make('max_deposit')
                            ->label('Макс. депозит')
                            ->numeric()
                            ->maxLength(191),
                        TextInput::make('min_withdrawal')
                            ->label('Мин. вывод')
                            ->numeric()
                            ->maxLength(191),
                        TextInput::make('max_withdrawal')
                            ->label('Макс. вывод')
                            ->numeric()
                            ->maxLength(191),
                        TextInput::make('initial_bonus')
                            ->label('Начальный бонус (%)')
                            ->numeric()
                            ->suffix('%')
                            ->maxLength(191),
                        TextInput::make('currency_code')
                            ->label('Валюта')
                            ->maxLength(191),

                        Group::make()
                            ->label('Процент суб-аффилиатов')
                            ->schema([
                                TextInput::make('perc_sub_lv1')
                                    ->label('% Суб-аффилиат Уровень 1')
                                    ->numeric()
                                    ->maxLength(191),
                                TextInput::make('perc_sub_lv2')
                                    ->label('% Суб-аффилиат Уровень 2')
                                    ->numeric()
                                    ->maxLength(191),
                                TextInput::make('perc_sub_lv3')
                                    ->label('% Суб-аффилиат Уровень 3')
                                    ->numeric()
                                    ->maxLength(191),
                            ])
                            ->columnSpanFull()
                            ->columns(3),

                        Toggle::make('suitpay_is_enable')
                            ->label('SuitPay Активен'),
                        Toggle::make('stripe_is_enable')
                            ->label('Stripe Активен'),
                        Toggle::make('bspay_is_enable')
                            ->label('BSPay Активен'),
                        Toggle::make('disable_spin')
                            ->label('Отключить Spin'),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }
}
