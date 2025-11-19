<?php

namespace App\Filament\Resources\SubmoduleResource\Pages;

use App\Filament\Resources\SubmoduleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSubmodule extends ViewRecord
{
    protected static string $resource = SubmoduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Edit')
                ->color('primary'),
        ];
    }
}