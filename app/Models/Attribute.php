<?php

namespace App\Models;

use App\Enums\AttributesFilterTypes;
use App\Enums\AttributesTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Attribute extends Model implements HasMedia
{
    use HasFactory , InteractsWithMedia;

    protected $fillable = [
        'name',
        'filter_type',
        'type',
    ];

    protected $casts =[
      'type' => AttributesTypes::class,
      'filter_type' => AttributesFilterTypes::class
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(AttributeOption::class);
    }
}
