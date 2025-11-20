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

    protected static ?string $navigationGroup = 'Sistem';

    protected static ?string $navigationLabel = 'Konfigurasi Logo';

    protected static ?string $modelLabel = 'Konfigurasi Logo';

    protected static ?string $pluralModelLabel = 'Konfigurasi Logo';

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

                        // Forms\Components\Placeholder::make('logo_preview')
                        //     ->label('Preview Logo')
                        //     ->content(function ($get) {
                        //         $url = $get('logo_url');
                        //         if (!$url) {
                        //             return '<div class="text-gray-500 text-sm">URL logo belum diisi</div>';
                        //         }

                        //         return "
                        //             <div class='space-y-2'>
                        //                 <div class='text-sm font-medium text-gray-700'>Preview:</div>
                        //                 <div class='bg-gray-100 p-4 rounded-lg border border-gray-300'>
                        //                     <img src='{$url}' alt='Preview' class='max-h-20 max-w-full object-contain mx-auto' onerror='this.style.display=\"none\"; document.getElementById(\"preview-status\").textContent=\"Gagal memuat gambar\"'>
                        //                     <div class='text-xs text-gray-500 text-center mt-2' id='preview-status'>Memuat preview...</div>
                        //                 </div>
                        //                 <div class='text-xs text-gray-500 break-all'>URL: {$url}</div>
                        //             </div>
                        //         ";
                        //     })
                        //     ->visible(fn($get) => !empty($get('logo_url'))),
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
                        // 'favicon' => 'Favicon',
                        // 'mobile_logo' => 'Logo Mobile App',
                        // 'admin_logo' => 'Logo Admin Panel',
                        // 'email_logo' => 'Logo Email Signature',
                        // 'print_logo' => 'Logo Cetak/Dokumen',
                        // 'dark_logo' => 'Logo Mode Gelap',
                        // 'light_logo' => 'Logo Mode Terang',
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
                        // 'favicon' => 'Favicon',
                        // 'mobile_logo' => 'Logo Mobile App',
                        // 'admin_logo' => 'Logo Admin Panel',
                        // 'email_logo' => 'Logo Email Signature',
                        // 'print_logo' => 'Logo Cetak/Dokumen',
                        // 'dark_logo' => 'Logo Mode Gelap',
                        // 'light_logo' => 'Logo Mode Terang',
                    ])
                    ->placeholder('Semua Jenis'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('preview')
                        ->label('Preview')
                        ->icon('heroicon-o-eye')
                        ->color('success')
                        ->url(fn($record) => $record->logo_url, shouldOpenInNewTab: true)
                        ->tooltip('Lihat logo di tab baru'),

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
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make()
                //         ->label('Hapus yang dipilih'),
                // ]),
            ])
            ->emptyStateHeading('Belum ada konfigurasi logo')
            ->emptyStateDescription('Buat konfigurasi logo pertama Anda dengan mengklik tombol di bawah.')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Buat Konfigurasi Logo')
                    ->icon('heroicon-o-plus'),
            ])
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
