<?php

namespace App\Filament\Resources\SaasPaymentResource\Pages;

use App\Filament\Resources\SaasPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSaasPayments extends ListRecords
{
    protected static string $resource = SaasPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            SaasPaymentResource\Widgets\SaasPaymentStatsOverview::class,
        ];
    }
}
