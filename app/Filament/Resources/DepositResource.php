<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepositResource\Pages;
use App\Filament\Resources\DepositResource\RelationManagers;
use App\Models\Deposit;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DepositResource extends Resource
{
    protected static ?string $model = Deposit::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';

    protected static ?string $navigationLabel = 'Депозиты';

    protected static ?string $modelLabel = 'Депозиты';

    protected static ?string $navigationGroup = 'Администрирование';

    protected static ?string $slug = 'todos-depositos';

    protected static ?int $navigationSort = 2;

    /**
     * @dev @victormsalatiel
     * @return bool
     */
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    /**
     * @return string|null
     */
    public static function getNavigationBadge(): ?string
    {
        // Количество неподтверждённых (status = 0) депозитов
        return static::getModel()::where('status', 0)->count();
    }

    /**
     * @return string|array|null
     */
    public static function getNavigationBadgeColor(): string|array|null
    {
        // Если больше 5 неподтверждённых депозитов — цвет «success», иначе «warning»
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
                Forms\Components\Section::make('Создание депозита')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Пользователи')
                            ->placeholder('Выберите пользователя')
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
                            ->label('Квитанция')
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

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('payment_id')
                    ->label('ID Платежа')
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Пользователь')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Сумма')
                    ->formatStateUsing(fn (Deposit $record): string => $record->symbol . ' ' . $record->amount)
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Тип')
                    ->searchable(),

                Tables\Columns\TextColumn::make('proof')
                    ->label('Квитанция')
                    ->searchable(),

                Tables\Columns\IconColumn::make('status')
                    ->label('Статус')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата создания')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Дата изменения')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDeposits::route('/'),
            'create' => Pages\CreateDeposit::route('/create'),
            'edit' => Pages\EditDeposit::route('/{record}/edit'),
        ];
    }
}
