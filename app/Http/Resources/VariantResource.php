<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="VariantResource",
 *     title="Variant Resource",
 *     description="Product variant resource representation",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         description="Variant ID"
 *     ),
 *     @OA\Property(
 *         property="product",
 *         ref="#/components/schemas/ProductResource",
 *         description="Associated product information"
 *     ),
 *     @OA\Property(
 *         property="attribute",
 *         ref="#/components/schemas/AttributeResource",
 *         description="Associated attribute information"
 *     ),
 *     @OA\Property(
 *         property="value",
 *         type="string",
 *         description="Variant value"
 *     ),
 *     @OA\Property(
 *         property="additionalPrice",
 *         type="number",
 *         format="float",
 *         description="Additional price for this variant"
 *     ),
 *     @OA\Property(
 *         property="quantity",
 *         type="integer",
 *         description="Available quantity for this variant"
 *     ),
 *     @OA\Property(
 *         property="view",
 *         type="integer",
 *         description="Number of variant views"
 *     )
 * )
 */
class VariantResource extends JsonResource
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
            'product' => ProductResource::make($this->whenLoaded('product')),
            'attribute' => AttributeResource::make($this->whenLoaded('attribute')),
            'vendors' => VendorResource::collection($this->whenLoaded('vendors')),
            'primaryImage' => $this->whenLoaded('media' , MediaResource::make($this->getFirstMedia('images'))),
            'value' => $this->when($this->value , $this->value),
            'additionalPrice' => $this->when($this->additional_price , $this->additional_price),
            'quantity' => $this->when($this->quantity , $this->quantity),
            'view' => $this->when($this->view , $this->view),
            'attributes' => AttributeItemResource::collection($this->whenLoaded('attributes')),
            'images' => $this->whenLoaded('media', function () {
                return MediaResource::collection($this->getMedia('images'));
            }),
        ];
    }
}