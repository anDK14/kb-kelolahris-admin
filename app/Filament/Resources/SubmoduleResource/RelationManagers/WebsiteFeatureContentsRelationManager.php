<?php

namespace App\Filament\Resources\SubmoduleResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WebsiteFeatureContentsRelationManager extends RelationManager
{
    protected static string $relationship = 'websiteFeatureContents';

    protected static ?string $title = 'Konten Fitur Website';

    protected static ?string $label = 'Konten';

    protected static ?string $pluralLabel = 'Konten';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('content_type')
                    ->label('Tipe Konten')
                    ->required()
                    ->options([
                        'fitur_utama' => 'Fitur Utama',
                        'panduan_langkah' => 'Panduan Langkah',
                        'contoh_tampilan' => 'Contoh Tampilan',
                        'tip_box' => 'Tip Box',
                    ])
                    ->searchable()
                    ->reactive()
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan judul konten')
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->required()
                    ->rows(6)
                    ->placeholder('Masukkan deskripsi konten')
                    ->columnSpanFull()
                    ->visible(fn(callable $get) => $get('content_type') !== 'panduan_langkah'),

                // Deskripsi khusus untuk panduan langkah
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->required()
                    ->rows(12)
                    ->placeholder("Masukkan deskripsi konten.\nGunakan format:\nMAIN_STEP: Langkah utama\n    SUB_STEP: Langkah detail")
                    ->columnSpanFull()
                    ->visible(fn(callable $get) => $get('content_type') === 'panduan_langkah'),

                Forms\Components\Textarea::make('image_path')
                    ->label('Path Gambar')
                    ->required()
                    ->rows(2)
                    ->placeholder('Masukkan path/lokasi gambar')
                    ->columnSpanFull()
                    ->visible(fn(callable $get) => $get('content_type') === 'contoh_tampilan'),

                Forms\Components\TextInput::make('content_order')
                    ->label('Urutan')
                    ->numeric()
                    ->required()
                    ->default(1)
                    ->minValue(1)
                    ->placeholder('Masukkan nomor urut'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color('gray')
                    ->size('sm'),

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

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->limit(50)
                    ->searchable()
                    ->weight('medium')
                    ->color('primary')
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return $state ?: null;
                    }),

                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(30)
                    ->searchable()
                    ->color('gray')
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return $state ?: null;
                    })
                    ->size('sm'),

                Tables\Columns\TextColumn::make('image_path')
                    ->label('Gambar')
                    ->limit(20)
                    ->searchable()
                    ->color('gray')
                    ->placeholder('Tidak ada gambar')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return $state ?: null;
                    }),

                Tables\Columns\TextColumn::make('content_order')
                    ->label('Urutan')
                    ->sortable()
                    ->alignCenter()
                    ->weight('bold')
                    ->color('secondary'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->color('gray')
                    ->size('sm')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime()
                    ->sortable()
                    ->color('gray')
                    ->size('sm')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('content_type')
                    ->label('Filter berdasarkan Tipe Konten')
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
            ->headerActions([
                Tables\Actions\Action::make('viewAll')
                    ->label('Lihat Semua')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->color('primary')
                    ->url(fn($livewire) => \App\Filament\Resources\WebsiteFeatureContentResource::getUrl('index', [
                        'tableFilters[submodule][value]' => $livewire->ownerRecord->id
                    ])),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label('Lihat'),
                    Tables\Actions\EditAction::make()
                        ->label('Edit'),
                    Tables\Actions\DeleteAction::make()
                        ->label('Hapus'),
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
                        ->label('Hapus yang Dipilih'),
                ]),
            ])
            ->emptyStateHeading('Belum ada konten')
            ->emptyStateDescription('Klik tombol "Lihat Semua" untuk mengelola konten.');
    }
}
