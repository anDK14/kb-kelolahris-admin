<?php

namespace App\Filament\Resources\MobileFeatureResource\Pages;

use App\Filament\Resources\MobileFeatureResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMobileFeature extends CreateRecord
{
    protected static string $resource = MobileFeatureResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return $this->getResource()::getModelLabel() . ' berhasil dibuat!';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
