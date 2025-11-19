<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MobileFeature extends Model
{
    use HasFactory;

    protected $fillable = ['mobile_module_id', 'name', 'description', 'view_count'];

    public function mobileModule(): BelongsTo
    {
        return $this->belongsTo(MobileModule::class);
    }

    public function mobileFeatureContents(): HasMany
    {
        return $this->hasMany(MobileFeatureContent::class);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class);
    }
}