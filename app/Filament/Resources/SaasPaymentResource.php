<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaasPaymentResource\Pages;
use App\Models\SaasPayment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SaasPaymentResource extends Resource
{
    protected static ?string $model = SaasPayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Transaksi SaaS';

    protected static ?string $modelLabel = 'Transaksi SaaS';

    protected static ?string $pluralModelLabel = 'Daftar Transaksi SaaS';

    protected static ?string $navigationGroup = 'SaaS Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('owner_id')
                            ->relationship('owner', 'username')
                            ->disabled()
                            ->required(),
                        Forms\Components\TextInput::make('order_id')
                            ->required()
                            ->disabled()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('plan_slug')
                            ->required()
                            ->disabled()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('amount')
                            ->required()
                            ->disabled()
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'unpaid' => 'Unpaid',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('payment_method')
                            ->maxLength(50)
                            ->disabled(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('owner.username')
                    ->label('Tenant Owner')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('order_id')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('plan_slug')
                    ->label('Plan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('idr')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'unpaid' => 'gray',
                        'failed' => 'danger',
                        'cancelled' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status Transaksi')
                    ->options([
                        'paid' => 'Lunas (Paid)',
                        'unpaid' => 'Belum Bayar (Unpaid)',
                        'failed' => 'Gagal (Failed)',
                        'cancelled' => 'Dibatalkan (Cancelled)',
                    ]),
                Tables\Filters\SelectFilter::make('plan_slug')
                    ->label('Paket Langganan')
                    ->options([
                        'bronze' => 'Bronze',
                        'silver' => 'Silver',
                        'gold' => 'Gold',
                        'platinum' => 'Platinum',
                    ]),
                Tables\Filters\Filter::make('period')
                    ->label('Periode Transaksi')
                    ->form([
                        Forms\Components\Select::make('period_type')
                            ->label('Pilih Periode')
                            ->options([
                                'today' => 'Hari Ini',
                                'this_week' => 'Minggu Ini',
                                'this_month' => 'Bulan Ini',
                                'this_year' => 'Tahun Ini',
                            ]),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                        return $query->when(
                            $data['period_type'] === 'today',
                            fn ($q) => $q->whereDate('created_at', now()->today())
                        )->when(
                            $data['period_type'] === 'this_week',
                            fn ($q) => $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                        )->when(
                            $data['period_type'] === 'this_month',
                            fn ($q) => $q->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)
                        )->when(
                            $data['period_type'] === 'this_year',
                            fn ($q) => $q->whereYear('created_at', now()->year)
                        );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('mark_paid')
                    ->label('Tandai Lunas')
                    ->color('success')
                    ->icon('heroicon-m-check-circle')
                    ->visible(fn (SaasPayment $record): bool => $record->status !== 'paid')
                    ->action(function (SaasPayment $record) {
                        $record->update(['status' => 'paid']);
                        
                        // Extend target owner subscription
                        if ($record->owner) {
                            $plan = \App\Models\SaasPlan::where('slug', $record->plan_slug)->first();
                            $expiresAt = now()->addMonth();
                            if ($plan) {
                                if ($plan->billing_period === 'yearly') {
                                    $expiresAt = now()->addYear();
                                } elseif ($plan->billing_period === '7_days') {
                                    $expiresAt = now()->addDays(7);
                                }
                            }
                            $record->owner->update([
                                'status' => 'active',
                                'level' => $record->plan_slug,
                                'subscription_expires_at' => $expiresAt
                            ]);
                        }
                    })
                    ->requiresConfirmation(),
                Tables\Actions\Action::make('mark_cancelled')
                    ->label('Batalkan')
                    ->color('danger')
                    ->icon('heroicon-m-x-circle')
                    ->visible(fn (SaasPayment $record): bool => $record->status === 'unpaid')
                    ->action(function (SaasPayment $record) {
                        $record->update(['status' => 'cancelled']);
                    })
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSaasPayments::route('/'),
        ];
    }
}
