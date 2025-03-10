<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductVariant extends Model implements HasMedia
{
    use HasFactory , HasSlug , InteractsWithMedia;

    protected $fillable = [
        'name',
        'slug',
        'product_id',
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
                $attributeNameWithValue = '';
                foreach($model->attributes as $attribute){
                    $attributeNameWithValue .= $attribute->name . '-' . $attribute->value;
                }
              

                // Combine the product name, attribute name, and value to create the slug
                return "{$productName} {$attributeNameWithValue}";
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

    public function vendors(): BelongsToMany
    {
        return $this->belongsToMany(Vendor::class);
    }
}
