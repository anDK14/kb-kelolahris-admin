<?php

namespace App\Filament\Resources\MobileModuleResource\Pages;

use App\Filament\Resources\MobileModuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMobileModule extends ViewRecord
{
    protected static string $resource = MobileModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Edit')
                ->color('primary'),
        ];
    }
}