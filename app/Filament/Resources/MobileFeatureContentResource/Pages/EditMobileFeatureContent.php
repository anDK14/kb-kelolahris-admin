<?php

namespace App\Filament\Resources\MobileFeatureContentResource\Pages;

use App\Filament\Resources\MobileFeatureContentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMobileFeatureContent extends EditRecord
{
    protected static string $resource = MobileFeatureContentResource::class;

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
