<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MobileModuleResource\Pages;
use App\Filament\Resources\MobileModuleResource\RelationManagers;
use App\Models\MobileModule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MobileModuleResource extends Resource
{
    protected static ?string $model = MobileModule::class;

    protected static ?string $navigationIcon = 'heroicon-o-device-phone-mobile';

    protected static ?string $navigationGroup = 'Mobile';

    protected static ?string $navigationLabel = 'Modul Mobile';

    protected static ?string $modelLabel = 'Modul Mobile';

    protected static ?string $pluralModelLabel = 'Data Modul Mobile';

    protected static ?int $navigationSort = -90;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Modul Mobile')
                    ->schema([
                        Forms\Components\TextInput::make('id')
                            ->label('ID')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('name')
                            ->label('Nama Modul Mobile')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Masukkan nama modul mobile')
                            ->columnSpan(2),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(4)
                            ->required()
                            ->maxLength(500)
                            ->placeholder('Tuliskan deskripsi modul di sini...')
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color('gray')
                    ->size('sm'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Modul')
                    ->sortable()
                    ->searchable()
                    ->weight('semibold')
                    ->color('secondary')
                    ->size('lg'),

                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->searchable()
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return $state ?: null;
                    })
                    ->color('gray')
                    ->size('sm'),

                Tables\Columns\TextColumn::make('mobile_features_count')
                    ->counts('mobileFeatures')
                    ->label('Jumlah Fitur Mobile')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('total_views')
                    ->label('Total Dilihat')
                    ->getStateUsing(fn($record) => $record->total_views)
                    ->badge()
                    ->color('info'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('name')
                    ->label('Filter berdasarkan Modul')
                    ->options(function () {
                        return \App\Models\MobileModule::query()
                            ->pluck('name', 'name')
                            ->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->placeholder('Semua Modul'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label('Lihat')
                        ->url(fn ($record) => static::getUrl('view', ['record' => $record])),
                    Tables\Actions\EditAction::make()
                        ->label('Edit')
                        ->color('primary')
                        ->icon('heroicon-o-pencil'),
                ])
                    ->label('Aksi')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->size('sm')
                    ->color('primary')
                    ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus yang Dipilih')
                        ->color('danger')
                        ->icon('heroicon-o-trash'),
                ]),
            ])
            ->defaultSort('updated_at', 'desc')
            ->recordUrl(null)
            ->striped()
            ->deferLoading();
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\MobileFeaturesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMobileModules::route('/'),
            'create' => Pages\CreateMobileModule::route('/create'),
            'view' => Pages\ViewMobileModule::route('/{record}'),
            'edit' => Pages\EditMobileModule::route('/{record}/edit'),
        ];
    }
}