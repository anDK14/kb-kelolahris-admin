<?php

namespace App\Filament\Resources\MobileModuleResource\Pages;

use App\Filament\Resources\MobileModuleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMobileModule extends EditRecord
{
    protected static string $resource = MobileModuleResource::class;

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
