<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ColorConfigResource\Pages;
use App\Models\ColorConfig;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ColorConfigResource extends Resource
{
    protected static ?string $model = ColorConfig::class;

    protected static ?string $navigationIcon = 'heroicon-o-swatch';

    protected static ?string $navigationGroup = 'Sistem';

    protected static ?string $navigationLabel = 'Konfigurasi Warna';

    protected static ?string $modelLabel = 'Konfigurasi Warna';

    protected static ?string $pluralModelLabel = 'Konfigurasi Warna';

    protected static ?int $navigationSort = 99;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Pengaturan Konfigurasi')
                    ->description('Pilih jenis konfigurasi warna yang ingin diatur')
                    ->schema([
                        Forms\Components\Select::make('config_key')
                            ->label('Jenis Konfigurasi')
                            ->options([
                                'navbar_gradient' => 'Gradien Navbar',
                                'footer_gradient' => 'Gradien Footer',
                            ])
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->disabled(fn($record) => $record !== null)
                            ->helperText('Pilih bagian website yang ingin diatur warnanya'),
                    ]),

                Forms\Components\Section::make('Pengaturan Warna Gradien')
                    ->description('Atur warna awal dan akhir untuk efek gradien')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\ColorPicker::make('color_start')
                                    ->required()
                                    ->label('Warna Awal')
                                    ->helperText('Warna awal untuk efek gradien')
                                    ->live(),

                                Forms\Components\ColorPicker::make('color_end')
                                    ->required()
                                    ->label('Warna Akhir')
                                    ->helperText('Warna akhir untuk efek gradien')
                                    ->live(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('config_key')
                    ->label('Jenis Konfigurasi')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'navbar_gradient' => 'Gradien Navbar',
                        'footer_gradient' => 'Gradien Footer',
                        default => $state
                    })
                    ->sortable()
                    ->searchable(),

                Tables\Columns\ColorColumn::make('color_start')
                    ->label('Warna Awal')
                    ->copyable()
                    ->copyMessage('Warna awal disalin!'),

                Tables\Columns\ColorColumn::make('color_end')
                    ->label('Warna Akhir')
                    ->copyable()
                    ->copyMessage('Warna akhir disalin!'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime('d F Y H:i')
                    ->sortable()
                    ->description(fn($record) => $record->updated_at->diffForHumans()),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->label('Edit')
                        ->tooltip('Edit konfigurasi warna'),
                ])
                    ->label('Aksi')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->size('sm')
                    ->color('primary')
                    ->button(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make()
                //         ->label('Hapus yang dipilih'),
                // ]),
            ])
            ->emptyStateHeading('Belum ada konfigurasi warna')
            ->emptyStateDescription('Buat konfigurasi warna pertama Anda dengan mengklik tombol di bawah.')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Buat Konfigurasi Warna')
                    ->icon('heroicon-o-plus'),
            ])
            ->recordUrl(null)
            ->striped()
            ->deferLoading();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListColorConfigs::route('/'),
            'create' => Pages\CreateColorConfig::route('/create'),
            'edit' => Pages\EditColorConfig::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}
