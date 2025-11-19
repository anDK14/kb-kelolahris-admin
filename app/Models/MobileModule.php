<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MobileModule extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function mobileFeatures(): HasMany
    {
        return $this->hasMany(MobileFeature::class);
    }

    public function getTotalViewsAttribute()
    {
        return $this->mobileFeatures->sum('view_count');
    }
}