<?php

namespace App\Filament\Owner\Resources\RouterSessionResource\Pages;

use App\Filament\Owner\Resources\RouterSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRouterSessions extends ListRecords
{
    protected static string $resource = RouterSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
