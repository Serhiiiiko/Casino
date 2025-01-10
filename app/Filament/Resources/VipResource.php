<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VipResource\Pages;
use App\Filament\Resources\VipResource\RelationManagers;
use App\Models\Vip;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VipResource extends Resource
{
    protected static ?string $model = Vip::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationLabel = 'VIP';

    protected static ?string $modelLabel = 'VIP';

    protected static ?string $slug = 'vip';

    /**
     * @dev @victormsalatiel
     * @return bool
     */
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('VIP')
                    ->description('Зарегистрируйте свой VIP-список бонусов')
                    ->schema([
                        FileUpload::make('bet_symbol')
                            ->label('Символ')
                            ->placeholder('Загрузите символ')
                            ->image()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('bet_level')
                            ->label('Уровень')
                            ->required()
                            ->placeholder('Сохраняйте числовой порядок в списке'),
                        Forms\Components\TextInput::make('bet_required')
                            ->label('Необходимая ставка')
                            ->required()
                            ->placeholder('Укажите требуемую ставку для получения приза')
                            ->numeric(),
                        Forms\Components\Select::make('bet_period')
                            ->label('Период')
                            ->options([
                                'weekly' => 'Еженедельно',
                                'monthly' => 'Ежемесячно',
                                'yearly' => 'Ежегодно',
                            ]),
                        Forms\Components\TextInput::make('bet_bonus')
                            ->label('Бонус')
                            ->placeholder('Введите сумму бонуса')
                            ->required()
                            ->numeric(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('bet_symbol')
                    ->label('Изображение'),

                Tables\Columns\TextColumn::make('bet_level')
                    ->label('Уровень')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('bet_required')
                    ->label('Необходимая ставка')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('bet_period')
                    ->label('Период')
                    ->searchable(),

                Tables\Columns\TextColumn::make('bet_bonus')
                    ->label('Бонус')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVips::route('/'),
            'create' => Pages\CreateVip::route('/create'),
            'edit' => Pages\EditVip::route('/{record}/edit'),
        ];
    }
}
