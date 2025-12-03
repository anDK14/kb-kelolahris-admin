<?php

namespace App\Filament\Resources\SubmoduleResource\Pages;

use App\Filament\Resources\SubmoduleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSubmodule extends CreateRecord
{
    protected static string $resource = SubmoduleResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return $this->getResource()::getModelLabel() . ' berhasil dibuat!';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
