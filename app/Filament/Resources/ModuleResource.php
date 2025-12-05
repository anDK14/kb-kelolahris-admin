<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModuleResource\Pages;
use App\Filament\Resources\ModuleResource\RelationManagers;
use App\Models\Module;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ModuleResource extends Resource
{
    protected static ?string $model = Module::class;

    protected static ?string $navigationIcon = 'heroicon-m-computer-desktop';

    protected static ?string $navigationGroup = 'Website';

    protected static ?string $navigationLabel = 'Modul Website';

    protected static ?string $modelLabel = 'Modul Website';

    protected static ?string $pluralModelLabel = 'Data Modul Website';

    protected static ?int $navigationSort = -100;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Modul Website')
                    ->schema([
                        Forms\Components\TextInput::make('id')
                            ->label('ID')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('name')
                            ->label('Nama Modul Website')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Masukkan nama modul website')
                            ->columnSpan(2),
                        
                        // TAMBAHKAN FIELD SLUG DI SINI
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true)
                            ->placeholder('contoh: master-data')
                            ->helperText('URL-friendly identifier untuk modul')
                            ->columnSpan(2),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(4)
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
                    ->color('primary')
                    ->size('lg'),
                
                // TAMBAHKAN COLUMN SLUG DI SINI
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->sortable()
                    ->searchable()
                    ->color('gray')
                    ->size('sm')
                    ->copyable()
                    ->copyMessage('Slug berhasil disalin')
                    ->toggleable(isToggledHiddenByDefault: false),

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

                Tables\Columns\TextColumn::make('submodules_count')
                    ->counts('submodules')
                    ->label('Jumlah Fitur Website')
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
                        return \App\Models\Module::query()
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
                        ->url(fn($record) => static::getUrl('view', ['record' => $record])),
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
            RelationManagers\SubmodulesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListModules::route('/'),
            'create' => Pages\CreateModule::route('/create'),
            'view' => Pages\ViewModule::route('/{record}'),
            'edit' => Pages\EditModule::route('/{record}/edit'),
        ];
    }
}