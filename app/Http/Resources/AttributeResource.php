<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="AttributeResource",
 *     title="Attribute Resource",
 *     description="Single attribute resource",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         description="The unique identifier of the attribute",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="The name of the attribute",
 *         example="Color"
 *     ),
 *     @OA\Property(
 *         property="image",
 *         ref="#/components/schemas/MediaResource",
 *         description="The associated media resource for the attribute"
 *     )
 * )
 */
class AttributeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'      => $this->when($this->id , $this->id),
            'name'    => $this->when($this->name , $this->name),
            'image'   => $this->whenLoaded('media' , MediaResource::make($this->getFirstMedia('categories'))),
            'values'  => $this->whenLoaded('productAttributes' , $this->productAttributes->unique('id')->pluck('value')->toArray()),
            'options' => $this->whenLoaded('attributeOptions' , $this->attributeOptions->unique('id')->pluck('value')->toArray()),
        ];
    }
}