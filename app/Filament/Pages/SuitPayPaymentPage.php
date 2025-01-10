<?php

namespace App\Filament\Pages;

use App\Livewire\LatestPixPayments;
use App\Models\SuitPayPayment;
use App\Models\User;
use App\Traits\Gateways\SuitpayTrait;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Model;

class SuitPayPaymentPage extends Page
{
    use SuitpayTrait;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static string $view = 'filament.pages.suit-pay-payment-page';

    protected static ?string $navigationLabel = 'SuitPay Платежи';

    protected static ?string $modelLabel = 'SuitPay Платежи';

    protected static ?string $title = 'SuitPay Платежи';

    protected static ?string $slug = 'suitpay-pagamentos';

    /**
     * @dev @victormsalatiel
     * @return bool
     */
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public ?array $data = [];
    public SuitPayPayment $suitPayPayment;

    /**
     * @return void
     */
    public function mount(): void
    {
        $this->form->fill();
    }

    /**
     * @param Form $form
     * @return Form
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Детали платежа')
                    ->schema([
                        Select::make('user_id')
                            ->label('Пользователи')
                            ->placeholder('Выберите пользователя')
                            ->relationship(name: 'user', titleAttribute: 'name')
                            ->options(
                                fn($get) => User::query()
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),
                        TextInput::make('pix_key')
                            ->label('Ключ Pix')
                            ->placeholder('Введите ключ Pix')
                            ->required(),
                        Select::make('pix_type')
                            ->label('Тип ключа')
                            ->placeholder('Выберите тип ключа')
                            ->options([
                                'document'    => 'Документ',
                                'phoneNumber' => 'Телефон',
                                'randomKey'   => 'Случайный ключ',
                                'paymentCode' => 'Код оплаты',
                            ]),
                        TextInput::make('amount')
                            ->label('Сумма')
                            ->placeholder('Введите сумму')
                            ->required()
                            ->numeric(),
                        Textarea::make('observation')
                            ->label('Примечание')
                            ->placeholder('Оставьте комментарий, если нужно')
                            ->rows(5)
                            ->cols(10)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    /**
     * @return void
     */
    public function submit(): void
    {
        if (env('APP_DEMO')) {
            Notification::make()
                ->title('Внимание')
                ->body('Вы не можете выполнить это изменение в демо-версии')
                ->danger()
                ->send();
            return;
        }

        $suitpayment = SuitPayPayment::create([
            'user_id'     => $this->data['user_id'],
            'pix_key'     => $this->data['pix_key'],
            'pix_type'    => $this->data['pix_type'],
            'amount'      => $this->data['amount'],
            'observation' => $this->data['observation'],
        ]);

        if ($suitpayment) {
            $resp = self::pixCashOut([
                'pix_key'        => $this->data['pix_key'],
                'pix_type'       => $this->data['pix_type'],
                'amount'         => $this->data['amount'],
                'suitpayment_id' => $suitpayment->id,
            ]);

            if ($resp) {
                Notification::make()
                    ->title('Вывод запрошен')
                    ->body('Запрос на вывод успешно отправлен')
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Ошибка при выводе')
                    ->body('Ошибка при запросе на вывод')
                    ->danger()
                    ->send();
            }
        } else {
            Notification::make()
                ->title('Ошибка при сохранении')
                ->body('Не удалось сохранить запрос на вывод')
                ->danger()
                ->send();
        }
    }

    /**
     * @return array|\Filament\Widgets\WidgetConfiguration[]|string[]
     */
    public function getFooterWidgets(): array
    {
        return [
            LatestPixPayments::class,
        ];
    }
}
