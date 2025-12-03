<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WebsiteFeatureContentResource\Pages;
use App\Filament\Resources\WebsiteFeatureContentResource\RelationManagers;
use App\Models\WebsiteFeatureContent;
use App\Models\Submodule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WebsiteFeatureContentResource extends Resource
{
    protected static ?string $model = WebsiteFeatureContent::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Website';

    protected static ?string $navigationLabel = 'Konten Website';

    protected static ?string $modelLabel = 'Konten Website';

    protected static ?string $pluralModelLabel = 'Data Konten Website';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Konten Website')
                    ->schema([
                        // ID (non-editable)
                        Forms\Components\TextInput::make('id')
                            ->label('ID')
                            ->disabled()
                            ->dehydrated(false),

                        // Tipe Konten (fixed & readonly)
                        Forms\Components\Select::make('content_type')
                            ->label('Tipe Konten')
                            ->options([
                                'fitur_utama' => 'Fitur Utama',
                                'panduan_langkah' => 'Panduan Langkah',
                                'contoh_tampilan' => 'Contoh Tampilan',
                                'tip_box' => 'Tip Box',
                            ])
                            ->searchable()
                            ->default(fn() => request()->query('content_type'))
                            ->disabled()
                            ->dehydrated(true)
                            ->reactive(),

                        // Nama Fitur Website
                        Forms\Components\Select::make('submodule_id')
                            ->label('Nama Fitur Website')
                            ->required()
                            ->relationship('submodule', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih fitur terkait')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                static::updateContentOrder($set, $get);
                            })
                            ->createOptionForm([
                                Forms\Components\Select::make('module_id')
                                    ->label('Modul Website')
                                    ->relationship('module', 'name')
                                    ->required()
                                    ->placeholder('Pilih modul terkait'),
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Fitur')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Masukkan nama fitur website'),
                                Forms\Components\Textarea::make('description')
                                    ->label('Deskripsi')
                                    ->rows(2)
                                    ->placeholder('Masukkan deskripsi fitur...'),
                            ]),

                        // Urutan (auto berdasarkan fitur & tipe konten)
                        Forms\Components\TextInput::make('content_order')
                            ->label('Urutan')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(true)
                            ->default(1)
                            ->hint('Nomor urut otomatis berdasarkan tipe konten & fitur'),

                        // Judul
                        Forms\Components\TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Masukkan judul konten'),

                        // Path Gambar (hanya untuk tipe "contoh_tampilan")
                        Forms\Components\Textarea::make('image_path')
                            ->label('Path Gambar')
                            ->required()
                            ->rows(2)
                            ->placeholder('Masukkan lokasi penyimpanan gambar')
                            ->visible(fn(callable $get) => $get('content_type') === 'contoh_tampilan'),

                        // Deskripsi (default)
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->required()
                            ->rows(6)
                            ->columnSpanFull()
                            ->placeholder('Masukkan deskripsi konten.')
                            ->visible(fn(callable $get) => $get('content_type') !== 'panduan_langkah'),

                        // Deskripsi (khusus panduan langkah)
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->required()
                            ->rows(12)
                            ->columnSpanFull()
                            ->placeholder("Masukkan deskripsi konten.\nGunakan tombol Tambah MAIN_STEP dan Tambah SUB_STEP di atas untuk menambahkan format langkah secara otomatis.\nHasilnya seperti:\nMAIN_STEP:  Ketik langkah utama di sini\n    SUB_STEP:  Ketik langkah sub di sini")
                            ->hintActions([
                                Forms\Components\Actions\Action::make('insertMainStep')
                                    ->label('Tambah MAIN_STEP')
                                    ->icon('heroicon-o-plus')
                                    ->color('primary')
                                    ->action(function ($state, callable $set) {
                                        $set('description', trim($state . "\nMAIN_STEP: "));
                                    }),

                                Forms\Components\Actions\Action::make('insertSubStep')
                                    ->label('Tambah SUB_STEP')
                                    ->icon('heroicon-o-plus')
                                    ->color('secondary')
                                    ->action(function ($state, callable $set) {
                                        $set('description', trim($state . "\n   SUB_STEP: "));
                                    }),
                            ])
                            ->visible(fn(callable $get) => $get('content_type') === 'panduan_langkah'),
                    ])
                    ->columns(2),
            ]);
    }

    /**
     * Hitung urutan konten berdasarkan fitur & tipe konten
     */
    protected static function updateContentOrder(callable $set, callable $get): void
    {
        $submoduleId = $get('submodule_id');
        $contentType = $get('content_type');

        if (blank($submoduleId) || blank($contentType)) {
            $set('content_order', 1);
            return;
        }

        $lastOrder = WebsiteFeatureContent::where('submodule_id', $submoduleId)
            ->where('content_type', $contentType)
            ->max('content_order');

        $set('content_order', $lastOrder ? $lastOrder + 1 : 1);
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

                Tables\Columns\TextColumn::make('submodule.id')
                    ->label('ID Fitur')
                    ->sortable()
                    ->searchable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color('gray')
                    ->size('sm'),

                Tables\Columns\TextColumn::make('submodule.name')
                    ->label('Nama Fitur')
                    ->sortable()
                    ->searchable()
                    ->placeholder('Tidak ada fitur')
                    ->weight('semibold')
                    ->color('primary')
                    ->size('lg'),

                Tables\Columns\TextColumn::make('submodule.module.name')
                    ->label('Modul')
                    ->sortable()
                    ->searchable()
                    ->placeholder('-')
                    ->color('secondary')
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('content_type')
                    ->label('Tipe Konten')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'fitur_utama' => 'Fitur Utama',
                        'panduan_langkah' => 'Panduan Langkah',
                        'contoh_tampilan' => 'Contoh Tampilan',
                        'tip_box' => 'Tip Box',
                        default => $state,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'fitur_utama' => 'primary',
                        'panduan_langkah' => 'secondary',
                        'contoh_tampilan' => 'success',
                        'tip_box' => 'warning',
                        default => 'gray',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'fitur_utama' => 'heroicon-o-star',
                        'panduan_langkah' => 'heroicon-o-document-text',
                        'contoh_tampilan' => 'heroicon-o-photo',
                        'tip_box' => 'heroicon-o-light-bulb',
                        default => 'heroicon-o-question-mark-circle',
                    }),

                Tables\Columns\TextColumn::make('content_order')
                    ->label('Urutan')
                    ->sortable()
                    ->alignCenter()
                    ->weight('bold')
                    ->color('secondary'),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->limit(50)
                    ->searchable()
                    ->weight('medium'),

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

                Tables\Columns\TextColumn::make('image_path')
                    ->label('Path Gambar')
                    ->limit(30)
                    ->searchable()
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return $state ?: null;
                    })
                    ->color('gray')
                    ->placeholder('Tidak ada gambar')
                    ->size('sm')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color('gray')
                    ->size('sm'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('submodule')
                    ->label('Nama Fitur')
                    ->searchable()
                    ->preload()
                    ->relationship('submodule', 'name')
                    ->placeholder('Semua Fitur'),

                Tables\Filters\SelectFilter::make('content_type')
                    ->label('Tipe Konten')
                    ->options([
                        'fitur_utama' => 'Fitur Utama',
                        'panduan_langkah' => 'Panduan Langkah',
                        'contoh_tampilan' => 'Contoh Tampilan',
                        'tip_box' => 'Tip Box',
                    ])
                    ->searchable()
                    ->preload()
                    ->placeholder('Semua Tipe'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWebsiteFeatureContents::route('/'),
            'create' => Pages\CreateWebsiteFeatureContent::route('/create'),
            'view' => Pages\ViewWebsiteFeatureContent::route('/{record}'),
            'edit' => Pages\EditWebsiteFeatureContent::route('/{record}/edit'),
        ];
    }
}