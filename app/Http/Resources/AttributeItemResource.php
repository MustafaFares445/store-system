<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'      =>  $this->when($this->id , $this->id),
            'name'    =>  $this->when($this->name , $this->name),
            'image'   =>  $this->whenLoaded('media' , MediaResource::make($this->getFirstMedia('categories'))),
            'value'   =>  $this->when($this->pivot->value , $this->pivot->value),
        ];
    }
}
