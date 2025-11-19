<?php

namespace App\Filament\Resources\SubmoduleResource\Pages;

use App\Filament\Resources\SubmoduleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubmodule extends EditRecord
{
    protected static string $resource = SubmoduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
