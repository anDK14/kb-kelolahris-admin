<?php

namespace App\Filament\Resources\MobileFeatureResource\Pages;

use App\Filament\Resources\MobileFeatureResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMobileFeatures extends ListRecords
{
    protected static string $resource = MobileFeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
