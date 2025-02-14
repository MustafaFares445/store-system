<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class ProductVariant extends Model implements HasMedia
{
    use HasFactory , HasSlug , InteractsWithMedia;

    protected $fillable = [
        'product_id',
        'value',
        'additional_price',
        'quantity',
        'view'
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function ($model) {
                // Assuming the Product and Attribute models have a 'name' field
                $productName = $model->product->name;
                $attributeName = $model->attribute->name;
                $value = $model->value;

                // Combine the product name, attribute name, and value to create the slug
                return "{$productName} {$attributeName} {$value}";
            })
            ->saveSlugsTo('slug');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function attributes()
    {
        return $this->morphToMany(Attribute::class, 'item' , 'attribute_item')
            ->using(AttributeItem::class)
            ->withPivot('value');
    }
}
