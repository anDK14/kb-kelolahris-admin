<?php

namespace App\Filament\Resources\WebsiteFeatureContentResource\Pages;

use App\Filament\Resources\WebsiteFeatureContentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;

class ListWebsiteFeatureContents extends ListRecords
{
    protected static string $resource = WebsiteFeatureContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Gunakan ActionGroup agar dropdown berfungsi di Filament v4
            ActionGroup::make([
                Action::make('fitur_utama')
                    ->label('Fitur Utama')
                    ->icon('heroicon-o-star')
                    ->url(fn () => static::getResource()::getUrl('create', [
                        'content_type' => 'fitur_utama',
                    ])),

                Action::make('panduan_langkah')
                    ->label('Panduan Langkah')
                    ->icon('heroicon-o-book-open')
                    ->url(fn () => static::getResource()::getUrl('create', [
                        'content_type' => 'panduan_langkah',
                    ])),

                Action::make('contoh_tampilan')
                    ->label('Contoh Tampilan')
                    ->icon('heroicon-o-photo')
                    ->url(fn () => static::getResource()::getUrl('create', [
                        'content_type' => 'contoh_tampilan',
                    ])),

                Action::make('tip_box')
                    ->label('Tip Box')
                    ->icon('heroicon-o-light-bulb')
                    ->url(fn () => static::getResource()::getUrl('create', [
                        'content_type' => 'tip_box',
                    ])),
            ])
                ->label('Tambah Konten')
                ->icon('')
                ->button()
                ->color('primary'),
        ];
    }
}