<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\GGRGamesFiver;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class MyBetsTableWidget extends BaseWidget
{
    protected static ?string $heading = 'Все ставки';

    protected static ?int $navigationSort = -1;

    /**
     * В Filament, как правило, используется значение 'full'.
     * Использование другого текста (напр. 'полный') может привести к некорректному отображению.
     */
    protected int | string | array $columnSpan = 'full'; 

    public User $record;

    /**
     * @param Table $table
     * @return Table
     */
    public function table(Table $table): Table
    {
        // \Log::info('ДАННЫЕ XXXXXXXXXXX ' . json_encode($this->record->id));

        return $table
            ->query(Order::query()->where('user_id', $this->record->id))
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('game')
                    ->label('Игра')
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Тип')
                    ->badge()
                    ->searchable(),

                /**
                 * Цвет также обычно задаётся как 'success', 'danger' и т.д. 
                 * Если указать 'успешный', Filament не распознает такой цвет.
                 */
                Tables\Columns\TextColumn::make('type_money')
                    ->label('Тип транзакции')
                    ->badge()
                    ->color('success') // Если указать 'успешный', Filament не поймёт
                    ->searchable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Цена')
                    ->money('BRL')
                    ->searchable(),

                Tables\Columns\TextColumn::make('providers')
                    ->label('Поставщик')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата')
                    ->dateTime()
                    ->sortable(),
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
                                'Конец создания ' . Carbon::parse($data['created_until'])->toFormattedDateString();
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
