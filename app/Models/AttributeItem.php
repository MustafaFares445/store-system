<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

class AttributeItem extends MorphPivot
{
    public $timestamps = true;

    protected $fillable = [
        'attribute_id',
        'item_id',
        'item_type',
        'value'
    ];

    public function item(): MorphTo
    {
        return $this->morphTo();
    }
}