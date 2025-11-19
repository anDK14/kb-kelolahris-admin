<?php

namespace App\Filament\Resources\WebsiteFeatureContentResource\Pages;

use App\Filament\Resources\WebsiteFeatureContentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewWebsiteFeatureContent extends ViewRecord
{
    protected static string $resource = WebsiteFeatureContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Edit')
                ->color('primary'),
        ];
    }
}