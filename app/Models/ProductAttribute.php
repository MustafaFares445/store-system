<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductAttribute extends Pivot
{
    protected $fillable = [
      'product_id',
      'attribute_id',
      'value'
    ];
}
