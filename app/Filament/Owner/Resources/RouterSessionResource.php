<?php

namespace App\Filament\Owner\Resources;

use App\Filament\Owner\Resources\RouterSessionResource\Pages;
use App\Filament\Owner\Resources\RouterSessionResource\RelationManagers;
use App\Models\RouterSession;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RouterSessionResource extends Resource
{
    protected static ?string $model = RouterSession::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('owner_id', auth('owners')->id());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Hidden::make('owner_id')
                            ->default(fn () => auth('owners')->id()),
                        Forms\Components\TextInput::make('session_name')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('ip_address')
                            ->required()
                            ->placeholder('10.20.30.1:8728')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('username')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required()
                            ->dehydrateStateUsing(fn ($state) => $state)
                            ->formatStateUsing(fn ($record) => $record ? $record->decrypted_password : '')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('hotspot_name')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('dns_name')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('currency')
                            ->default('Rp')
                            ->required()
                            ->maxLength(20),
                        Forms\Components\TextInput::make('auto_reload')
                            ->default('10')
                            ->required()
                            ->maxLength(20),
                        Forms\Components\TextInput::make('interface')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('info_limit')
                            ->default('2')
                            ->required()
                            ->maxLength(20),
                        Forms\Components\TextInput::make('idle_timeout')
                            ->maxLength(20),
                        Forms\Components\Select::make('live_report')
                            ->options([
                                'enable' => 'Enable',
                                'disable' => 'Disable',
                            ])
                            ->default('enable')
                            ->required(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('session_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ip_address')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hotspot_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dns_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('live_report')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'enable' ? 'success' : 'gray'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRouterSessions::route('/'),
            'create' => Pages\CreateRouterSession::route('/create'),
            'edit' => Pages\EditRouterSession::route('/{record}/edit'),
        ];
    }
}
