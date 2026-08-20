<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgentTransactionResource\Pages;
use App\Models\AgentTransaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AgentTransactionResource extends Resource
{
    protected static ?string $model = AgentTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?string $navigationLabel = 'Transaksi Agent';

    protected static ?string $modelLabel = 'Transaksi Agent';

    protected static ?string $pluralModelLabel = 'Daftar Transaksi Agent';

    protected static ?string $navigationGroup = 'SaaS Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('agent_id')
                            ->relationship('agent', 'agent_name')
                            ->disabled()
                            ->required(),
                        Forms\Components\TextInput::make('transaction_type')
                            ->required()
                            ->disabled(),
                        Forms\Components\TextInput::make('amount')
                            ->required()
                            ->disabled()
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('balance_before')
                            ->required()
                            ->disabled()
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('balance_after')
                            ->required()
                            ->disabled()
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('profile_name')
                            ->disabled(),
                        Forms\Components\TextInput::make('voucher_username')
                            ->disabled(),
                        Forms\Components\TextInput::make('quantity')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\Textarea::make('description')
                            ->disabled()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('reference_id')
                            ->disabled(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('agent.agent_name')
                    ->label('Agent')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('agent.owner.username')
                    ->label('Tenant Owner')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transaction_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'topup' => 'success',
                        'generate' => 'info',
                        'refund' => 'warning',
                        'commission' => 'success',
                        'penalty' => 'danger',
                        'digiflazz' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('idr')
                    ->sortable(),
                Tables\Columns\TextColumn::make('balance_after')
                    ->label('Saldo Akhir')
                    ->money('idr')
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('reference_id')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('transaction_type')
                    ->options([
                        'topup' => 'Topup',
                        'generate' => 'Generate Voucher',
                        'refund' => 'Refund',
                        'commission' => 'Commission',
                        'penalty' => 'Penalty',
                        'digiflazz' => 'Digiflazz',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Read-only logs
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAgentTransactions::route('/'),
        ];
    }
}
