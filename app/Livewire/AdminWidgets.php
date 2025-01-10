<?php

namespace App\Livewire;

use App\Models\AffiliateHistory;
use App\Models\User;
use App\Models\Wallet;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminWidgets extends BaseWidget
{
    protected static ?int $navigationSort = -2;

    /**
     * @return array|Stat[]
     */
    protected function getCards(): array
    {
        if (auth()->check()) {
            $inviterId = auth()->user()->id;
            $usersIds = User::where('inviter', $inviterId)->pluck('id');

            if (! empty($usersIds)) {
                $comissaoRevshare = AffiliateHistory::whereIn('user_id', $usersIds)
                    ->where('commission_type', 'revshare')
                    ->sum('commission_paid');

                $comissaoCPAs = AffiliateHistory::whereIn('user_id', $usersIds)
                    ->where('commission_type', 'cpa')
                    ->sum('commission_paid');

                $lossesRev = AffiliateHistory::whereIn('user_id', $usersIds)
                    ->where('commission_type', 'revshare')
                    ->sum('losses_amount');
            }
        } else {
            $comissaoRevshare = 0;
            $comissaoCPAs     = 0;
            $lossesRev        = 0;
        }

        return [
            Stat::make('CPA-комиссия', \Helper::amountFormatDecimal($comissaoCPAs))
                ->description('CPA-комиссия')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Revshare-комиссия', \Helper::amountFormatDecimal($comissaoRevshare))
                ->description('Revshare-комиссия')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Убытки', \Helper::amountFormatDecimal($lossesRev))
                ->description('Убытки привлечённых пользователей')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),
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
