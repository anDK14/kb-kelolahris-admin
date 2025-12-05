<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LogoConfigResource\Pages;
use App\Models\LogoConfig;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LogoConfigResource extends Resource
{
    protected static ?string $model = LogoConfig::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Theme';

    protected static ?string $navigationLabel = 'Konfigurasi Logo';

    protected static ?string $modelLabel = 'Konfigurasi Logo';

    protected static ?string $pluralModelLabel = 'Data Konfigurasi Logo';

    protected static ?int $navigationSort = 98;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Pengaturan Konfigurasi')
                    ->description('Pilih jenis konfigurasi logo yang ingin diatur')
                    ->schema([
                        Forms\Components\Select::make('config_key')
                            ->label('Jenis Konfigurasi')
                            ->options([
                                'navbar_logo' => 'Logo Navbar',
                                'footer_logo' => 'Logo Footer',
                                // 'favicon' => 'Favicon',
                                // 'mobile_logo' => 'Logo Mobile App',
                                // 'admin_logo' => 'Logo Admin Panel',
                                // 'email_logo' => 'Logo Email Signature',
                                // 'print_logo' => 'Logo Cetak/Dokumen',
                                // 'dark_logo' => 'Logo Mode Gelap',
                                // 'light_logo' => 'Logo Mode Terang',
                            ])
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->disabled(fn($record) => $record !== null)
                            ->helperText('Pilih bagian website yang ingin diatur logonya'),

                        Forms\Components\Section::make('Status')
                            ->description('Aktifkan atau nonaktifkan konfigurasi ini')
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Aktif')
                                    ->default(true)
                                    ->required()
                                    ->helperText('Jika dinonaktifkan, warna default akan digunakan')
                                    ->onColor('primary')
                                    ->offColor('danger'),
                            ]),
                    ]),

                Forms\Components\Section::make('Pengaturan Logo')
                    ->description('Atur URL logo dan teks alternatif')
                    ->schema([
                        Forms\Components\TextInput::make('logo_url')
                            ->label('URL Logo')
                            ->required()
                            ->url()
                            ->maxLength(500)
                            ->placeholder('https://example.com/logo.png')
                            ->helperText('Masukkan URL lengkap menuju gambar logo'),

                        Forms\Components\TextInput::make('logo_alt')
                            ->label('Teks Alternatif (Alt Text)')
                            ->required()
                            ->maxLength(200)
                            ->placeholder('Nama Perusahaan Logo')
                            ->helperText('Teks yang akan ditampilkan jika gambar tidak dapat dimuat'),
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
                        'navbar_logo' => 'Logo Navbar',
                        'footer_logo' => 'Logo Footer',
                        default => $state
                    })
                    ->sortable()
                    ->searchable(),

                Tables\Columns\ImageColumn::make('logo_url')
                    ->label('Preview Logo')
                    ->height(40)
                    ->width(100)
                    ->extraImgAttributes(['class' => 'object-contain'])
                    ->defaultImageUrl(fn($record) => 'data:image/svg+xml;base64,' . base64_encode('
                        <svg width="100" height="40" xmlns="http://www.w3.org/2000/svg">
                            <rect width="100" height="40" fill="#f3f4f6"/>
                            <text x="50" y="22" text-anchor="middle" font-family="Arial" font-size="10" fill="#9ca3af">No Logo</text>
                        </svg>
                    ')),

                Tables\Columns\TextColumn::make('logo_alt')
                    ->label('Alt Text')
                    ->limit(30)
                    ->tooltip(function ($state) {
                        if (strlen($state) > 30) {
                            return $state;
                        }
                        return null;
                    })
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

                Tables\Columns\TextColumn::make('logo_url')
                    ->label('URL Logo')
                    ->limit(40)
                    ->copyable()
                    ->copyMessage('URL logo disalin!')
                    ->tooltip(function ($state) {
                        if (strlen($state) > 40) {
                            return $state;
                        }
                        return null;
                    }),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime('d F Y H:i')
                    ->sortable()
                    ->description(fn($record) => $record->updated_at->diffForHumans()),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('config_key')
                    ->label('Jenis Konfigurasi')
                    ->options([
                        'navbar_logo' => 'Logo Navbar',
                        'footer_logo' => 'Logo Footer',
                    ])
                    ->placeholder('Semua Jenis'),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif')
                    ->placeholder('Semua Status')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif')
                    ->queries(
                        true: fn($query) => $query->where('is_active', true),
                        false: fn($query) => $query->where('is_active', false),
                        blank: fn($query) => $query,
                    ),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('preview')
                        ->label('Preview')
                        ->icon('heroicon-o-eye')
                        ->color('success')
                        ->url(fn($record) => $record->logo_url, shouldOpenInNewTab: true)
                        ->tooltip('Lihat logo di tab baru'),

                    Tables\Actions\Action::make('activate')
                        ->label(fn($record) => $record->is_active ? 'Nonaktifkan' : 'Aktifkan')
                        ->icon(fn($record) => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                        ->color(fn($record) => $record->is_active ? 'danger' : 'success')
                        ->action(function ($record) {
                            // Jika akan mengaktifkan, nonaktifkan dulu yang lain dengan config_key sama
                            if (!$record->is_active) {
                                LogoConfig::where('config_key', $record->config_key)
                                    ->where('id', '!=', $record->id)
                                    ->update(['is_active' => false]);
                            }

                            $record->update(['is_active' => !$record->is_active]);
                        })
                        ->tooltip(fn($record) => $record->is_active ? 'Nonaktifkan logo ini' : 'Aktifkan logo ini'),

                    Tables\Actions\EditAction::make()
                        ->label('Edit')
                        ->tooltip('Edit konfigurasi logo'),
                ])
                    ->label('Aksi')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->size('sm')
                    ->color('primary')
                    ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('activateSelected')
                        ->label('Aktifkan yang dipilih')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                LogoConfig::where('config_key', $record->config_key)
                                    ->update(['is_active' => false]);

                                $record->update(['is_active' => true]);
                            }
                        })
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('deactivateSelected')
                        ->label('Nonaktifkan yang dipilih')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update(['is_active' => false]);
                            }
                        })
                        ->deselectRecordsAfterCompletion(),

                    // Tables\Actions\DeleteBulkAction::make()
                    //     ->label('Hapus yang dipilih')
                    //     ->requiresConfirmation()
                    //     ->modalHeading('Hapus Konfigurasi Logo')
                    //     ->modalDescription('Apakah Anda yakin ingin menghapus konfigurasi logo yang dipilih?')
                    //     ->modalSubmitActionLabel('Ya, Hapus'),
                ]),
            ])
            ->emptyStateHeading('Belum ada konfigurasi logo')
            ->defaultSort('updated_at', 'desc')
            ->recordUrl(null)
            ->striped()
            ->deferLoading();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLogoConfigs::route('/'),
            'create' => Pages\CreateLogoConfig::route('/create'),
            'edit' => Pages\EditLogoConfig::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    // Tambahkan badge di navigation untuk logo aktif
    public static function getNavigationBadge(): ?string
    {
        $activeCount = static::getModel()::where('is_active', true)->count();
        return $activeCount > 0 ? $activeCount : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'success';
    }
}
