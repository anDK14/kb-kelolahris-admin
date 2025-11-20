<?php

namespace App\Filament\Resources\MobileModuleResource\Pages;

use App\Filament\Resources\MobileModuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMobileModules extends ListRecords
{
    protected static string $resource = MobileModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Modul'),
        ];
    }
}
