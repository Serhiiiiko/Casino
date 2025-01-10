<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WalletResource\Pages;
use App\Filament\Resources\WalletResource\RelationManagers;
use App\Models\Category;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WalletResource extends Resource
{
    protected static ?string $model = Wallet::class;

    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    protected static ?string $navigationLabel = 'Кошельки';

    protected static ?string $modelLabel = 'Кошельки';

    protected static ?string $navigationGroup = 'Администрирование';

    protected static ?string $slug = 'minha-carteira';

    protected static ?int $navigationSort = 1;

    /**
     * @dev @victormsalatiel
     * @return bool
     */
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    /**
     * @return bool
     */
    public static function canCreate(): bool
    {
        // В данном случае возвращаем false, чтобы запретить создание кошельков
        return false;
    }

    /**
     * @param Model $record
     * @return string
     */
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        // Возвращаем имя пользователя (для отображения в глобальном поиске)
        return $record->user->name;
    }

    /**
     * @return string[]
     */
    public static function getGloballySearchableAttributes(): array
    {
        // Позволяет искать по имени и e-mail пользователя
        return ['user.name', 'user.email'];
    }

    /**
     * @param Form $form
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Пользователь')
                    ->description('Выберите пользователя')
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
                            ->live(),
                    ]),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('balance')
                            ->label('Баланс')
                            ->required()
                            ->numeric()
                            ->default(0.00),

                        Forms\Components\TextInput::make('balance_bonus')
                            ->label('Бонусный баланс')
                            ->required()
                            ->numeric()
                            ->default(0.00),

                        Forms\Components\TextInput::make('refer_rewards')
                            ->label('Баланс аффилиата')
                            ->required()
                            ->numeric()
                            ->default(0.00),

                        Forms\Components\TextInput::make('balance_demo')
                            ->label('Баланс для Influencer')
                            ->required()
                            ->numeric()
                            ->default(0.00),

                        Forms\Components\TextInput::make('balance_withdrawal')
                            ->label('Баланс для вывода')
                            ->required()
                            ->numeric()
                            ->default(0.00),
                    ])
                    ->columns(5),
            ]);
    }

    /**
     * @param Table $table
     * @return Table
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Пользователь')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('balance')
                    ->label('Баланс')
                    ->formatStateUsing(fn (string $state): string => \Helper::amountFormatDecimal($state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('balance_withdrawal')
                    ->label('Баланс для вывода')
                    ->formatStateUsing(fn (string $state): string => \Helper::amountFormatDecimal($state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('balance_bonus')
                    ->label('Бонус')
                    ->formatStateUsing(fn (string $state): string => \Helper::amountFormatDecimal($state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('balance_bonus_rollover')
                    ->label('Rollover-баланс')
                    ->formatStateUsing(fn (string $state): string => \Helper::amountFormatDecimal($state))
                    ->sortable(),

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
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->label('Создан от'),
                        DatePicker::make('created_until')->label('Создан до'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Создан от ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }

                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Создан до ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
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
                // По умолчанию отключено, т. к. canCreate() вернёт false
                //Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListWallets::route('/'),
            'create' => Pages\CreateWallet::route('/create'),
            'edit' => Pages\EditWallet::route('/{record}/edit'),
        ];
    }
}
