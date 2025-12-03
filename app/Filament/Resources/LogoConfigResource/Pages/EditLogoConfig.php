<?php

namespace App\Filament\Resources\LogoConfigResource\Pages;

use App\Filament\Resources\LogoConfigResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLogoConfig extends EditRecord
{
    protected static string $resource = LogoConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->successNotificationTitle(
                    $this->getResource()::getModelLabel() . ' berhasil dihapus!'
                ),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return $this->getResource()::getModelLabel() . ' berhasil diperbarui!';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
