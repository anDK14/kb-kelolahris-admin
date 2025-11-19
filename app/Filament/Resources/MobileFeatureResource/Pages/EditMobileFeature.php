<?php

namespace App\Filament\Resources\MobileFeatureResource\Pages;

use App\Filament\Resources\MobileFeatureResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMobileFeature extends EditRecord
{
    protected static string $resource = MobileFeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
