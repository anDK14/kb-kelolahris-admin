<?php

namespace App\Filament\Resources\LogoConfigResource\Pages;

use App\Filament\Resources\LogoConfigResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLogoConfigs extends ListRecords
{
    protected static string $resource = LogoConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
