<?php

namespace App\Filament\Resources\SaasPaymentResource\Widgets;

use App\Models\Owner;
use App\Models\SaasPayment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SaasPaymentStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalRevenue = SaasPayment::where('status', 'paid')->sum('amount');
        $monthlyRevenue = SaasPayment::where('status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
        $weeklyRevenue = SaasPayment::where('status', 'paid')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('amount');
        $activeMembers = Owner::where('status', 'active')->count();

        return [
            Stat::make('Total Pendapatan SaaS', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Total pemasukan dari transaksi lunas')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Pemasukan Bulan Ini', 'Rp ' . number_format($monthlyRevenue, 0, ',', '.'))
                ->description('Omset bulan ' . now()->translatedFormat('F Y'))
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),

            Stat::make('Pemasukan Minggu Ini', 'Rp ' . number_format($weeklyRevenue, 0, ',', '.'))
                ->description('Omset minggu ini')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('primary'),

            Stat::make('Member / Tenant Aktif', $activeMembers . ' Member')
                ->description('Jumlah owner aktif terdaftar')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning'),
        ];
    }
}
