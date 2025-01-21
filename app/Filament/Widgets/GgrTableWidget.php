<?php

namespace App\Filament\Widgets;

use App\Models\GGRGamesFiver;
use App\Models\GgrGamesWorldSlot;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class GgrTableWidget extends BaseWidget
{
    protected static ?string $heading = 'GGR World Slot';

    protected static ?int $navigationSort = -1;

    protected int | string | array $columnSpan = 'full';

    /**
     * @param Table $table
     * @return Table
     */
    public function table(Table $table): Table
    {
        return $table
            ->query(GgrGamesWorldSlot::query())
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Пользователь'),

                Tables\Columns\TextColumn::make('provider')
                    ->label('Провайдер')
                    ->searchable(),

                Tables\Columns\TextColumn::make('game')
                    ->label('Игра')
                    ->searchable(),

                Tables\Columns\TextColumn::make('balance_bet')
                    ->money('RUB')
                    ->label('Ставка'),

                Tables\Columns\TextColumn::make('balance_win')
                    ->money('RUB')
                    ->label('Выигрыш'),

                Tables\Columns\TextColumn::make('dateHumanReadable')
                    ->label('Дата'),
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->label('Начальная дата'),
                        DatePicker::make('created_until')->label('Конечная дата'),
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
                            $indicators['created_from'] =
                                'Начало создания ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }

                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] =
                                'Окончание создания ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
            ]);
    }

    /**
     * @return bool
     */
    public static function canView(): bool
    {
        return auth()->user()->hasRole('admin');
    }
}
