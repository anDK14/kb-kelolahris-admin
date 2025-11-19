<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebsiteFeatureContent extends Model
{
    use HasFactory;

    protected $table = 'websitefeature_contents';

    protected $fillable = [
        'submodule_id',
        'content_type', 
        'title',
        'description',
        'image_path',
        'content_order'
    ];

    public function submodule(): BelongsTo
    {
        return $this->belongsTo(Submodule::class);
    }
}