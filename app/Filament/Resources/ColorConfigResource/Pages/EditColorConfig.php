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
