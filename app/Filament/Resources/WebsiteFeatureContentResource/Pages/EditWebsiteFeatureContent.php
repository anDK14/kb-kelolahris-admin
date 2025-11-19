<?php

namespace App\Filament\Resources\WebsiteFeatureContentResource\Pages;

use App\Filament\Resources\WebsiteFeatureContentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWebsiteFeatureContent extends EditRecord
{
    protected static string $resource = WebsiteFeatureContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
