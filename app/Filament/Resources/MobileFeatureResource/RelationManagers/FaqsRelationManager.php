<?php

namespace App\Filament\Resources\MobileFeatureResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FaqsRelationManager extends RelationManager
{
    protected static string $relationship = 'faqs';

    protected static ?string $title = 'FAQ';

    protected static ?string $label = 'FAQ';

    protected static ?string $pluralLabel = 'FAQ';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('question')
                    ->label('Pertanyaan')
                    ->rows(2)
                    ->required()
                    ->placeholder('Masukkan pertanyaan yang sering diajukan')
                    ->columnSpanFull(),

                Forms\Components\RichEditor::make('answer')
                    ->label('Jawaban')
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'underline',
                        'bulletList',
                        'orderedList',
                    ])
                    ->placeholder('Tuliskan jawaban untuk pertanyaan di atas')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('question')
            ->columns([
                Tables\Columns\TextColumn::make('question')
                    ->label('Pertanyaan')
                    ->limit(50)
                    ->searchable()
                    ->weight('medium')
                    ->color('primary')
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return $state ?: null;
                    }),

                Tables\Columns\TextColumn::make('answer')
                    ->label('Jawaban')
                    ->limit(30)
                    ->html()
                    ->searchable()
                    ->color('gray')
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        // Remove HTML tags for tooltip
                        return $state ? strip_tags($state) : null;
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('viewAll')
                    ->label('Lihat Semua')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->color('primary')
                    ->url(fn($livewire) => \App\Filament\Resources\FaqResource::getUrl('index', [
                        'tableFilters[mobileFeature][value]' => $livewire->ownerRecord->id
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
            ->emptyStateHeading('Belum ada FAQ')
            ->emptyStateDescription('Klik tombol "Tambah FAQ" untuk menambahkan pertanyaan dan jawaban.');
    }
}