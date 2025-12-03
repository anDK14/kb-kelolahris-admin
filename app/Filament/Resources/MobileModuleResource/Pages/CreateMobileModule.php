<?php

namespace App\Filament\Resources\MobileModuleResource\Pages;

use App\Filament\Resources\MobileModuleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMobileModule extends CreateRecord
{
    protected static string $resource = MobileModuleResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return $this->getResource()::getModelLabel() . ' berhasil dibuat!';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
