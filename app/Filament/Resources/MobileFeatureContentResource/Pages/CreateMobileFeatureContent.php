<?php

namespace App\Filament\Resources\MobileFeatureContentResource\Pages;

use App\Filament\Resources\MobileFeatureContentResource;
use App\Models\MobileFeatureContent;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateMobileFeatureContent extends CreateRecord
{
    protected static string $resource = MobileFeatureContentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['content_type'] = $data['content_type'] ?? request()->query('content_type');

        if (empty($data['content_type'])) {
            $data['content_type'] = 'fitur_utama';

            Notification::make()
                ->title('Tipe konten tidak ditentukan, menggunakan default: Fitur Utama')
                ->warning()
                ->send();
        }

        if (!empty($data['mobile_feature_id']) && !empty($data['content_type'])) {
            $lastOrder = MobileFeatureContent::where('mobile_feature_id', $data['mobile_feature_id'])
                ->where('content_type', $data['content_type'])
                ->max('content_order');

            $data['content_order'] = $lastOrder ? $lastOrder + 1 : 1;
        } else {
            $data['content_order'] = 1;
        }

        return $data;
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Konten mobile berhasil dibuat')
            ->success()
            ->body('Konten mobile telah berhasil ditambahkan ke sistem.')
            ->send();
    }

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

    public function getTitle(): string
    {
        $contentType = request()->query('content_type', 'fitur_utama');
        
        $typeLabels = [
            'fitur_utama' => 'Fitur Utama',
            'panduan_langkah' => 'Panduan Langkah', 
            'contoh_tampilan' => 'Contoh Tampilan',
            'tip_box' => 'Tip Box',
        ];

        $typeLabel = $typeLabels[$contentType] ?? 'Konten Mobile';

        return "Tambah {$typeLabel}";
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return $this->getResource()::getModelLabel() . ' berhasil dibuat!';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}