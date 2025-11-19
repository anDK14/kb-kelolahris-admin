<?php

namespace App\Filament\Resources\ModuleResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubmodulesRelationManager extends RelationManager
{
    protected static string $relationship = 'submodules';

    protected static ?string $title = 'Fitur Website';

    protected static ?string $label = 'Fitur Website';

    protected static ?string $pluralLabel = 'Fitur Website';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Fitur Website')
                    ->required()
                    ->maxLength(150)
                    ->placeholder('Masukkan nama Fitur Website'),

                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(3)
                    ->placeholder('Masukkan deskripsi Fitur Website')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Fitur Website')
                    ->searchable()
                    ->weight('semibold')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->searchable()
                    ->color('gray')
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return $state ?: null;
                    }),

                Tables\Columns\TextColumn::make('view_count')
                    ->label('Jumlah Dilihat')
                    ->badge()
                    ->color('success'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('viewAll')
                    ->label('Lihat Semua')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->color('primary')
                    ->url(fn($livewire) => \App\Filament\Resources\SubmoduleResource::getUrl('index', [
                        'tableFilters[module][value]' => $livewire->ownerRecord->id
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
            ->emptyStateHeading('Belum ada Fitur Website')
            ->emptyStateDescription('Klik tombol "Tambah Fitur Website" untuk menambahkan Fitur Website pertama.');
    }
}
