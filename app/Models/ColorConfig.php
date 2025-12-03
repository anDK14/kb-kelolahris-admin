<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColorConfig extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'config_key', 'color_start', 'color_end', 'is_active'];
    protected $table = 'color_configs';
    
    // FALLBACK DEFAULTS sebagai konstanta class - PERBARUI INI
    private const FALLBACK_DEFAULTS = [
        'navbar' => [
            'bg_utama' => [
                'start' => '#045257',
                'end' => '#06756e',
            ],
            'text_active' => [
                'start' => '#FC9E49',
                'end' => '#FC9E49',
            ],
            'text_hover' => [
                'start' => '#FC9E49',
                'end' => '#FC9E49',
            ],
            'text_normal' => [
                'start' => '#FFFFFF',
                'end' => '#FFFFFF',
            ]
        ],
        'footer' => [
            'bg_utama' => [
                'start' => '#045257',
                'end' => '#06756e',
            ],
            'text_active' => [
                'start' => '#FC9E49',
                'end' => '#FC9E49',
            ],
            'text_hover' => [
                'start' => '#FC9E49',
                'end' => '#FC9E49',
            ],
            'text_normal' => [
                'start' => '#FFFFFF',
                'end' => '#FFFFFF',
            ]
        ],
        'default' => [
            'start' => '#045257',
            'end' => '#045257',
        ]
    ];

    public static function getGradient($type, $configKey)
    {
        try {
            // Hanya ambil yang aktif
            $config = static::where('type', $type)
                ->where('config_key', $configKey)
                ->where('is_active', true)
                ->first();
            
            if ($config) {
                return [
                    'type' => $config->type,
                    'config_key' => $config->config_key,
                    'start' => $config->color_start,
                    'end' => $config->color_end,
                    'is_active' => $config->is_active
                ];
            }
        } catch (\Exception $e) {
            // Fallback jika tabel belum ada
        }
        
        // Ambil fallback dari konstanta
        $fallback = self::FALLBACK_DEFAULTS[$type][$configKey] ?? self::FALLBACK_DEFAULTS['default'];
        
        return [
            'type' => $type,
            'config_key' => $configKey,
            'start' => $fallback['start'],
            'end' => $fallback['end'],
            'is_active' => true
        ];
    }

    // TAMBAHKAN METHOD INI untuk konsistensi dengan page publik
    public static function getColors($type, $configKey)
    {
        return self::getGradient($type, $configKey);
    }

    // Scope untuk hanya mengambil yang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    // Scope untuk berdasarkan type
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}