<?php

namespace App\Filament\Pages;

use App\Models\GamesKey;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class GamesKeyPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.games-key-page';

    protected static ?string $title = 'Игровые ключи';

    // При желании можно оставить slug без изменений, чтобы не нарушать потенциальные маршруты
    protected static ?string $slug = 'chaves-dos-jogos';

    /**
     * @dev @victormsalatiel
     * @return bool
     */
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public ?array $data = [];
    public ?GamesKey $setting;

    /**
     * @return void
     */
    public function mount(): void
    {
        $gamesKey = GamesKey::first();
        if (!empty($gamesKey)) {
            $this->setting = $gamesKey;
            $this->form->fill($this->setting->toArray());
        } else {
            $this->form->fill();
        }
    }

    /**
     * @param Form $form
     * @return Form
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Games2Api')
                    ->description('Настройка учётных данных для Games2Api')
                    ->schema([
                        TextInput::make('games2_agent_code')
                            ->label('Agent Code')
                            ->placeholder('Введите здесь Agent Code')
                            ->maxLength(191),
                        TextInput::make('games2_agent_token')
                            ->label('Agent Token')
                            ->placeholder('Введите здесь Agent Token')
                            ->maxLength(191),
                        TextInput::make('games2_agent_secret_key')
                            ->label('Agent Secret Key')
                            ->placeholder('Введите здесь Agent Secret Key')
                            ->maxLength(191),
                        TextInput::make('games2_api_endpoint')
                            ->label('API Endpoint')
                            ->placeholder('Введите здесь API Endpoint')
                            ->maxLength(191)
                            ->columnSpanFull(),
                    ])
                    ->columns(3),

                Section::make('World Slot API')
                    ->description('Настройка учётных данных для World Slot')
                    ->schema([
                        TextInput::make('worldslot_agent_code')
                            ->label('Agent Code')
                            ->placeholder('Введите здесь Agent Code')
                            ->maxLength(191),
                        TextInput::make('worldslot_agent_token')
                            ->label('Agent Token')
                            ->placeholder('Введите здесь Agent Token')
                            ->maxLength(191),
                        TextInput::make('worldslot_agent_secret_key')
                            ->label('Agent Secret Key')
                            ->placeholder('Введите здесь Agent Secret Key')
                            ->maxLength(191),
                        TextInput::make('worldslot_api_endpoint')
                            ->label('API Endpoint')
                            ->placeholder('Введите здесь API Endpoint')
                            ->maxLength(191)
                            ->columnSpanFull(),
                    ])
                    ->columns(3),

                Section::make('Slotegrator API')
                    ->description('Настройка учётных данных для Slotegrator')
                    ->schema([
                        TextInput::make('merchant_url')
                            ->label('Merchant URL')
                            ->placeholder('Введите здесь URL API')
                            ->maxLength(191),
                        TextInput::make('merchant_id')
                            ->label('Merchant ID')
                            ->placeholder('Введите здесь Merchant ID')
                            ->maxLength(191),
                        TextInput::make('merchant_key')
                            ->label('Merchant Key')
                            ->placeholder('Введите здесь Merchant Key')
                            ->maxLength(191),
                    ])
                    ->columns(3),

                Section::make('Salsa API')
                    ->description('Настройка учётных данных для Salsa. Сайт провайдера: https://salsatechnology.com/')
                    ->schema([
                        TextInput::make('salsa_base_uri')
                            ->label('Salsa URI')
                            ->placeholder('Введите базовый URL Salsa')
                            ->maxLength(191),
                        TextInput::make('salsa_pn')
                            ->label('Salsa PN')
                            ->placeholder('Введите здесь PN')
                            ->maxLength(191),
                        TextInput::make('salsa_key')
                            ->label('Salsa Key')
                            ->placeholder('Введите здесь Salsa Key')
                            ->maxLength(191),
                    ])
                    ->columns(3),

                Section::make('Vibra Gaming API')
                    ->description('Настройка учётных данных для Vibra Gaming Casino. Сайт провайдера: https://vibragaming.com/')
                    ->schema([
                        TextInput::make('vibra_site_id')
                            ->label('Vibra Site ID')
                            ->placeholder('Введите здесь Vibra Site ID')
                            ->maxLength(191),
                        TextInput::make('vibra_game_mode')
                            ->label('Vibra Game Mode')
                            ->placeholder('Введите здесь Vibra Game Mode')
                            ->maxLength(191),
                    ])
                    ->columns(2),

                Section::make('Fivers API')
                    ->description('Настройка учётных данных для Fivers')
                    ->schema([
                        TextInput::make('agent_code')
                            ->label('Agent Code')
                            ->placeholder('Введите здесь Agent Code')
                            ->maxLength(191),
                        TextInput::make('agent_token')
                            ->label('Agent Token')
                            ->placeholder('Введите здесь Agent Token')
                            ->maxLength(191),
                        TextInput::make('agent_secret_key')
                            ->label('Agent Secret Key')
                            ->placeholder('Введите здесь Agent Secret Key')
                            ->maxLength(191),
                        TextInput::make('api_endpoint')
                            ->label('API Endpoint')
                            ->placeholder('Введите здесь API Endpoint')
                            ->maxLength(191)
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
            ])
            ->statePath('data');
    }

    /**
     * @return void
     */
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

            $setting = GamesKey::first();
            if (!empty($setting)) {
                if ($setting->update($this->data)) {
                    Notification::make()
                        ->title('Ключи изменены')
                        ->body('Ваши ключи были успешно изменены!')
                        ->success()
                        ->send();
                }
            } else {
                if (GamesKey::create($this->data)) {
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
