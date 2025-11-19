<?php

namespace App\Filament\Resources\MobileFeatureContentResource\Pages;

use App\Filament\Resources\MobileFeatureContentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMobileFeatureContent extends EditRecord
{
    protected static string $resource = MobileFeatureContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
