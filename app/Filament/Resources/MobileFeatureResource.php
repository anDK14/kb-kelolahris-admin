<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MobileFeatureResource\Pages;
use App\Filament\Resources\MobileFeatureResource\RelationManagers;
use App\Models\MobileFeature;
use App\Models\MobileModule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MobileFeatureResource extends Resource
{
    protected static ?string $model = MobileFeature::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationGroup = 'Mobile';

    protected static ?string $navigationLabel = 'Fitur Mobile';

    protected static ?string $modelLabel = 'Fitur Mobile';

    protected static ?string $pluralModelLabel = 'Data Fitur Mobile';

    protected static ?int $navigationSort = -80;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Fitur Mobile')
                    ->schema([
                        Forms\Components\TextInput::make('id')
                            ->label('ID')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Select::make('mobile_module_id')
                            ->label('Nama Modul Mobile')
                            ->required()
                            ->relationship('mobileModule', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih modul terkait')
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Modul')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Masukkan nama modul mobile'),
                                Forms\Components\Textarea::make('description')
                                    ->label('Deskripsi')
                                    ->rows(4)
                                    ->maxLength(500)
                                    ->placeholder('Tuliskan deskripsi modul di sini...'),
                            ]),

                        Forms\Components\TextInput::make('name')
                            ->label('Nama Fitur Mobile')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Masukkan nama fitur mobile'),
                        
                        // TAMBAHKAN FIELD SLUG DI SINI
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true)
                            ->placeholder('contoh: check-in')
                            ->helperText('URL-friendly identifier untuk fitur mobile'),

                        Forms\Components\TextInput::make('view_count')
                            ->label('Jumlah Dilihat')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(4)
                            ->maxLength(500)
                            ->placeholder('Tuliskan deskripsi fitur di sini...')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
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
                    ->label('Nama Fitur')
                    ->sortable()
                    ->searchable()
                    ->weight('semibold')
                    ->color('secondary')
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

                Tables\Columns\TextColumn::make('view_count')
                    ->label('Jumlah Dilihat')
                    ->sortable()
                    ->numeric()
                    ->badge()
                    ->color(fn($state) => $state > 0 ? 'success' : 'gray')
                    ->icon(fn($state) => $state > 0 ? 'heroicon-o-eye' : 'heroicon-o-eye-slash'),

                Tables\Columns\TextColumn::make('mobileModule.name')
                    ->label('Modul')
                    ->sortable()
                    ->searchable()
                    ->placeholder('Tidak ada modul')
                    ->color('primary')
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('mobileModule.id')
                    ->label('ID Modul')
                    ->sortable()
                    ->searchable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color('gray'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('mobileModule')
                    ->label('Filter berdasarkan Modul')
                    ->relationship('mobileModule', 'name')
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
            RelationManagers\MobileFeatureContentsRelationManager::class,
            RelationManagers\FaqsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMobileFeatures::route('/'),
            'create' => Pages\CreateMobileFeature::route('/create'),
            'view' => Pages\ViewMobileFeature::route('/{record}'),
            'edit' => Pages\EditMobileFeature::route('/{record}/edit'),
        ];
    }
}