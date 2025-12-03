<?php

namespace App\Filament\Resources\ColorConfigResource\Pages;

use App\Filament\Resources\ColorConfigResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateColorConfig extends CreateRecord
{
    protected static string $resource = ColorConfigResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return $this->getResource()::getModelLabel() . ' berhasil dibuat!';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
