<?php

namespace App\Filament\Owner\Pages;

use Filament\Pages\Page;

class Subscription extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $title = 'Langganan & Upgrade';

    protected static ?string $navigationLabel = 'Langganan & Upgrade';

    protected static string $view = 'filament.owner.pages.subscription';
}
