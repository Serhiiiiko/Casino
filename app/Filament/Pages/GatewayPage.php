<?php

namespace App\Filament\Pages;

use App\Models\Gateway;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;

class GatewayPage extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.gateway-page';

    public ?array $data = [];
    public Gateway $setting;

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public function mount(): void
    {
        $gateway = Gateway::first();
        if (!empty($gateway)) {
            $this->setting = $gateway;
            $this->form->fill($this->setting->toArray());
        } else {
            $this->form->fill();
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Suitpay настройки
                Section::make('Suitpay')
                    ->description('Настройка учётных данных для Suitpay')
                    ->schema([
                        TextInput::make('suitpay_uri')
                            ->label('Client URI')
                            ->placeholder('Введите URL API')
                            ->maxLength(191)
                            ->columnSpanFull(),
                        TextInput::make('suitpay_cliente_id')
                            ->label('Client ID')
                            ->placeholder('Введите Client ID')
                            ->maxLength(191)
                            ->columnSpanFull(),
                        TextInput::make('suitpay_cliente_secret')
                            ->label('Client Secret')
                            ->placeholder('Введите Client Secret')
                            ->maxLength(191)
                            ->columnSpanFull(),
                    ]),
                // Stripe настройки
                Section::make('Stripe')
                    ->description('Настройка учётных данных для Stripe')
                    ->schema([
                        TextInput::make('stripe_public_key')
                            ->label('Публичный ключ')
                            ->placeholder('Введите публичный ключ')
                            ->maxLength(191)
                            ->columnSpanFull(),
                        TextInput::make('stripe_secret_key')
                            ->label('Приватный ключ')
                            ->placeholder('Введите приватный ключ')
                            ->maxLength(191)
                            ->columnSpanFull(),
                        TextInput::make('stripe_webhook_key')
                            ->label('Webhook ключ')
                            ->placeholder('Введите ключ вебхука')
                            ->maxLength(191)
                            ->columnSpanFull(),
                    ]),
                // Antrpay настройки
                Section::make('Antrpay')
                    ->description('Настройка учётных данных для Antrpay')
                    ->schema([
                        TextInput::make('antrpay_uri')
                            ->label('Antrpay URI')
                            ->placeholder('Введите URL API Antrpay')
                            ->maxLength(191)
                            ->columnSpanFull(),
                        TextInput::make('antrpay_public_key')
                            ->label('Antrpay Public Key')
                            ->placeholder('Введите публичный ключ Antrpay')
                            ->maxLength(191)
                            ->columnSpanFull(),
                        TextInput::make('antrpay_secret_key')
                            ->label('Antrpay Secret Key')
                            ->placeholder('Введите секретный ключ Antrpay')
                            ->maxLength(191)
                            ->columnSpanFull(),
                        Toggle::make('antrpay_is_enable')
                            ->label('Antrpay Активен'),
                    ]),
            ])
            ->statePath('data');
    }

    public function submit(): void
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

            $setting = Gateway::first();
            if (!empty($setting)) {
                if ($setting->update($this->data)) {
                    // Обновляем переменные .env для Stripe
                    if (!empty($this->data['stripe_public_key'])) {
                        $envs = DotenvEditor::load(base_path('.env'));
                        $envs->setKeys([
                            'STRIPE_KEY' => $this->data['stripe_public_key'],
                            'STRIPE_SECRET' => $this->data['stripe_secret_key'],
                            'STRIPE_WEBHOOK_SECRET' => $this->data['stripe_webhook_key'],
                        ]);
                        $envs->save();
                    }
                    // Обновляем .env для Antrpay
                    if (!empty($this->data['antrpay_public_key'])) {
                        $envs = DotenvEditor::load(base_path('.env'));
                        $envs->setKeys([
                            'ANTRPAY_URI' => $this->data['antrpay_uri'],
                            'ANTRPAY_PUBLIC_KEY' => $this->data['antrpay_public_key'],
                            'ANTRPAY_SECRET_KEY' => $this->data['antrpay_secret_key'],
                        ]);
                        $envs->save();
                    }

                    Notification::make()
                        ->title('Ключи изменены')
                        ->body('Ваши ключи были успешно изменены!')
                        ->success()
                        ->send();
                }
            } else {
                if (Gateway::create($this->data)) {
                    Notification::make()
                        ->title('Ключи созданы')
                        ->body('Ваши ключи были успешно созданы!')
                        ->success()
                        ->send();
                }
            }
        } catch (Halt $exception) {
            Notification::make()
                ->title('Ошибка при изменении данных!')
                ->body('Ошибка при изменении данных!')
                ->danger()
                ->send();
        }
    }
}
