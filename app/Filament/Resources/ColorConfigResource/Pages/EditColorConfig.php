<?php

namespace App\Filament\Resources\ColorConfigResource\Pages;

use App\Filament\Resources\ColorConfigResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditColorConfig extends EditRecord
{
    protected static string $resource = ColorConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
