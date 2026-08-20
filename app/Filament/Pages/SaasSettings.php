<?php

namespace App\Filament\Pages;

use App\Models\SaasSetting;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class SaasSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Pengaturan SaaS';

    protected static ?string $title = 'Konfigurasi SaaS & Pembayaran';

    protected static ?string $navigationGroup = 'SaaS Management';

    protected static string $view = 'filament.pages.saas-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'sumopod_api_key' => SaasSetting::get('sumopod_api_key'),
            'sumopod_endpoint' => SaasSetting::get('sumopod_endpoint', 'https://api-pay-sandbox.sumopod.com/api/v1/payments'),
            'sumopod_mode' => SaasSetting::get('sumopod_mode', 'sandbox'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('sumopod_api_key')
                            ->label('Sumopod API Key')
                            ->password()
                            ->revealable()
                            ->required(),
                        TextInput::make('sumopod_endpoint')
                            ->label('Sumopod API Endpoint')
                            ->url()
                            ->required(),
                        Select::make('sumopod_mode')
                            ->label('Sumopod Mode')
                            ->options([
                                'sandbox' => 'Sandbox (Testing)',
                                'live' => 'Live (Production)',
                            ])
                            ->required(),
                    ])
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            SaasSetting::set($key, $value);
        }

        Notification::make()
            ->title('Pengaturan berhasil disimpan')
            ->success()
            ->send();
    }
}
