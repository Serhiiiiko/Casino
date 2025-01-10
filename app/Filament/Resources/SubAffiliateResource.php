<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubAffiliateResource\Pages;
use App\Filament\Resources\SubAffiliateResource\Widgets\SubAffiliateOverview;
use App\Models\SubAffiliate;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SubAffiliateResource extends Resource
{
    protected static ?string $model = SubAffiliate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Суб-аффилиаты';

    protected static ?string $modelLabel = 'Суб-аффилиаты';

    /**
     * @dev @victormsalatiel
     * @return bool
     */
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('afiliado');
    }

    /**
     * @param Table $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        // Получаем всех пользователей, у которых inviter = id текущего пользователя
        $usersIds = User::where('inviter', auth()->id())->pluck('id');

        return $table
            ->query(User::query()->whereIn('id', $usersIds))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Имя')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label('Подтверждение E-mail')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

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
                // Здесь можно добавить действия, например, Edit, View, Delete и т.д.
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Здесь можно добавить BulkActions
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * @return string[]
     */
    public static function getWidgets(): array
    {
        return [
            SubAffiliateOverview::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubAffiliates::route('/'),
        ];
    }
}
