<?php

namespace App\Filament\Resources\LogoConfigResource\Pages;

use App\Filament\Resources\LogoConfigResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLogoConfig extends CreateRecord
{
    protected static string $resource = LogoConfigResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return $this->getResource()::getModelLabel() . ' berhasil dibuat!';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
