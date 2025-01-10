<?php

namespace App\Livewire;

use App\Models\AffiliateHistory;
use App\Models\User;
use App\Models\Wallet;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AffiliateWidgets extends BaseWidget
{
    protected static ?int $navigationSort = -2;

    /**
     * @return array|Stat[]
     */
    protected function getCards(): array
    {
        $inviterId     = auth()->user()->id;
        $usersIds      = User::where('inviter', $inviterId)->pluck('id');
        $usersTotal    = User::where('inviter', $inviterId)->count();
        $comissaoTotal = Wallet::whereIn('user_id', $usersIds)->sum('refer_rewards');

        return [
            Stat::make('Сумма к получению', \Helper::amountFormatDecimal($comissaoTotal))
                ->description('Сумма, доступная к получению')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Доступный баланс', \Helper::amountFormatDecimal(0))
                ->description('Баланс, доступный к выводу')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Регистрации', $usersTotal)
                ->description('Пользователи, зарегистрировавшиеся по моей ссылке')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }

    /**
     * @return bool
     */
    public static function canView(): bool
    {
        return auth()->user()->hasRole('afiliado');
    }
}
