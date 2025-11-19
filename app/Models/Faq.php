<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Faq extends Model
{
    use HasFactory;

    protected $table = 'faq';

    protected $fillable = [
        'submodule_id',
        'mobile_feature_id', 
        'question',
        'answer'
    ];

    public function submodule(): BelongsTo
    {
        return $this->belongsTo(Submodule::class);
    }

    public function mobileFeature(): BelongsTo
    {
        return $this->belongsTo(MobileFeature::class);
    }
}