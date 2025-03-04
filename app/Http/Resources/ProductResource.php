<?php

namespace App\Http\Resources;

use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ProductResource",
 *     title="Product Resource",
 *     description="Product resource representation",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         description="Product ID"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Product name"
 *     ),
 *     @OA\Property(
 *         property="summary",
 *         type="string",
 *         description="Brief summary of the product"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Detailed description of the product"
 *     ),
 *     @OA\Property(
 *         property="price",
 *         type="number",
 *         format="float",
 *         description="Product price"
 *     ),
 *     @OA\Property(
 *         property="quantity",
 *         type="integer",
 *         description="Available quantity"
 *     ),
 *     @OA\Property(
 *         property="view",
 *         type="integer",
 *         description="Number of product views"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         ref="#/components/schemas/ProductStatusResource",
 *         description="Product status information"
 *     ),
 *     @OA\Property(
 *         property="vendor",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/ReviewResource"),
 *         description="Product reviews"
 *     ),
 *     @OA\Property(
 *         property="reviews",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/VariantResource"),
 *         description="Product variants"
 *     )
 * )
 */
class ProductResource extends JsonResource
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
            'summary' => $this->when($this->summary , $this->summary),
            'description' => $this->when($this->description , $this->description),
            'price' => $this->when($this->price , $this->price),
            'quantity' => $this->when($this->quantity , $this->quantity),
            'slug' => $this->slug,
            'view' => $this->when($this->view , $this->view),
            'primaryImage' => $this->whenLoaded('media' , MediaResource::make($this->getFirstMedia('images'))),
            'status' => ProductStatusResource::make($this->whenLoaded('status')),
            'vendors' => VendorResource::collection($this->whenLoaded('vendors')),
            'variants' => VariantResource::collection($this->whenLoaded('productVariants')),
            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
            'attributes' => AttributeItemResource::collection($this->whenLoaded('attributes')),
            'images' => $this->whenLoaded('media', function () {
                return MediaResource::collection($this->getMedia('images'));
            }),
        ];
    }
}