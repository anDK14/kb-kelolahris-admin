<?php

namespace App\Filament\Resources\WebsiteFeatureContentResource\Pages;

use App\Filament\Resources\WebsiteFeatureContentResource;
use App\Models\WebsiteFeatureContent;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateWebsiteFeatureContent extends CreateRecord
{
    protected static string $resource = WebsiteFeatureContentResource::class;

    /**
     * Mutasi data sebelum disimpan ke database.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 1️⃣ Ambil nilai tipe konten dari URL kalau belum ada
        $data['content_type'] = $data['content_type'] ?? request()->query('content_type');

        if (empty($data['content_type'])) {
            $data['content_type'] = 'fitur_utama';

            Notification::make()
                ->title('Tipe konten tidak ditentukan, menggunakan default: Fitur Utama')
                ->warning()
                ->send();
        }

        // 2️⃣ Tentukan urutan otomatis berdasarkan submodule dan tipe konten
        if (!empty($data['submodule_id']) && !empty($data['content_type'])) {
            $lastOrder = WebsiteFeatureContent::where('submodule_id', $data['submodule_id'])
                ->where('content_type', $data['content_type'])
                ->max('content_order');

            $data['content_order'] = $lastOrder ? $lastOrder + 1 : 1;
        } else {
            // Jika belum ada submodule_id (belum dipilih)
            $data['content_order'] = 1;
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
     * Notifikasi sukses setelah membuat data.
     */
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Konten website berhasil dibuat')
            ->success()
            ->body('Konten website telah berhasil ditambahkan ke sistem.')
            ->send();
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

    /**
     * Mengatur judul halaman
     */
    public function getTitle(): string
    {
        $contentType = request()->query('content_type', 'fitur_utama');
        
        $typeLabels = [
            'fitur_utama' => 'Fitur Utama',
            'panduan_langkah' => 'Panduan Langkah', 
            'contoh_tampilan' => 'Contoh Tampilan',
            'tip_box' => 'Tip Box',
        ];

        $typeLabel = $typeLabels[$contentType] ?? 'Konten Website';

        return "Tambah {$typeLabel}";
    }
}