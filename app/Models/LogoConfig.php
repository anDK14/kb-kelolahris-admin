<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogoConfig extends Model
{
    use HasFactory;

    protected $fillable = ['config_key', 'logo_url', 'logo_alt'];
    
    protected $table = 'logo_configs';

    public static function getLogo($key)
    {
        try {
            $config = static::where('config_key', $key)->first();
            
            if ($config) {
                return [
                    'url' => $config->logo_url,
                    'alt' => $config->logo_alt,
                    'config_key' => $config->config_key
                ];
            }
        } catch (\Exception $e) {
            // Fallback jika tabel belum ada
        }
        
        // Fallback default
        return self::getDefaultLogo($key);
    }

    public static function getDefaultLogo($key)
    {
        $defaults = [
            'navbar_logo' => [
                'url' => 'https://esdmvascomm.vasdev.co.id/logo/logo-type-white.png',
                'alt' => 'Vascomm Logo',
                'config_key' => 'navbar_logo'
            ],
            'footer_logo' => [
                'url' => 'https://www.kelolahr.id/wp-content/uploads/2023/09/new-logo-khr-white.png',
                'alt' => 'KelolaHR Logo',
                'config_key' => 'footer_logo'
            ]
        ];

        return $defaults[$key] ?? $defaults['navbar_logo'];
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
}