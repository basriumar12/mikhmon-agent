<?php

namespace App\Filament\Owner\Widgets;

use Filament\Widgets\Widget;

class SaaSSubscriptionWidget extends Widget
{
    protected static string $view = 'filament.owner.widgets.saas-subscription-widget';

    protected int | string | array $columnSpan = 'full';
}
