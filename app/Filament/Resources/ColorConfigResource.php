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

    protected static ?string $navigationGroup = 'Theme';

    protected static ?string $navigationLabel = 'Konfigurasi Warna';

    protected static ?string $modelLabel = 'Konfigurasi Warna';

    protected static ?string $pluralModelLabel = 'Data Konfigurasi Warna';

    protected static ?int $navigationSort = -60;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Pengaturan Konfigurasi')
                    ->description('Pilih jenis dan tipe konfigurasi warna')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->label('Tipe Area')
                            ->options([
                                'navbar' => 'Navbar',
                                'footer' => 'Footer',
                            ])
                            ->required()
                            ->disabled(fn($record) => $record !== null)
                            ->helperText('Pilih bagian website yang ingin diatur warnanya'),
                            
                        Forms\Components\TextInput::make('config_key')
                            ->label('Kunci Konfigurasi')
                            ->default('bg_utama')
                            ->required()
                            ->disabled(fn($record) => $record !== null)
                            ->helperText('Nama unik untuk konfigurasi ini')
                            ->rules(['regex:/^[a-z0-9_]+$/']),
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe Area')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'navbar' => 'Navbar',
                        'footer' => 'Footer',
                        default => ucfirst($state)
                    })
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('config_key')
                    ->label('Kunci Konfigurasi')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'bg_utama' => 'Background Utama',
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
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime('d F Y H:i')
                    ->sortable()
                    ->description(fn($record) => $record->updated_at->diffForHumans()),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipe Area')
                    ->options([
                        'navbar' => 'Navbar',
                        'footer' => 'Footer',
                    ]),
                    
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->label('Edit')
                        ->tooltip('Edit konfigurasi warna'),
                        
                    Tables\Actions\Action::make('toggleActive')
                        ->label(fn(ColorConfig $record): string => $record->is_active ? 'Nonaktifkan' : 'Aktifkan')
                        ->icon(fn(ColorConfig $record): string => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                        ->color(fn(ColorConfig $record): string => $record->is_active ? 'danger' : 'success')
                        ->action(function (ColorConfig $record) {
                            $record->update([
                                'is_active' => !$record->is_active
                            ]);
                        })
                        ->requiresConfirmation()
                        ->modalHeading(fn(ColorConfig $record): string => $record->is_active ? 'Nonaktifkan Konfigurasi?' : 'Aktifkan Konfigurasi?')
                        ->modalDescription(fn(ColorConfig $record): string => $record->is_active 
                            ? 'Konfigurasi warna ini akan dinonaktifkan. Warna default akan digunakan.' 
                            : 'Konfigurasi warna ini akan diaktifkan dan akan digunakan di website.')
                        ->modalSubmitActionLabel(fn(ColorConfig $record): string => $record->is_active ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan'),
                ])
                    ->label('Aksi')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->size('sm')
                    ->color('primary')
                    ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Aktifkan')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update(['is_active' => true]);
                            }
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Aktifkan Konfigurasi')
                        ->modalDescription('Konfigurasi yang dipilih akan diaktifkan.')
                        ->deselectRecordsAfterCompletion(),
                        
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Nonaktifkan')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update(['is_active' => false]);
                            }
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Nonaktifkan Konfigurasi')
                        ->modalDescription('Konfigurasi yang dipilih akan dinonaktifkan.')
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->emptyStateHeading('Belum ada konfigurasi warna')
            ->emptyStateDescription('Klik tombol di bawah untuk membuat konfigurasi warna pertama.')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Buat Konfigurasi Warna')
                    ->icon('heroicon-o-plus'),
            ])
            ->defaultSort('type')
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
        return static::getModel()::where('is_active', true)->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
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