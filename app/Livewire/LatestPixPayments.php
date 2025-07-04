<?php

namespace App\Livewire;

use App\Models\SuitPayPayment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestPixPayments extends BaseWidget
{
    protected static ?string $heading = 'Совершённые платежи';

    protected static ?int $navigationSort = -1;

    protected int | string | array $columnSpan = 'full';

    /**
     * @param Table $table
     * @return Table
     */
    public function table(Table $table): Table
    {
        return $table
            ->query(SuitPayPayment::query())
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('payment_id')
                    ->label('ID платежа'),
                Tables\Columns\TextColumn::make('pix_key')
                    ->label('Ключ Pix'),
                Tables\Columns\TextColumn::make('pix_type')
                    ->label('Тип ключа'),
                Tables\Columns\TextColumn::make('amount')
                    ->money('RUB')
                    ->label('Сумма'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pendente' => 'warning',
                        'pago' => 'success',
                    }),
                Tables\Columns\TextColumn::make('dateHumanReadable')
                    ->label('Дата'),
            ]);
    }

    /**
     * @return bool
     */
    public static function canView(): bool
    {
        // По умолчанию возвращаем true (разрешаем просмотр).
        // Если нужно ограничить просмотр только админом, верните:
        // return auth()->user()->hasRole('admin');
        return true;
    }
}
