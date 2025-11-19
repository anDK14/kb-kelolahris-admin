<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColorConfig extends Model
{
    use HasFactory;

    protected $fillable = ['config_key', 'color_start', 'color_end'];
    
    protected $table = 'color_configs';

    public static function getGradient($key)
    {
        $config = static::where('config_key', $key)->first();
        
        if ($config) {
            return [
                'start' => $config->color_start,
                'end' => $config->color_end,
                'class' => "from-[{$config->color_start}] to-[{$config->color_end}]"
            ];
        }
        
        // Fallback default
        return [
            'start' => '#045257',
            'end' => '#06756e',
            'class' => 'from-[#045257] to-[#06756e]'
        ];
    }
}