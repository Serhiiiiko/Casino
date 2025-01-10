<?php

namespace App\Filament\Widgets;

use App\Models\AffiliateHistory;
use App\Models\Order;
use App\Models\User;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Helpers\Core as Helper;

class StatsUserDetailOverview extends BaseWidget
{
    public User $record;

    public function mount($record)
    {
        $this->record = $record;
    }

    /**
     * @return array|Stat[]
     */
    protected function getStats(): array
    {
        $totalGanhos = Order::where('user_id', $this->record->id)
            ->where('type', 'win')
            ->sum('amount');

        $totalPerdas = Order::where('user_id', $this->record->id)
            ->where('type', 'loss')
            ->sum('amount');

        $totalAfiliados = AffiliateHistory::where('inviter', $this->record->id)
            ->sum('commission_paid');

        return [
            Stat::make('Общая сумма выигрышей', Helper::amountFormatDecimal(Helper::formatNumber($totalGanhos)))
                ->description('Суммарные выигрыши на платформе')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Общая сумма проигрышей', Helper::amountFormatDecimal(Helper::formatNumber($totalPerdas)))
                ->description('Суммарные проигрыши на платформе')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),

            Stat::make('Заработано как аффилиат', Helper::amountFormatDecimal(Helper::formatNumber($totalAfiliados)))
                ->description('Общая сумма, заработанная как аффилиат')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}
