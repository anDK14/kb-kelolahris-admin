<?php

namespace App\Filament\Resources\FaqResource\Pages;

use App\Filament\Resources\FaqResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFaqs extends ListRecords
{
    protected static string $resource = FaqResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ActionGroup::make([
                Actions\Action::make('website_feature')
                    ->label('Website Feature')
                    ->icon('heroicon-o-globe-alt')
                    ->url(fn () => static::getResource()::getUrl('create', [
                        'feature_type' => 'website',
                    ])),

                Actions\Action::make('mobile_feature')
                    ->label('Mobile Feature')
                    ->icon('heroicon-o-device-phone-mobile')
                    ->url(fn () => static::getResource()::getUrl('create', [
                        'feature_type' => 'mobile',
                    ])),
            ])
            ->label('Tambah FAQ')
            ->icon('')
            ->button()
            ->color('primary'),
        ];
    }
}