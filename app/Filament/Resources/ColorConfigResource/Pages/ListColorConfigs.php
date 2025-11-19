<?php

namespace App\Filament\Resources\ColorConfigResource\Pages;

use App\Filament\Resources\ColorConfigResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListColorConfigs extends ListRecords
{
    protected static string $resource = ColorConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
