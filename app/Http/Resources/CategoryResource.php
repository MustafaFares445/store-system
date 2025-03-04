<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->when($this->id , $this->id),
            'name' => $this->when($this->name , $this->name),
            'slug' => $this->when($this->slug , $this->slug),
            'parentSlug' => $this->when($this->parentSlug , $this->parentSlug),
            'image' => $this->whenLoaded('media' , MediaResource::make($this->getFirstMedia('images'))),
            'children' => CategoryResource::collection($this->whenLoaded('children')),
            'attributes' => AttributeResource::collection($this->whenLoaded('attributes'))
        ];
    }
}
