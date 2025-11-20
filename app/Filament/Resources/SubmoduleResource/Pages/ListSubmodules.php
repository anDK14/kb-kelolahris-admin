<?php

namespace App\Filament\Resources\SubmoduleResource\Pages;

use App\Filament\Resources\SubmoduleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubmodules extends ListRecords
{
    protected static string $resource = SubmoduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Fitur'),
        ];
    }
}
