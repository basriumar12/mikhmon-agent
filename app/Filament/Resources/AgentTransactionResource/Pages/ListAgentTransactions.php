<?php

namespace App\Filament\Resources\AgentTransactionResource\Pages;

use App\Filament\Resources\AgentTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAgentTransactions extends ListRecords
{
    protected static string $resource = AgentTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
