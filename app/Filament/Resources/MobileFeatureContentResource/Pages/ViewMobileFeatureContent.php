<?php

namespace App\Filament\Resources\MobileFeatureContentResource\Pages;

use App\Filament\Resources\MobileFeatureContentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMobileFeatureContent extends ViewRecord
{
    protected static string $resource = MobileFeatureContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Edit')
                ->color('primary'),
        ];
    }
}