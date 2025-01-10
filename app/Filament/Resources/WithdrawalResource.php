<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WithdrawalResource\Pages;
use App\Filament\Resources\WithdrawalResource\RelationManagers;
use App\Models\User;
use App\Models\Withdrawal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class WithdrawalResource extends Resource
{
    protected static ?string $model = Withdrawal::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-tray';

    protected static ?string $navigationLabel = 'Выводы средств';

    protected static ?string $modelLabel = 'Выводы средств';

    protected static ?string $navigationGroup = 'Администрирование';

    protected static ?string $slug = 'todos-saques';

    protected static ?int $navigationSort = 3;

    /**
     * @dev @victormsalatiel
     * @return bool
     */
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    /**
     * @return string[]
     */
    public static function getGloballySearchableAttributes(): array
    {
        return ['type', 'bank_info', 'user.name', 'user.last_name', 'user.cpf', 'user.phone', 'user.email'];
    }

    /**
     * @return string|null
     */
    public static function getNavigationBadge(): ?string
    {
        // Показывает количество непроведённых (status = 0) заявок на вывод
        return static::getModel()::where('status', 0)->count();
    }

    /**
     * @return string|array|null
     */
    public static function getNavigationBadgeColor(): string|array|null
    {
        // Если больше 5 необработанных заявок, меняем цвет на 'success', иначе 'warning'
        return static::getModel()::where('status', 0)->count() > 5 ? 'success' : 'warning';
    }

    /**
     * @param Form $form
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Регистрация вывода средств')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Пользователи')
                            ->placeholder('Выберите пользователя')
                            ->relationship(name: 'user', titleAttribute: 'name')
                            ->options(
                                fn($get) => User::query()->pluck('name', 'id')
                            )
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),

                        Forms\Components\TextInput::make('amount')
                            ->label('Сумма')
                            ->required()
                            ->default(0.00),

                        Forms\Components\TextInput::make('type')
                            ->label('Тип')
                            ->required()
                            ->maxLength(191),

                        Forms\Components\FileUpload::make('proof')
                            ->label('Подтверждение')
                            ->placeholder('Загрузите изображение квитанции')
                            ->image()
                            ->columnSpanFull()
                            ->required(),

                        Forms\Components\Toggle::make('status')
                            ->label('Статус')
                            ->required(),
                    ]),
            ]);
    }

    /**
     * @param Table $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Имя')
                    ->searchable(['users.name', 'users.last_name'])
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Сумма')
                    ->formatStateUsing(fn (Withdrawal $record): string => $record->symbol . ' ' . $record->amount)
                    ->sortable(),

                Tables\Columns\TextColumn::make('pix_type')
                    ->label('Тип')
                    ->formatStateUsing(fn (string $state): string => \Helper::formatPixType($state))
                    ->searchable(),

                Tables\Columns\TextColumn::make('pix_key')
                    ->label('Ключ Pix'),

                Tables\Columns\TextColumn::make('bank_info')
                    ->label('Банковские данные'),

                Tables\Columns\TextColumn::make('proof')
                    ->label('Подтверждение')
                    ->html()
                    ->formatStateUsing(fn (string $state): string => '<a href="'.url('storage/'.$state).'" target="_blank">Скачать</a>'),

                Tables\Columns\IconColumn::make('status')
                    ->label('Статус')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('deny_payment')
                    ->label('Отменить')
                    ->icon('heroicon-o-banknotes')
                    ->color('danger')
                    ->visible(fn (Withdrawal $withdrawal): bool => !$withdrawal->status)
                    ->action(function (Withdrawal $withdrawal) {
                        \Filament\Notifications\Notification::make()
                            ->title('Отменить вывод')
                            ->success()
                            ->persistent()
                            ->body('Вы отменяете вывод на сумму ' . \Helper::amountFormatDecimal($withdrawal->amount))
                            ->actions([
                                \Filament\Notifications\Actions\Action::make('view')
                                    ->label('Подтвердить')
                                    ->button()
                                    ->url(route('suitpay.cancelwithdrawal', ['id' => $withdrawal->id]))
                                    ->close(),
                                \Filament\Notifications\Actions\Action::make('undo')
                                    ->color('gray')
                                    ->label('Отмена')
                                    ->action(function (Withdrawal $withdrawal) {
                                        // Действие на случай, если пользователь решит отменить
                                    })
                                    ->close(),
                            ])
                            ->send();
                    }),

                Action::make('approve_payment')
                    ->label('Выплатить')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn (Withdrawal $withdrawal): bool => !$withdrawal->status)
                    ->action(function (Withdrawal $withdrawal) {
                        \Filament\Notifications\Notification::make()
                            ->title('Вывод')
                            ->success()
                            ->persistent()
                            ->body('Вы запрашиваете вывод на сумму ' . \Helper::amountFormatDecimal($withdrawal->amount))
                            ->actions([
                                \Filament\Notifications\Actions\Action::make('view')
                                    ->label('Подтвердить')
                                    ->button()
                                    ->url(route('suitpay.withdrawal', ['id' => $withdrawal->id]))
                                    ->close(),
                                \Filament\Notifications\Actions\Action::make('undo')
                                    ->color('gray')
                                    ->label('Отмена')
                                    ->action(function (Withdrawal $withdrawal) {
                                        // Действие на случай, если пользователь решит отменить
                                    })
                                    ->close(),
                            ])
                            ->send();
                    }),

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    /**
     * @return array|\Filament\Resources\RelationManagers\RelationGroup[]|\Filament\Resources\RelationManagers\RelationManagerConfiguration[]|string[]
     */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWithdrawals::route('/'),
            'create' => Pages\CreateWithdrawal::route('/create'),
            'edit' => Pages\EditWithdrawal::route('/{record}/edit'),
        ];
    }
}
