<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MissionResource\Pages;
use App\Filament\Resources\MissionResource\RelationManagers;
use App\Models\Currency;
use App\Models\GameProvider;
use App\Models\Mission;
use App\Models\Provider;
use App\Models\Wallet;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MissionResource extends Resource
{
    protected static ?string $model = Mission::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    protected static ?string $navigationLabel = 'Миссии';

    protected static ?string $modelLabel = 'Миссии';

    protected static ?string $slug = 'centro-missoes';

    /**
     * @dev @victormsalatiel
     * @return bool
     */
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    /**
     * @param Form $form
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('')
                    ->schema([
                        Forms\Components\TextInput::make('challenge_name')
                            ->label('Название миссии')
                            ->placeholder('Введите название миссии')
                            ->required()
                            ->columnSpanFull()
                            ->maxLength(191),

                        RichEditor::make('challenge_description')
                            ->label('Описание')
                            ->placeholder('Введите описание миссии')
                            ->columnSpanFull(),

                        RichEditor::make('challenge_rules')
                            ->label('Правила')
                            ->placeholder('Введите правила миссии')
                            ->columnSpanFull(),

                        Select::make('challenge_type')
                            ->label('Тип миссии')
                            ->default('game')
                            ->options([
                                'game'      => 'Игра',
                                'wallet'    => 'Кошелёк',
                                'deposit'   => 'Депозит',
                                'affiliate' => 'Аффилиат',
                            ]),

                        Forms\Components\TextInput::make('challenge_link')
                            ->label('Ссылка на миссию')
                            ->maxLength(191),

                        Forms\Components\DateTimePicker::make('challenge_start_date')
                            ->label('Дата начала миссии')
                            ->required(),

                        Forms\Components\DateTimePicker::make('challenge_end_date')
                            ->label('Дата окончания миссии')
                            ->required(),

                        Forms\Components\TextInput::make('challenge_bonus')
                            ->label('Сумма бонуса')
                            ->numeric()
                            ->required()
                            ->default(0.00),

                        Forms\Components\TextInput::make('challenge_total')
                            ->label('Всего миссий')
                            ->numeric()
                            ->required()
                            ->default(1),

                        Select::make('challenge_currency')
                            ->label('Основная валюта')
                            ->options(Currency::all()->pluck('code', 'id'))
                            ->required()
                            ->reactive()
                            ->default(Wallet::where('active', 1)->first()->currency ?? null)
                            ->searchable(),

                        Select::make('challenge_provider')
                            ->label('Провайдер')
                            ->options(Provider::all()->pluck('name', 'id'))
                            ->reactive()
                            ->searchable(),

                        Forms\Components\TextInput::make('challenge_gameid')
                            ->label('ID игры')
                            ->placeholder('Введите ID игры (можно посмотреть в списке игр)')
                            ->columnSpanFull()
                            ->maxLength(191),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('challenge_name')
                    ->label('Название')
                    ->searchable(),

                Tables\Columns\TextColumn::make('challenge_type')
                    ->label('Тип')
                    ->searchable(),

                Tables\Columns\TextColumn::make('challenge_link')
                    ->label('Ссылка')
                    ->searchable(),

                Tables\Columns\TextColumn::make('challenge_start_date')
                    ->label('Начальная дата')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('challenge_end_date')
                    ->label('Конечная дата')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('challenge_bonus')
                    ->label('Сумма приза')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('challenge_total')
                    ->label('Всего')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('challenge_currency')
                    ->label('Валюта')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата создания')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

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
            ]);
    }

    /**
     * @return string[]
     */
    public static function getRelations(): array
    {
        return [
            RelationManagers\UsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMissions::route('/'),
            'create' => Pages\CreateMission::route('/create'),
            'edit' => Pages\EditMission::route('/{record}/edit'),
        ];
    }
}
