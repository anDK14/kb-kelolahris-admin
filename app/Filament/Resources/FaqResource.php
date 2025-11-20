<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages;
use App\Filament\Resources\FaqResource\RelationManagers;
use App\Models\Faq;
use App\Models\Submodule;
use App\Models\MobileFeature;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationGroup = 'FAQ';

    protected static ?string $navigationLabel = 'FAQ';

    protected static ?string $modelLabel = 'FAQ';

    protected static ?string $pluralModelLabel = 'Data FAQ';

    protected static ?int $navigationSort = -70;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi FAQ')
                    ->schema([
                        Forms\Components\TextInput::make('id')
                            ->label('ID')
                            ->columnSpanFull()
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('submodule_id')
                                    ->label('Fitur Website')
                                    ->relationship('submodule', 'name')
                                    ->required(fn() => request()->query('feature_type') === 'website')
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Pilih fitur website')
                                    ->disabled(fn() => request()->query('feature_type') === 'mobile'),

                                Forms\Components\Select::make('mobile_feature_id')
                                    ->label('Fitur Mobile')
                                    ->relationship('mobileFeature', 'name')
                                    ->required(fn() => request()->query('feature_type') === 'mobile')
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Pilih fitur mobile')
                                    ->disabled(fn() => request()->query('feature_type') === 'website'),
                            ])
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('question')
                            ->label('Pertanyaan')
                            ->required()
                            ->rows(2)
                            ->columnSpanFull()
                            ->placeholder('Masukkan pertanyaan yang sering diajukan'),

                        Forms\Components\RichEditor::make('answer')
                            ->label('Jawaban')
                            ->required()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'bulletList',
                                'orderedList',
                            ])
                            ->columnSpanFull()
                            ->placeholder('Masukkan jawaban untuk pertanyaan di atas'),
                    ]),
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

                Tables\Columns\TextColumn::make('submodule_id')
                    ->label('ID Fitur Website')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('—')
                    ->color('gray')
                    ->size('sm'),

                Tables\Columns\TextColumn::make('submodule.name')
                    ->label('Fitur Website')
                    ->sortable()
                    ->searchable()
                    ->placeholder('—')
                    ->color('primary')
                    ->weight('semibold')
                    ->size('lg'),

                Tables\Columns\TextColumn::make('mobile_feature_id')
                    ->label('ID Fitur Mobile')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('—')
                    ->color('gray')
                    ->size('lg'),

                Tables\Columns\TextColumn::make('mobileFeature.name')
                    ->label('Fitur Mobile')
                    ->sortable()
                    ->searchable()
                    ->placeholder('—')
                    ->color('secondary')
                    ->weight('semibold')
                    ->size('lg'),

                Tables\Columns\TextColumn::make('question')
                    ->label('Pertanyaan')
                    ->limit(50)
                    ->searchable()
                    ->weight('semibold')
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return $state ?: null;
                    }),

                Tables\Columns\TextColumn::make('answer')
                    ->label('Jawaban')
                    ->limit(50)
                    ->searchable()
                    ->color('gray')
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return $state ? strip_tags($state) : null;
                    })
                    ->html(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color('gray')
                    ->size('sm'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color('gray')
                    ->size('sm'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('feature_type')
                    ->label('Tipe Fitur')
                    ->placeholder('Semua tipe')
                    ->trueLabel('Fitur Website saja')
                    ->falseLabel('Fitur Mobile saja')
                    ->queries(
                        true: fn($query) => $query->whereNotNull('submodule_id')->whereNull('mobile_feature_id'),
                        false: fn($query) => $query->whereNull('submodule_id')->whereNotNull('mobile_feature_id'),
                        blank: fn($query) => $query,
                    ),

                Tables\Filters\SelectFilter::make('submodule')
                    ->label('Fitur Website')
                    ->searchable()
                    ->preload()
                    ->relationship('submodule', 'name')
                    ->placeholder('Semua Fitur Website'),

                Tables\Filters\SelectFilter::make('mobileFeature')
                    ->label('Fitur Mobile')
                    ->searchable()
                    ->preload()
                    ->relationship('mobileFeature', 'name')
                    ->placeholder('Semua Fitur Mobile'),
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
            ->headerActions([
                Tables\Actions\Action::make('exportPdf')
                    ->label('Ekspor PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->form([
                        Forms\Components\Radio::make('feature_type')
                            ->label('Tipe Fitur')
                            ->options([
                                'all' => 'Semua FAQ',
                                'website' => 'Fitur Website Saja',
                                'mobile' => 'Fitur Mobile Saja',
                            ])
                            ->default('all'),
                    ])
                    ->action(function (array $data) {
                        $query = Faq::with(['submodule', 'mobileFeature']);

                        if ($data['feature_type'] === 'website') {
                            $query->whereNotNull('submodule_id')->whereNull('mobile_feature_id');
                        } elseif ($data['feature_type'] === 'mobile') {
                            $query->whereNull('submodule_id')->whereNotNull('mobile_feature_id');
                        }

                        $faqs = $query->get();

                        // Pastikan view 'exports.faqs-pdf' sudah dibuat
                        $pdf = Pdf::loadView('exports.faqs-pdf', [
                            'faqs' => $faqs,
                            'filterType' => $data['feature_type'],
                        ])->setPaper('a4', 'portrait');

                        return Response::streamDownload(
                            fn () => print($pdf->output()),
                            'faq-' . now()->format('Y-m-d_H-i-s') . '.pdf'
                        );
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus yang Dipilih')
                        ->color('danger')
                        ->icon('heroicon-o-trash'),
                ]),
            ])
            ->recordUrl(null)
            ->striped()
            ->deferLoading()
            ->emptyStateHeading('Belum ada FAQ')
            ->emptyStateDescription('Klik tombol "Tambah FAQ" untuk menambahkan pertanyaan dan jawaban.');
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
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'view' => Pages\ViewFaq::route('/{record}'),
            'edit' => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}