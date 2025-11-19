<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function submodules(): HasMany
    {
        return $this->hasMany(Submodule::class);
    }
    
    public function getTotalViewsAttribute()
    {
        return $this->submodules->sum('view_count');
    }
}