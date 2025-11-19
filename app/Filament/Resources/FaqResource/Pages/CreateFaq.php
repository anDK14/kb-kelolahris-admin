<?php

namespace App\Filament\Resources\FaqResource\Pages;

use App\Filament\Resources\FaqResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFaq extends CreateRecord
{
    protected static string $resource = FaqResource::class;

    /**
     * Set default form values berdasarkan query parameter feature_type
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $featureType = request()->query('feature_type', 'website');

        // Set default values berdasarkan feature_type
        if ($featureType === 'website') {
            $data['mobile_feature_id'] = null;
        } elseif ($featureType === 'mobile') {
            $data['submodule_id'] = null;
        }

        return $data;
    }

    /**
     * Mengembalikan pengguna ke halaman yang benar setelah membuat data.
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Tindakan header halaman.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('kembali')
                ->label('Kembali ke Daftar')
                ->url($this->getResource()::getUrl('index'))
                ->color('gray')
                ->icon('heroicon-o-arrow-left'),
        ];
    }
}