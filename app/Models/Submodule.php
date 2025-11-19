<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Submodule extends Model
{
    use HasFactory;

    protected $fillable = ['module_id', 'name', 'description', 'view_count'];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function websiteFeatureContents(): HasMany
    {
        return $this->hasMany(WebsiteFeatureContent::class);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class);
    }
}