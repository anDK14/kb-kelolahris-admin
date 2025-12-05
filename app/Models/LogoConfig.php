<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogoConfig extends Model
{
    use HasFactory;

    protected $fillable = ['config_key', 'logo_url', 'logo_alt', 'is_active'];
    
    protected $table = 'logo_configs';

    // Cast is_active sebagai boolean
    protected $casts = [
        'is_active' => 'boolean',
    ];

    // FALLBACK DEFAULTS sebagai konstanta class - SESUAIKAN SEPERTI CONTOH
    private const FALLBACK_DEFAULTS = [
        'navbar_logo' => [
            'url' => 'https://esdmvascomm.vasdev.co.id/logo/logo-type-white.png',
            'alt' => 'Vascomm Logo',
        ],
        'footer_logo' => [
            'url' => 'https://www.kelolahr.id/wp-content/uploads/2023/09/new-logo-khr-white.png',
            'alt' => 'KelolaHR Logo',
        ],
        'default' => [
            'url' => 'https://esdmvascomm.vasdev.co.id/logo/logo-type-white.png',
            'alt' => 'Default Logo',
        ]
    ];

    public static function getLogo($configKey)
    {
        try {
            // Hanya ambil yang aktif
            $config = static::where('config_key', $configKey)
                ->where('is_active', true)
                ->first();
            
            if ($config) {
                return [
                    'config_key' => $config->config_key,
                    'url' => $config->logo_url,
                    'alt' => $config->logo_alt,
                    'is_active' => $config->is_active
                ];
            }
        } catch (\Exception $e) {
            // Fallback jika tabel belum ada
        }
        
        // Ambil fallback dari konstanta
        $fallback = self::FALLBACK_DEFAULTS[$configKey] ?? self::FALLBACK_DEFAULTS['default'];
        
        return [
            'config_key' => $configKey,
            'url' => $fallback['url'],
            'alt' => $fallback['alt'],
            'is_active' => true
        ];
    }

    // TAMBAHKAN METHOD INI untuk konsistensi dengan contoh
    public static function getLogoConfig($configKey)
    {
        return self::getLogo($configKey);
    }

    // Validasi untuk mencegah duplikasi config_key
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $existing = static::where('config_key', $model->config_key)->exists();
            if ($existing) {
                throw new \Exception('Konfigurasi untuk ' . $model->config_key . ' sudah ada.');
            }
        });
    }

    // Scope untuk logo aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk logo berdasarkan key
    public function scopeByKey($query, $configKey)
    {
        return $query->where('config_key', $configKey);
    }
}