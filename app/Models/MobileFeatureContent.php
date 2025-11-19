<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MobileFeatureContent extends Model
{
    use HasFactory;

    protected $table = 'mobilefeature_contents';

    protected $fillable = [
        'mobile_feature_id',
        'content_type',
        'title', 
        'description',
        'image_path',
        'content_order'
    ];

    public function mobileFeature(): BelongsTo
    {
        return $this->belongsTo(MobileFeature::class);
    }
}