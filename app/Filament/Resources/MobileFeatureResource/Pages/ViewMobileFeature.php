<?php

namespace App\Filament\Resources\MobileFeatureResource\Pages;

use App\Filament\Resources\MobileFeatureResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMobileFeature extends ViewRecord
{
    protected static string $resource = MobileFeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Edit')
                ->color('primary'),
        ];
    }
}